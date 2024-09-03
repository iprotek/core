<template> 
    <div @click="setActive()" :class="'card '+(is_active ? 'card-primary':'card-secondary')+' card-outline direct-chat '+(is_active ? 'direct-chat-primary':'direct-chat-secondary')+' '+(is_minimize ? 'collapsed-card':'')" style="min-width:300px;">
        <div class="card-header">
            <img :data-card-widget="(is_minimize?'collapse':'')" class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image" style="width:30px; height:30px;" @click=" (is_minimize ? isMinimizeClick():'')" :style="(is_minimize ? 'cursor:pointer;':'')"> 
            &nbsp;
            <h3 :class="'card-title py-1 ml-1 '+(is_active ? 'text-primary':'')" :data-card-widget="(is_minimize?'collapse':'')" @click=" (is_minimize ? isMinimizeClick():'')" :style="(is_minimize ? 'cursor:pointer;':'')">
                    Shadow - Small
            </h3>
            <div class="card-tools">
                <span title="3 New Messages" :class="'badge '+(is_active ? 'bg-primary':'bg-warning')">3</span>
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

            <div class="direct-chat-messages">

                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div> 
                    <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                    <div class="direct-chat-text">
                        <span>
                            Is this template really for free? That's unbelievable!
                        </span>
                        <div style="line-height: 7px;font-style:italic;">
                            <small class="align-center" style="font-size: 14px;">
                                <small class="fa fa-check"></small> 
                                <small style="font-size:10px;" class="mb-2">
                                  <span>  Received 11AM </span> | <span>Seen 12AM</span>
                                </small> 
                            </small>
                        </div>
                    </div> 
                </div> 
                <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                        <span :class="'direct-chat-name float-right '+(is_active ? 'text-primary':'')">Sarah Bullock</span>
                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div> 
                    <img class="direct-chat-img" src="/iprotek/images/temp-image.png" alt="Message User Image"> 
                    <div class="direct-chat-text">
                        <span>
                            You better believe it!
                        </span>
                        <div style="line-height: 7px;font-style:italic;">
                            <small class="align-center" style="font-size: 14px;">
                                <small class="fa fa-check"></small> 
                                <small style="font-size:10px;" class="mb-2">
                                  <span>  Received 11AM </span> | <span>Seen 12AM</span>
                                </small> 
                            </small>
                        </div>
                    </div> 
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
        <div class="card-footer" :style="'display: '+(is_minimize ? 'none;':'block;')"> 
            <div class="input-group">
                <span class="input-group-text p-2 px-3">
                    <span :class="'fa fa-paperclip text-lg '+(is_active ? 'text-primary':'')" style="cursor:pointer;"></span>
                </span>
                <input :id="chat_id" type="text" name="message" placeholder="Type Message ..." class="form-control">
                <span class="input-group-append">
                    <button type="submit" :class="'btn '+(is_active ? 'btn-primary':'btn-secondary')">Send</button>
                </span>
            </div> 
        </div>
    </div> 
</template>

<script>
    export default {
        props:[ "value" ],
        components: { 
        },
        data: function () {
            return {
                is_active:false,
                is_minimize:false,
                chat_id:'chat-input-text-'+this._uid
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
                        document.querySelector('#'+vm.chat_id).focus();
                    }, 600);
                }
            },
            setInActive:function(){
                if(this.is_active)
                    this.is_active = false;
            }
            
        },
        mounted:function(){     
            this.value.setInActive = this.setInActive;
        },
        updated:function(){

        }
    }
</script>
