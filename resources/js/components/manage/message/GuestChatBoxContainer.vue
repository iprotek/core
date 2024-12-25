<template>
    <div>
        
        <div v-if="show_chat == false" class="row justify-content-end">
            <div  class="col-sm-2 text-center">
                <span @click="show_chat=true" class="ion ion-chatbubble-working text-white" style="font-size:80px; text-shadow: 0px 0px 4px black; cursor:pointer;"></span>
            </div>
        </div>

        <div v-else style="pointer-events:none;">
            <div class="row align-items-center justify-content-center" style="pointer-events:none;">
                <div class="col-md-4" style="pointer-events:all;">
                    <guest-chat-box ref="guest_chat_box" @modal_close="show_chat=false " :chat_info="copy_chat_info" @reload_chat_info="loadChatInfo()" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ChatBoxVue from './GuestChatBox.vue';
    export default {
        props:[ "chat_info" ],
        components: { 
            "guest-chat-box":ChatBoxVue,
            
        },
        data: function () {
            return {  
                show_chat:false,
                copy_chat_info: this.chat_info,
                pusher_loaded:false,
                pusher_key:'',
                pusher_cluster:'',
                pusher_name:'',
                pusher_app_id:'',
                is_active:false
            }
        },
        methods: {  
            loadChatInfo:function(){
                var vm = this;
                WebRequest2("GET", "/guest-chat/chat-info").then(resp=>{
                    if( resp.ok ){
                        resp.json().then(data=>{
                            //vm.$emit('update:chat_info', data);
                            //console.log(data, vm.chat_info);
                            vm.copy_chat_info = data;
                            vm.loadPusher(vm.pusher_key, vm.pusher_cluster);
                        });
                    }
                });
            },
            loadPusher:function( key, cluster){
                var vm = this;
                if(!vm.is_active) return;

                if(!vm.copy_chat_info.guest_chat_id)
                    return;
                if(vm.pusher_loaded) return;

                vm.pusher_loaded = true;
 
                if(vm.pusher_name == 'PUSHER.COM'){

                    Pusher.logToConsole = true;

                    var pusher = new Pusher( vm.pusher_key, {
                        cluster: vm.pusher_cluster
                    });

                    var chat_channel = pusher.subscribe('chat-channel');
                    chat_channel.bind('notify', function(data) {

                        if( data.guest_chat_id == vm.copy_chat_info.guest_chat_id && window.guest_chat_messages){
                            window.guest_chat_messages.loadNext(data.content_id);
                        }

                        //console.log(data);
                        return data;
                    });

                }else if(vm.pusher_name == 'iProtek WebSocket' && window.iProtekPusher){

                    var channel = window.iProtekPusher.subscribe('chat-channel');
                    channel.listen('notify').then(result=>{
                        var data = result.data;
                        if(data && result.type == 'message'){
                            if( data.guest_chat_id == vm.copy_chat_info.guest_chat_id && window.guest_chat_messages){
                                window.guest_chat_messages.loadNext(data.content_id);
                            }
                        }
                        return data;
                    });


                }
            },
            loadPusherInfo: function(){

                var vm = this;
                
                //FOR MESSAGING PUSH NOTIF INFO
                //WebRequest2('GET', '/api/push-info').then(resp=>{
                    //resp.json().then(data=>{
                        //console.log("NOTIF SETTINGS", data);
                        window.XposeSetSocket(data=>{
                            vm.pusher_name = data.name;
                            vm.pusher_key = data.key;
                            vm.pusher_cluster = data.cluster;
                            vm.pusher_app_id = data.app_id;
                            vm.is_active = data.is_active;
                            if(data.is_active  && data.name == 'PUSHER.COM'){
                                vm.loadPusher(vm.pusher_key, vm.pusher_cluster);
                            }
                            else if(data.is_active && data.name == "iProtek WebSocket"){
                            (async()=>{ 
                                if(!window.iProtekPusher)
                                    window.iProtekPusher = await window.XposeSocket(data.url, data.cluster, data.app_id, data.key);
                                vm.loadPusher(vm.pusher_key, vm.pusher_cluster);
                            })();
                            }
                        });
                    //});

                    
                //});
                
            }
        },
        mounted:function(){
            this.copy_chat_info = this.chat_info;
            this.loadPusherInfo();
            
        },
        updated:function(){

        }
    }
</script>
