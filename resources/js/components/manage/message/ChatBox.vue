<template> 
    <div>
        <div v-if="value" @click="setActive()" :class="'card '+(is_active ? 'card-primary':'card-secondary')+' card-outline direct-chat '+(is_active ? 'direct-chat-primary':'direct-chat-secondary')+' '+(is_minimize ? 'collapsed-card':'')" style="min-width:300px;">
            <div class="card-header">
                <img :data-card-widget="(is_minimize?'collapse':'')" class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image" style="width:30px; height:30px;" @click=" (is_minimize ? isMinimizeClick():'')" :style="(is_minimize ? 'cursor:pointer;':'')"> 
                &nbsp;
                <h3 :class="'card-title py-1 ml-1 '+(is_active ? 'text-primary':'')" :data-card-widget="(is_minimize?'collapse':'')" @click=" (is_minimize ? isMinimizeClick():'')" :style="(is_minimize ? 'cursor:pointer;':'')">
                        {{ value.name }}
                </h3>
                <div class="card-tools">
                    <span v-if="value.count_unseen" title="3 New Messages" :class="'badge '+(is_active ? 'bg-danger':'bg-warning')" v-text="value.count_unseen"></span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" @click="isMinimizeClick()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <!--
                    <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                        <i class="fas fa-comments"></i>
                    </button>
                    -->
                    <button type="button" class="btn btn-tool" @click="removeChatItem(value)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div> 
            <div class="card-body" :style="'display: '+(is_minimize ? 'none;':'block;')">

                <div class="direct-chat-messages" :id="chatContainerEl" >
                    <div v-for="(chat,chatIndex) in messages" v-bind:key="'chat-'+_uid+'-'+chat.id+'-'+chatIndex">
                        <div v-if="chat.is_self == false" class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <small v-if="is_group && chat.from_pay_user_account" class="direct-chat-name float-left" v-text="chat.from_pay_user_account.name"> </small>
                                <small class="direct-chat-timestamp float-right" v-if="chat.created_at_diff" v-text="chat.created_at_diff" :title="chat.created_at"></small>
                            </div> 
                            <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                            <div class="direct-chat-text mr-3">
                                <span v-if="chat.chat_type == 'text'" v-text="chat.message"> </span>
                                <span v-else > <span class="fa fa-paperclip"></span> Attached File </span>
                                <div style="line-height: 7px;font-style:italic;" v-if="chat.actions && chat.actions.length > 0" >
                                    <small class="align-center" style="font-size: 14px;" v-if="chat.actions[0].received_at_diff || chat.actions[0].seen_at_diff">
                                        <small class="fa fa-check"></small> 
                                        <small style="font-size:10px;" class="mb-2" v-if="chat.actions[0].received_at != chat.actions[0].seen_at">
                                            <span :title="chat.actions[0].received_at" v-if="chat.actions[0].received_at_diff">  
                                                Received <span v-text="chat.actions[0].received_at_diff"> </span> 
                                            </span>
                                            <span :title="chat.actions[0].seen_at"> <span v-if="chat.actions[0].received_at_diff"> | </span> Seen <span v-text="chat.actions[0].seen_at_diff">  </span> </span>
                                        </small>
                                        <small style="font-size:10px;" class="mb-2" :title="chat.actions[0].received_at_diff">
                                            Seen & Received <span v-text="chat.actions[0].received_at_diff">  </span>
                                        </small>
                                    </small>
                                </div>
                            </div> 
                        </div> 
                        <div v-else class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span :class="'direct-chat-name float-right '+(is_active ? 'text-primary':'')" v-text="chat.from_pay_user_account.name"></span>
                                <span class="direct-chat-timestamp float-left" v-if="chat.created_at_diff" v-text="chat.created_at_diff" :title="chat.created_at"></span>
                            </div> 
                            <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                            <div class="direct-chat-text ml-3">
                                <span v-text="chat.message" v-if="chat.chat_type == 'text'"></span>
                                <span v-else > <span class="fa fa-paperclip"></span> Attached File </span>
                                <div style="line-height: 7px;font-style:italic;" v-if="chat.actions && chat.actions.length > 0" >
                                    <small class="align-center" style="font-size: 14px;" v-if="chat.actions[0].received_at_diff || chat.actions[0].seen_at_diff">
                                        <small class="fa fa-check"></small> 
                                        <small style="font-size:10px;" class="mb-2" v-if="chat.actions[0].received_at != chat.actions[0].seen_at">
                                            <span :title="chat.actions[0].received_at" v-if="chat.actions[0].received_at_diff">  
                                                Received <span v-text="chat.actions[0].received_at_diff"> </span> 
                                            </span>
                                            <span :title="chat.actions[0].seen_at"> <span v-if="chat.actions[0].received_at_diff"> | </span> Seen <span v-text="chat.actions[0].seen_at_diff">  </span> </span>
                                        </small>
                                        <small style="font-size:10px;" class="mb-2" :title="chat.actions[0].received_at_diff">
                                            Seen & Received <span v-text="chat.actions[0].received_at_diff">  </span>
                                        </small>
                                    </small>
                                </div>
                            </div> 
                        </div> 
                    </div>
                    <div class="text-center" v-if="errorMessage">
                        <code v-text="errorMessage"></code>
                    </div>
                </div> 
                <!--
                <div class="direct-chat-contacts">
                    <ul class="contacts-list">
                        <li>
                            <a href="#">
                                <img class="contacts-list-img" src="/iprotek/images/temp-image.png" alt="User Avatar">
                                <div class="contacts-list-info">
                                    <span class="contacts-list-name"> Count Dracula
                                        <small class="contacts-list-date float-right">2/28/2015</small>
                                    </span>
                                    <span class="contacts-list-msg">How have you been? I was...</span>
                                </div>
                            </a>
                        </li>
                    </ul> 
                </div> 
                -->
            </div> 
            <div  class="card-footer" :style="'display: '+(is_minimize ? 'none;':'block;')"> 
                <div class="input-group" v-if="!errorMessage">
                    <span class="input-group-text p-2 px-3">
                        <span :class="'fa fa-paperclip text-lg '+(is_active ? 'text-primary':'')" style="cursor:pointer;"></span>
                    </span>
                    <input v-model="sendText" @keyup.enter="sendContactMessage()" :id="chat_id" type="text" name="message" placeholder="Type Message ..." class="form-control" :readonly="isSend">
                    <span class="input-group-append">
                        <button @click="sendContactMessage()" type="submit" :class="'btn '+(is_active ? 'btn-primary':'btn-secondary')+' '+(isSend ? 'disabled':'')">Send</button>
                    </span>
                </div> 
            </div>
        </div> 
        <swal-alert ref="swal_alert"></swal-alert>
    </div>
