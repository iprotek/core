<template>
    <div>
        <div v-for="(chat,chatIndex) in messages" v-bind:key="'chat-'+_uid+'-'+chat.id+'-'+chatIndex">
            <div v-if="chat.is_self == false" class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                    <small class="direct-chat-name float-left" v-text="chat.sender_info.name"> </small>
                    <small class="direct-chat-timestamp float-right" v-if="chat.created_at_diff" v-text="chat.created_at_diff" :title="chat.created_at"></small>
                </div> 
                <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                <div class="direct-chat-text mr-3">
                    <span v-if="chat.pay_account_id == 0" v-text="chat.content"> </span> 
                    <span v-else v-html="chat.content"> </span> 
                    <!--<span v-else > <span class="fa fa-paperclip"></span> Attached File </span>-->
                    <div style="line-height: 7px;font-style:italic;" v-if="chat.read_at || chat.received_at" >
                        <small class="align-center" style="font-size: 14px;" v-if="chat.read_at || chat.received_at">
                            <small class="fa fa-check"></small> 
                            <small style="font-size:10px;" class="mb-2" v-if="chat.read_at != chat.received_at">
                                <span :title="chat.read_at" v-if="chat.received_at">  
                                    Received <span v-text="chat.received_at"> </span> 
                                </span>
                                <span :title="chat.read_at"> <span v-if="chat.received_at_diff"> | </span> Seen <span v-text="chat.read_at_diff">  </span> </span>
                            </small>
                            <small style="font-size:10px;" class="mb-2" :title="chat.received_at_diff">
                                Seen & Received <span v-text="chat.received_at_diff">  </span>
                            </small>
                        </small>
                    </div>
                </div> 
            </div> 
            <div v-else class="direct-chat-msg right">
                <div class="direct-chat-infos clearfix">
                    <span :class="'direct-chat-name float-right '+(is_active ? 'text-primary':'')" v-text="chat.sender_info.name"></span>
                    <span class="direct-chat-timestamp float-left" v-if="chat.created_at_diff" v-text="chat.created_at_diff" :title="chat.created_at"></span>
                </div> 
                <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                <div class="direct-chat-text ml-3">
                    <span v-if="chat.pay_account_id == 0" v-text="chat.content"> </span> 
                    <span v-else v-html="chat.content"> </span>  
                    <!--<span v-else > <span class="fa fa-paperclip"></span> Attached File </span>-->
                    <div style="line-height: 7px;font-style:italic;" v-if="chat.received_at || chat.read_at" >
                        <small class="align-center" style="font-size: 14px;" v-if="chat.received_at || chat.read_at">
                            <small class="fa fa-check"></small> 
                            <small style="font-size:10px;" class="mb-2" v-if="chat.received_at != chat.read_at">
                                <span :title="chat.received_at" v-if="chat.received_at_diff">  
                                    Received <span v-text="chat.received_at_diff"> </span> 
                                </span>
                                <span :title="chat.read_at"> <span v-if="chat.received_at_diff"> | </span> Seen <span v-text="chat.read_at_diff">  </span> </span>
                            </small>
                            <small style="font-size:10px;" class="mb-2" :title="chat.received_at_diff">
                                Seen & Received <span v-text="chat.received_at_diff">  </span>
                            </small>
                        </small>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</template>

<script>
    export default {
        props:[  ],
        components: { 
        },
        data: function () {
            return {
                messages:[]
            }
        },
        methods: { 
            loadMessage:function(){
                var vm = this;
                WebRequest2('GET', '/guest-chat/messages').then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.messages = data.data;
                    })
                })
            }
        },
        mounted:function(){
            this.loadMessage(); 
        },
        updated:function(){

        }
    }
</script>
