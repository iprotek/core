import { onMounted, onUnmounted } from "vue";

export function usePingKeepAlive() {
    const TAB_ID = generateId();
    const STATE_KEY = "iprotek_ping_keepalive_state";
    const CHANNEL_NAME = "iprotek_ping_keepalive_channel";
    const FALLBACK_MSG_KEY = "iprotek_ping_fallback_msg";

    const INTERVAL_VISIBLE = 60000;  // 1 minute (normal)
    const INTERVAL_HIDDEN = 300000;  // 5 minutes (relaxed when all tabs hidden)
    const TTL = 65000;               // Lease expiry buffer for visible leader

    let checkTimeout = null;
    let isCurrentLeader = false;
    let channel = null;

    // Helper: non-crypto random UUID generator for older browsers
    function generateId() {
        if (typeof window !== "undefined" && window.crypto?.randomUUID) {
            return crypto.randomUUID();
        }
        return (
            Date.now().toString(36) +
            Math.random().toString(36).substring(2, 10)
        );
    }

    // Helper: check tab visibility state
    function isTabVisible() {
        return typeof document !== "undefined" && document.visibilityState === "visible";
    }

    // Shared state management in localStorage
    function getSharedState() {
        try {
            const val = localStorage.getItem(STATE_KEY);
            if (val) {
                return JSON.parse(val);
            }
        } catch (e) {}
        return {
            leaderId: null,
            leaderTimestamp: 0,
            leaderVisible: false,
            lastPingTimestamp: 0
        };
    }

    function setSharedState(state) {
        try {
            localStorage.setItem(STATE_KEY, JSON.stringify(state));
        } catch (e) {}
    }

    function updateSharedState(updates) {
        const current = getSharedState();
        setSharedState({ ...current, ...updates });
    }

    // Broadcast messages (with fallback for tabs that don't support BroadcastChannel)
    function initBroadcastChannel() {
        try {
            if (typeof window !== "undefined" && window.BroadcastChannel) {
                channel = new BroadcastChannel(CHANNEL_NAME);
                channel.onmessage = (e) => handleBroadcastMessage(e.data);
            }
        } catch (e) {}

        // Fallback or secondary backup via storage event
        window.addEventListener("storage", handleStorageEvent);
    }

    function broadcast(msg) {
        const packet = { ...msg, sender: TAB_ID, timestamp: Date.now() };
        if (channel) {
            try {
                channel.postMessage(packet);
            } catch (e) {}
        } else {
            try {
                localStorage.setItem(FALLBACK_MSG_KEY, JSON.stringify(packet));
            } catch (e) {}
        }
    }

    function handleBroadcastMessage(msg) {
        if (!msg || msg.sender === TAB_ID) return;

        switch (msg.type) {
            case "CLAIM_LEADER":
                // If another tab claims leadership, check if they have higher priority
                // Priority: Visible > Hidden. Equal visibility: lexicographically smaller ID.
                const weAreVisible = isTabVisible();
                const senderVisible = !!msg.visible;
                
                if (isCurrentLeader) {
                    if (!weAreVisible && senderVisible) {
                        // Yield to visible tab
                        yieldLeadership();
                    } else if (weAreVisible === senderVisible && msg.sender < TAB_ID) {
                        // Tie breaker: yield to smaller ID
                        yieldLeadership();
                    }
                }
                break;

            case "YIELD_LEADER":
                // Leader yielded. Immediately evaluate and run election.
                tick();
                break;

            case "HEARTBEAT":
                // Keep local tracking updated
                if (isCurrentLeader && msg.sender !== TAB_ID) {
                    // Two leaders detected! Resolve tie
                    const weAreVisibleHeartbeat = isTabVisible();
                    const senderVisibleHeartbeat = !!msg.visible;
                    if (!weAreVisibleHeartbeat && senderVisibleHeartbeat) {
                        yieldLeadership();
                    } else if (weAreVisibleHeartbeat === senderVisibleHeartbeat && msg.sender < TAB_ID) {
                        yieldLeadership();
                    }
                }
                break;

            case "REQUEST_LEADER":
                // Respond if we are the leader
                if (isCurrentLeader) {
                    broadcast({
                        type: "HEARTBEAT",
                        visible: isTabVisible()
                    });
                }
                break;
        }
    }

    function handleStorageEvent(e) {
        if (e.key === FALLBACK_MSG_KEY && e.newValue) {
            try {
                const packet = JSON.parse(e.newValue);
                handleBroadcastMessage(packet);
            } catch (err) {}
        }
        if (e.key === STATE_KEY && e.newValue) {
            // If state changed externally, check if we need to adjust
            const state = getSharedState();
            if (state.leaderId && state.leaderId !== TAB_ID) {
                isCurrentLeader = false;
            }
        }
    }

    // Leader Election Logic
    async function attemptElection() {
        const visible = isTabVisible();
        
        // Random backoff between 0 and 100ms to prevent collision on simultaneous loads
        const backoff = Math.floor(Math.random() * 100);
        await new Promise((resolve) => setTimeout(resolve, backoff));

        const state = getSharedState();
        const now = Date.now();

        // Expired condition (for hidden leader, the lease is relative to hidden interval)
        const leaseDuration = state.leaderVisible ? TTL : INTERVAL_HIDDEN + 5000;
        const isExpired = !state.leaderId || (now - state.leaderTimestamp > leaseDuration);
        
        // Takeover condition: current leader is hidden but this tab is visible
        const canTakeOver = !state.leaderVisible && visible && state.leaderId !== TAB_ID;

        if (isExpired || canTakeOver) {
            const newState = {
                leaderId: TAB_ID,
                leaderTimestamp: now,
                leaderVisible: visible,
                lastPingTimestamp: state.lastPingTimestamp || 0
            };
            setSharedState(newState);

            // Double-check our claim (write-verification check)
            const verifiedState = getSharedState();
            if (verifiedState.leaderId === TAB_ID) {
                isCurrentLeader = true;
                broadcast({ type: "CLAIM_LEADER", visible });
            }
        }
    }

    function yieldLeadership() {
        isCurrentLeader = false;
        const state = getSharedState();
        if (state.leaderId === TAB_ID) {
            updateSharedState({
                leaderId: null,
                leaderTimestamp: 0,
                leaderVisible: false
            });
            broadcast({ type: "YIELD_LEADER" });
        }
        tick();
    }

    // Main Ping logic
    async function ping() {
        try {
            await fetch("/ping", {
                method: "GET",
                credentials: "include",
                cache: "no-store",
            });
        } catch (e) {
            // Network failures are ignored but logged to console in dev environments
            console.error("Keepalive ping failed:", e);
            throw e; // rethrow to let tick know ping failed
        }
    }

    // The core scheduler loop
    async function tick() {
        if (checkTimeout) clearTimeout(checkTimeout);

        const now = Date.now();
        const state = getSharedState();
        const visible = isTabVisible();

        const leaseDuration = state.leaderVisible ? TTL : INTERVAL_HIDDEN + 5000;
        const isExpired = !state.leaderId || (now - state.leaderTimestamp > leaseDuration);
        const canTakeOver = !state.leaderVisible && visible && state.leaderId !== TAB_ID;

        if (isExpired || canTakeOver) {
            await attemptElection();
        }

        const updatedState = getSharedState();
        isCurrentLeader = (updatedState.leaderId === TAB_ID);

        if (isCurrentLeader) {
            const interval = visible ? INTERVAL_VISIBLE : INTERVAL_HIDDEN;
            const timeSinceLastPing = now - updatedState.lastPingTimestamp;

            if (timeSinceLastPing >= interval) {
                try {
                    await ping();
                    updateSharedState({
                        leaderId: TAB_ID,
                        leaderTimestamp: Date.now(),
                        leaderVisible: visible,
                        lastPingTimestamp: Date.now()
                    });
                    broadcast({ type: "HEARTBEAT", visible });
                } catch (e) {
                    // Even if network fails, refresh lease to retain leadership and avoid thrashing
                    updateSharedState({
                        leaderId: TAB_ID,
                        leaderTimestamp: Date.now(),
                        leaderVisible: visible
                    });
                }
            } else {
                // Not time to ping yet, just refresh heartbeat lease
                updateSharedState({
                    leaderId: TAB_ID,
                    leaderTimestamp: Date.now(),
                    leaderVisible: visible
                });
            }

            // Schedule next check right before the interval ends
            const remainingTime = Math.max(1000, interval - (Date.now() - getSharedState().lastPingTimestamp));
            scheduleNextCheck(remainingTime);
        } else {
            // Follower tab: schedule next check right around when the leader lease is expected to expire
            const leaderAge = Date.now() - updatedState.leaderTimestamp;
            const timeUntilExpiry = Math.max(2000, leaseDuration - leaderAge);
            // Limit maximum follower sleep time to 15 seconds to ensure visibility changes and failovers are caught quickly
            scheduleNextCheck(Math.min(15000, timeUntilExpiry));
        }
    }

    function scheduleNextCheck(delayMs) {
        if (checkTimeout) clearTimeout(checkTimeout);
        checkTimeout = setTimeout(tick, delayMs);
    }

    // Handle Page visibility changes
    function handleVisibilityChange() {
        if (document.visibilityState === "hidden" && isCurrentLeader) {
            // Yield leadership so visible tabs take over immediately
            yieldLeadership();
        } else {
            tick();
        }
    }

    function start() {
        initBroadcastChannel();
        
        // Request leader status from other tabs immediately
        broadcast({ type: "REQUEST_LEADER" });

        document.addEventListener("visibilitychange", handleVisibilityChange);

        // Run election/tick cycle
        tick();
    }

    function stop() {
        if (checkTimeout) clearTimeout(checkTimeout);
        document.removeEventListener("visibilitychange", handleVisibilityChange);
        window.removeEventListener("storage", handleStorageEvent);

        if (isCurrentLeader) {
            const state = getSharedState();
            if (state.leaderId === TAB_ID) {
                setSharedState({
                    leaderId: null,
                    leaderTimestamp: 0,
                    leaderVisible: false,
                    lastPingTimestamp: state.lastPingTimestamp
                });
                broadcast({ type: "YIELD_LEADER" });
            }
        }

        if (channel) {
            try {
                channel.close();
            } catch (e) {}
        }
    }

    onMounted(() => {
        start();
    });

    onUnmounted(() => {
        stop();
    });
}