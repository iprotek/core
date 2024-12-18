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
                    <guest-chat-box @modal_close="show_chat=false " :chat_info="copy_chat_info" @reload_chat_info="loadChatInfo()" />
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
                copy_chat_info: this.chat_info
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
                        });
                    }
                });
            },
        },
        mounted:function(){
            //console.log("chat_info", this.chat_info);
            this.copy_chat_info = this.chat_info;
            this.loadChatInfo();
        },
        updated:function(){

        }
    }
</script>
