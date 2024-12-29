window.XposeSocket = async function(uri, cluster, app_id, key, is_guest = 0, account_id = 0){
    
    return ( async()=>{
        var url = `${uri}?cluster=${cluster}&app_id=${app_id}&key=${key}`;
        if(is_guest){
            url += `&is_guest=${is_guest}`;
        }
        if(account_id){
            url += `&account_id=${account_id}`;
        }

        const socket = await window.openWebSocketWithTimeout(url, 3000);
        if(!socket){
            return;
        }

        socket.settings = {
            cluster: cluster,
            app_id: app_id,
            key: key
        };
    

        socket.subscribe = function( channel ){

            return window.XposeSocketSubscribe(socket, channel);
        
        };

        socket.status = function(app_id, status_cluster = null){
        
            return window.XposeSocketStatus(socket, app_id, status_cluster);
        
        }

        socket.sendMessage = function(secret, channel, event ,data){
            socket.send( JSON.stringify( {
                secret: secret,
                data: data,
                event: event,
                channel: channel,
                type: 'message',
                key: key
            } )
            );
        } 

        socket.onlineCheck = function(account_id){
            socket.send(    
                JSON.stringify( {
                    account_id: account_id,
                    type: 'online-check'
                } )
            );
        }

        socket.onlineCheckGuest = function(account_id){
            socket.send( 
                JSON.stringify( {
                    account_id: account_id,
                    type: 'online-check-guest'
                } )
            );
        }

        return socket;
    })();
    //return socket;
}

window.openWebSocketWithTimeout = function(url, timeout = 5000) {
    return new Promise((resolve, reject) => { 
        const ws = new WebSocket(url);

        window.XposeSocketSetMessage(ws);

        const timer = setTimeout(() => {
            ws.close(); // Close the WebSocket if not connected within the timeout
            reject(new Error('WebSocket connection timed out'));
        }, timeout);

        ws.onopen = () => {
            clearTimeout(timer); // Clear the timer on successful connection
            resolve(ws);
        };

        ws.onerror = (err) => {
            clearTimeout(timer); // Clear the timer on error
            reject(err);
        };
    }).then(ws=>{
        return ws;
    });
}


window.XposeSocketStatus = function(socket, app_id, status_cluster){
    var res = {
        callback:null,
        //This is trigger for then function
        result_data:function(data){
            if(!this.callback){
                //console.log("Invalidate callback");
                return;
            }
            this.callback(data);
        },
        //Triggering results for callbacks
        then: function(fn){
            this.callback = fn;
        },
        type:'status',
        app_id: app_id,
        status_key: window.XposegetRandomString(36)
    };

    if(!socket.callBacks)
        socket.callBacks = [];
    socket.callBacks.push(res);

    //GET STATUS
    socket.send(JSON.stringify({
        type:'status',
        status_key: res.status_key,
        app_id: res.app_id,
        status_cluster: status_cluster
    }));

    return res;
}

window.XposeSocketSubscribe = function(socket, channel){
    var fn = {};

    
    fn.listen = function(event){ 
        return window.XposeChannelEvent(socket, channel, event);
    };
    

    return fn;
}

window.XposeSocketSetMessage = function(socket){
    if(!socket.onmessage){
        socket.onmessage = function(evt){
            
            const data = JSON.parse(evt.data);
            
            if(!socket.callBacks){
                return;
            }
            socket.callBacks.forEach(item => {
                
                if(data.type == 'status' && data.type == item.type){
                    if(data.status_key == item.status_key){
                        item.result_data(data);
                    }
                }
                else if( data.type == 'subscribe' && data.channel == item.channel && item.event == data.event ){
                    if(data.subscribe_key == item.subscribe_key){
                        item.result_data(data);
                        
                        console.log("Success to subscribe on channel:"+data.channel+' at event:'+data.event);
                    }else{
                        console.error("Failed to subscribe on channel:"+data.channel+' at event:'+data.event);
                    }
                }
                else if(data.type == 'online-check-guest'){
                    item.result_data(data);
                }
                else if(data.type == 'online-check'){
                    item.result_data(data);
                }
                else if( data.channel == item.channel && item.event == data.event ){
                    if(item.then){ 
                        item.result_data(data);
                    }else{
                        console.log("Unable to submit 'then' is empty");
                    }
                }
            });

        };
    }
}

window.XposeChannelEvent = function(socket, channel, event){ 

    //STORE TO socket the function for executions

    var res = {
        callback:null,
        //This is trigger for then function
        result_data:function(data){
            if(!this.callback){
                //console.log("Invalidate callback");
                return;
            }
            this.callback(data);
        },
        //Triggering results for callbacks
        then: function(fn){
            this.callback = fn;
        },
        event: event,
        channel: channel,
        subscribe_key: window.XposegetRandomString(36)
    };
    if(!socket.callBacks)
        socket.callBacks = [];
    socket.callBacks.push(res);


    //Subscribe HERE
    socket.send( JSON.stringify(
        {
            subscribe_key: res.subscribe_key,
            channel: channel,
            event: event,
            key: socket.settings.key,
            type: 'subscribe'
        }
    ) );
    return res;
}

window.XposegetRandomString = function(length=36) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}

window.PusherSet = false;
window.iProtekPusher = null;
window.PusherFlag = false;
window.CheckingInfo = false;
window.XposeSetSocket = function(fn, waitFn=null, is_guest=0, account_id = 0){
    
    WebRequest2('GET', '/api/push-info').then(resp=>{
        resp.json().then(data=>{ 
            if(fn){
                data.is_guest = is_guest;
                data.account_id = account_id;
                fn(data);
            }

            //Preventing from proceeding
            if(window.PusherSet || window.iProtekPusher) return;

            //Triggers that to set its pushertypes
            if(data.is_active  && data.name == 'PUSHER.COM'){
                window.PusherSet = true;
                if(waitFn){
                    waitFn(data);
                }
            }
            else if(data.is_active && data.name == "iProtek WebSocket"){
               (async()=>{ 
                if(!window.iProtekPusher)
                    window.iProtekPusher = await window.XposeSocket(data.url, data.cluster, data.app_id, data.key, is_guest, account_id);
                    if(waitFn){
                        waitFn(data);
                    }
                })();
            }else{
                //window.PusherFlag = false;
            }
        });
    });
}

window.GuestChatSocket = function(fn, waitFn = null){
    if(window.PusherFlag || window.CheckingInfo){
        return;
    }
    window.PusherFlag = true;
    window.CheckingInfo = true; 
    WebRequest2('GET', '/guest-chat/chat-info').then(resp=>{
        window.CheckingInfo = false;
        resp.json().then(data=>{
            if(data.guest_check){
                window.XposeSetSocket(fn, waitFn,  1, data.guest_chat_id);
            }
            else{
                window.PusherFlag = false;
            }
        })
    }); 
}