window.XposeSocket = function(url, cluster, app_id, key){
    //var socket = new WebSocket(`${url}?cluster=${cluster}&app_id=${app_id}&key=${key}`);
   return (async()=>{
        const socket = await window.openWebSocketWithTimeout(`${url}?cluster=${cluster}&app_id=${app_id}&key=${key}`, 3000);

        if(!socket)
            return;

        socket.settings = {
            cluster: cluster,
            app_id: app_id,
            key: key
        };
    

        socket.subscribe = function( channel ){

            return window.XposeSocketSubscribe(socket, channel);

        };

        socket.socket = function(app_id){
            return window.XposeSocketStatus(socket, app_id);
        }
        return socket;
    })();
 
    //return socket;
}

window.openWebSocketWithTimeout = function(url, timeout = 5000) {
    return new Promise((resolve, reject) => {
        const ws = new WebSocket(url);

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


window.XposeSocketStatus = function(socket, app_id = ''){
    var res = {
        then: null,
        app_id: app_id,
        status_key: window.getRandomString(36)
    };

    if(!socket.callBacks)
        socket.callBacks = [];
    socket.callBacks.push(res);

    
    //SET MESSAGE TRIGGER
    window.XposeSocketSetMessage(socket);


    //GET STATUS
    socket.send(JSON.stringify({
        type:'status',

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
            socket.callBacks.forEach(item => {
                
                if(data.type == 'status'){
                    if(data.status_key == item.status_key)
                        item.then(data);
                }
                if( data.type == 'subscribe' && data.channel == item.channel && item.event == data.event ){
                    if(data.subscribe_key == item.subscribe_key){
                        item.then(data);
                        //console.log("subcribe info", data);
                    }
                }
                else if( data.channel == item.channel && item.event == data.event ){
                    if(item.then){
                        item.then(data);
                    }else{
                        console.log("Unable to submit. Then empty.")
                    }
                }
            });

        };
    }
}

window.XposeChannelEvent = function(socket, channel, event){ 

    //STORE TO socket the function for executions
    var res = {
        then: null,
        event: event,
        channel: channel,
        subscribe_key: window.getRandomString(36)
    };
    if(!socket.callBacks)
        socket.callBacks = [];
    socket.callBacks.push(res);

    //SET MESSAGE TRIGGER
    window.XposeSocketSetMessage(socket);


    //Subscribe HERE
    socket.send( JSON.stringify(
        {
            subscribe_key: res.subscribe_key,
            channel: channel,
            event: event,
            type: 'subscribe'
        }
    ) );


    return res;
}

window.getRandomString = function(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}