<template>
    <div style="pointer-events:none;">
        <div class="row justify-content-end align-items-end" style="pointer-events:none;">
            <div class="col-md-3" v-for="(chatItem,chatIndex) in chats" v-bind:key="'chat-item-'+chatIndex" style="pointer-events:all;">
                <chat-box :value="chatItem"></chat-box>
            </div>
        </div>
    </div>
</template>

<script>
    import ChatBoxVue from './ChatBox.vue';
    export default {
        props:[  ],
        components: { 
            "chat-box":ChatBoxVue
        },
        data: function () {
            return { 
                chats:[]
            }
        },
        methods: { 
            addChatMessage:function(chatItem){
                var vm = this;
                //If already exists it will not proceed.
                var exists = vm.chats.filter(a=> a == chatItem)[0];
                if(exists)
                    return;

                setTimeout(()=>{
                    vm.chats.push(chatItem);
                    if(vm.chats.length >  3){
                        vm.chats.shift();
                    }
                }, 50);
 
            },
            removeChatMessage:function(chatItem){
                 this.chats = this.chats.filter(a=>a!=chatItem);//[chatItem];
            },
            inActiveAllMessages:function(exceptItem){
                this.chats.forEach(a=>{
                    if(a!=exceptItem)
                        a.setInActive();
                });
            }
        },
        mounted:function(){   
            window.addChatMessage = this.addChatMessage;  
            window.removeChatMessage = this.removeChatMessage;
            window.inActiveAllMessages = this.inActiveAllMessages;
        },
        updated:function(){

        }
    }
</script>
