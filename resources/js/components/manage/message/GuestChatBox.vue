<template>
    <div>
        <div class="card card-primary card-outline direct-chat direct-chat-primary " style="min-width: 350px;">
            <div class="card-header"><img data-card-widget="" src="/iprotek/images/temp-image.png" alt="Message User Image" class="direct-chat-img" style="width: 30px; height: 30px;"> 
            &nbsp;
            <h3 data-card-widget="" class="card-title py-1 ml-1 text-primary">
                Live Chat Support
            </h3> 
            <div class="card-tools"> 
                <button title="Company Details" type="button" @click="$refs.comp_profile.show()" class="btn btn-tool" style="font-size:8px; border:2px solid gray; padding:2px 6px; border-radius:50%;"><i class="fas fa-question"></i></button>
                <button type="button" data-card-widget="collapse" class="btn btn-tool">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" @click="$emit('modal_close')">
                        <i class="fas fa-times"></i></button>
                </div>
            </div> 
            <div class="card-body" style="display: block;">
                <div v-if="chat_info && chat_info.guest_check" style="position:sticky; background-color:#80808021;">
                    <div class="pl-3 py-1">
                        <small>
                            Hi, <code class="text-success" v-text="chat_info.guest_chat_name"></code>!
                        </small>
                        <small title="End Chat Ticket" class="text-danger btn-sm float-right mx-2" style="cursor:pointer;" @click="restChat()"> 
                            <i class="fa fa-times"></i>  END
                        </small>
                        <small title="Verify Email" @click="$refs.guest_modal.show()" v-if="!chat_info.guest_chat_verified" class="text-orange btn-sm float-right mx-2" style="cursor:pointer;" > 
                            <i class="fa fa-envelope"></i> Verify
                        </small>
                        <!--
                        <div v-if="!chat_info.guest_chat_catered_by">
                            <small class="text-secondarry text-nowrap">**Please wait.. while we're waiting for support.</small>
                        </div>
                        -->
                    </div> 
                </div>

                <div :id="'chat-container-'+_uid" class="direct-chat-messages w-100" style="min-height:300px;">
                    <div v-if="!chat_info || chat_info.guest_check == false" >
                        <div>
                            <small>
                                <code class="text-success">**We require your details below allowing us to address your issues.</code>
                            </small>
                        </div>
                        <div>
                            <label class="mb-0 text-sm">Name: <code>* <validation :errors="errors" :field="'name'"/></code>  </label>
                            <input @keyup.enter="key_enter()" v-model="chat_input.name" type="text" class="form-control form-control-sm"/>
                            <label class="mb-0 text-sm">Email: <code>* <validation :errors="errors" :field="'email'"/></code> </label>
                            <input @keyup.enter="key_enter()" v-model="chat_input.email" type="email" class="form-control form-control-sm"/>
                            <label class="mb-0 text-sm">Mobile#:</label>
                            <input @keyup.enter="key_enter()" v-model="chat_input.contact_no" type="text" class="form-control form-control-sm"/>
                            <div class="text-center">
                                <button class="btn btn-sm btn-outline-primary mt-2"  >
                                    <web-submit ref="web_submit" :action="start_chat" :label="'START CHATTING'" ></web-submit>
                                </button>
                            </div>
                        </div>
                    </div> 
                    <guest-chat-messages v-else/>
                </div>
                
            </div> 
            <div v-if="chat_info && chat_info.guest_check" class="card-footer" style="display: block;">
                <div class="input-group">
                    <span v-if="has_upload" class="input-group-text p-2 px-3">
                        <span class="fa fa-paperclip text-lg text-primary" style="cursor: pointer;"></span>
                    </span> 
                    <input id="chat-input-text-17" type="text" name="message" placeholder="Type Message ..." class="form-control"> 
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary ">Send</button>
                    </span>
                </div>
            </div>
        </div>
        <comp-profile ref="comp_profile"></comp-profile>
        <guest-modal ref="guest_modal" @reload_chat_info="$emit('reload_chat_info')"></guest-modal>
        <swal ref="swal_alert"></swal>
    </div>
</template>

<script>
    import CompanyProfileVue from '../../CompanyProfile.vue'
    import WebSubmitVue from '../../common/WebSubmit.vue'
    import ValidationVue from '../../common/Validation.vue'
    import GuestModalVerifyVue from './GuestModalVerify.vue'
    import GuestChatMessagesVue from './GuestChatMessages.vue'

    export default {
        props:[ "chat_info" ],
        watch: {
            chat_info(newValue) {
                //this.input_value = newValue;
                //console.log("updates", newValue);
            },
        },
        components: { 
            "comp-profile":CompanyProfileVue,
            "web-submit":WebSubmitVue,
            "validation":ValidationVue,
            "guest-modal":GuestModalVerifyVue,
            "guest-chat-messages":GuestChatMessagesVue
        },
        data: function () {
            return { 
                has_upload:false,
                has_info:false,
                chat_input:{
                    name:'',
                    email:'',
                    contact_no:''
                },
                errors:[]
            }
        },
        methods: { 

            key_enter:function(){
                this.$refs.web_submit.submit(); 
            },

            start_chat:function(){
                var vm = this;
                var req = vm.chat_input; 
                vm.errors = [];
                return WebRequest2('POST', '/guest-chat/start-chat', JSON.stringify(req) ).then(resp=>{

                    return resp.json().then(data=>{ 
                        if(!resp.ok){
                            vm.errors = data;
                            return data;
                        } 
                        setTimeout(()=>{
                            vm.$emit('reload_chat_info');
                        }, 2500);

                        return data;
                    });
                });
            },
            restChat:function(){
                var vm = this;
                vm.$refs.swal_alert.alert(
                    'question',
                    "Would you like to end this chat?", 
                    "Confirm" , 
                    "POST", 
                    "/guest-chat/clear-chat-info", 
                    '{}'
                ).then(res=>{
                    if(res.isConfirmed){
                        vm.$emit('reload_chat_info');
                    }
                });
                /*
                WebRequest2("POST", "/guest-chat/clear-chat-info").then(resp=>{
                    if( resp.ok ){
                        resp.json().then(data=>{
                            vm.$emit('reload_chat_info');
                        });
                    }
                });*/

            }
        },
        mounted:function(){ 
            //this.loadChatInfo();
            
        },
        updated:function(){

        }
    }
</script>
