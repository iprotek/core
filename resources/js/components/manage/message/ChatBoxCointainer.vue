<template>
    <div>
        <div style="pointer-events:none;">
            <div class="row justify-content-end align-items-end" style="pointer-events:none;"> 
                    <div v-for="(chatItem,chatIndex) in chats" :class="chatItem ? 'col-md-3' : ''" :style="chatItem ? '' :'display:none;'"  v-bind:key="'chat-item-'+chatIndex" style="pointer-events:all;">
                        <chat-box v-if="chatItem" :value="chatItem"></chat-box>
                    </div> 
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
            addChatMessage:function(chatItem, type){
                var vm = this;
                //If already exists it will not proceed.
                var exists = vm.chats.filter(a=> (a && a.id == chatItem.id && chatItem.type == a.type))[0]; 
                if(exists){ 
                    exists.setActive();
                    return;
                }

                //SET CHAT TYPE FOR CLASSIFCATION
                setTimeout(()=>{
                    chatItem.type = type;
                    vm.chats.push(chatItem);
                    var activeChats = vm.chats.filter(a=> a != null);
                    if(activeChats.length > 3){
                        var actIndex = vm.chats.indexOf(activeChats[0]);
                        vm.chats[actIndex] = null;
                        document.querySelector('#'+type+'-chatbox-item-'+activeChats[0].id).remove();
                    }
                    setTimeout(()=>{
                        chatItem.setActive();
                    }, 50);
                }, 50);
 
            },
            removeChatMessage:function(chatItem){
                //console.log("Chat Item", chatItem);
                //this.chats = this.chats.filter(a=>a.id != chatItem.id );//[chatItem];
                var index = this.chats.indexOf(chatItem);
                if(index >= 0){
                    this.chats[index] = null;
                }
                document.querySelector('#'+chatItem.type+'-chatbox-item-'+chatItem.id).closest('div.col-md-3').remove();
                //delete this.chats[index];
                //console.log(this.chats.length, index);
            },
            inActiveAllMessages:function(exceptItem){
                this.chats.forEach(a=>{
                    if(a && a!=exceptItem)
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
