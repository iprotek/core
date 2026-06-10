import { onMounted, onUnmounted } from "vue";

export function usePingKeepAlive() {
    const TAB_ID = generateId();
    const KEY = "ping_leader";
    const INTERVAL = 60000; // 1 minute
    const TTL = 65000; // leader expiry buffer

    let timer = null;

    function generateId() {
        if (window.crypto?.randomUUID) {
            return crypto.randomUUID();
        }

        // fallback (works everywhere)
        return (
            Date.now().toString(36) +
            Math.random().toString(36).substring(2, 10)
        );
    }

    function getLeader() {
        return JSON.parse(localStorage.getItem(KEY) || "null");
    }

    function setLeader(data) {
        localStorage.setItem(KEY, JSON.stringify(data));
    }

    function isLeader() {
        const leader = getLeader();
        const now = Date.now();

        if (!leader || now - leader.timestamp > TTL) {
            setLeader({ id: TAB_ID, timestamp: now });
            return true;
        }

        return leader.id === TAB_ID;
    }

    function refreshLeader() {
        const leader = getLeader();
        if (leader?.id === TAB_ID) {
            setLeader({ id: TAB_ID, timestamp: Date.now() });
        }
    }

    async function ping() {
        await fetch("/ping", {
            method: "GET",
            credentials: "include",
            cache: "no-store",
        });
    }

    function start() {
        timer = setInterval(async () => {
            if (isLeader()) { 
                await ping();
                refreshLeader();
            }
        }, INTERVAL);
    }

    function stop() {
        if (timer) clearInterval(timer);
    }

    onMounted(() => {
         start();
         //console.log("Mounted");
    });

    onUnmounted(() => {
        stop();

        // optional cleanup if this tab is leader
        const leader = getLeader();
        if (leader?.id === TAB_ID) {
            localStorage.removeItem(KEY);
        }
    });
}