</template>

<script>
    import SwalAlertVue from '../../common/SwalAlert.vue';
    export default {
        props:[ "value" ],
        components: { 
            "swal-alert":SwalAlertVue
        },
        data: function () {
            return {
                is_active:false,
                is_minimize:false,
                chat_id:'chat-input-text-'+this._uid,
                messages:[],
                is_group:false,
                sendText:'',
                chatContainerEl:'chat-container-'+this._uid,
                isSend:false,
                errorMessage:''

            }
        },
        methods: { 
            isMinimizeClick:function(){
                var vm = this;
                setTimeout(()=>{
                    vm.is_minimize = !vm.is_minimize;
                }, 500);
            },
            removeChatItem:function(chatItem){
                window.removeChatMessage(chatItem);
            },
            setActive:function(){
                var vm = this;
                window.inActiveAllMessages(this.value);
                if(!this.is_active){
                    this.is_active = true;
                    setTimeout(()=>{
                        var chat_input = document.querySelector('#'+vm.chat_id);
                        if(chat_input) 
                            chat_input.focus();
                    }, 600);
                }
            },
            setInActive:function(){
                if(this.is_active)
                    this.is_active = false;
            },
            loadContactMessages:function(is_add = false){
                var vm = this;
                vm.errorMessage = '';
                WebRequest2('GET', '/manage/message/dm/contact/'+this.value.app_user_account_id).then(resp=>{
                    resp.json().then(data=>{
                        console.log(data);
                        if(data.status == 1){
                            if(is_add == false){
                                vm.messages = data.result.data.reverse();

                                //scroll to bottom
                                setTimeout(()=>{
                                    var el = document.querySelector('#'+vm.chatContainerEl);
                                    //console.log(el);
                                    if(el){
                                        //el.scrollTo(0, el.scrollHeight);
                                        el.scrollTo({
                                            top: el.scrollHeight, // Scroll to the bottom
                                            behavior: 'smooth' // Enable smooth scrolling
                                        });
                                    }
                                })
                            } 
                        }else if(data.result){
                            //vm.errorMessage = data.result.message;
                            vm.errorMessage = "This requires login to proceed.";
                        }

                    })
                });


            },
            sendContactMessage:function(){
                
                var vm = this;
                if(vm.isSend){
                    return;
                }

                if(!vm.sendText.trim())
                    return;


                vm.isSend = true;
                var request = {
                    message:vm.sendText
                }
                WebRequest2('POST', '/manage/message/dm/contact/'+this.value.app_user_account_id, request).then(resp=>{
                    resp.json().then(data=>{
                        console.log(data);
                        vm.isSend = false;
                        if(data.status == 1){
                            if(data.result.status == 1){
                                vm.sendText = '';
                                vm.loadContactMessages();
                            }
                        }
                    })
                });

            }
            
        },
        mounted:function(){     
            if(this.value)
                this.value.setInActive = this.setInActive;
            this.setActive();
            
            console.log( "Chat info",this.value);

            this.loadContactMessages();
        },
        updated:function(){

        }
    }
</script>
