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
                pusher_cluster:''
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

                if(!vm.copy_chat_info.guest_chat_id)
                    return;
                if(vm.pusher_loaded) return;

                vm.pusher_loaded = true;

                setTimeout(()=>{

                    //Pusher.logToConsole = true;
                    //console.log("PUSHER INFO", key, cluster, vm.pusher_key, vm.pusher_cluster);
                    var pusher = new Pusher( vm.pusher_key, {
                        cluster: vm.pusher_cluster
                    });

                    var chat_channel = pusher.subscribe('chat-channel');
                    chat_channel.bind('notify', function(data) {
                        console.log(data);
                        return data;
                    });

                }, 2000);

            },
            loadPusherInfo:function(){

                var vm = this;
                
                //FOR MESSAGING PUSH NOTIF INFO
                WebRequest2('GET', '/api/push-info').then(resp=>{
                    resp.json().then(data=>{
                        //console.log("NOTIF SETTINGS", data);
                        if(data.is_active  && data.name == 'PUSHER.COM'){
                            vm.pusher_key = data.key;
                            vm.pusher_cluster = data.cluster;
                            vm.loadPusher(vm.pusher_key, vm.pusher_cluster);
                        }
                    });

                    vm.loadChatInfo();
                });
                
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
