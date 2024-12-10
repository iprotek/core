<template>
    <div>
        <div class="card card-primary card-outline direct-chat direct-chat-primary " style="min-width: 300px;">
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
                <div id="chat-container-17" class="direct-chat-messages" style="min-height:300px;">
                    <div>
                        <div>
                            <small>
                                <code>**We require your details below enable us to have conversation.</code>
                            </small>
                        </div>
                        <label class="mb-0 text-sm">Name: <code>* <validation :errors="errors" :field="'name'"/></code>  </label>
                        <input v-model="chat_info.name" type="text" class="form-control form-control-sm"/>
                        <label class="mb-0 text-sm">Email: <code>* <validation :errors="errors" :field="'email'"/></code> </label>
                        <input v-model="chat_info.email" type="email" class="form-control form-control-sm"/>
                        <label class="mb-0 text-sm">Mobile#:</label>
                        <input v-model="chat_info.contact_no" type="text" class="form-control form-control-sm"/>
                        <div class="text-center">
                            <web-submit :action="start_chat" :el_class="'btn btn-sm btn-outline-primary mt-2'" :label="'START CHATTING'" ></web-submit>
                        </div>
                    </div> 
                </div>
            </div> 
            <div v-if="has_info" class="card-footer" style="display: block;">
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
    </div>
</template>

<script>
    import CompanyProfileVue from '../../CompanyProfile.vue'
    import WebSubmitVue from '../../common/WebSubmit.vue'
    import ValidationVue from '../../common/Validation.vue'

    export default {
        props:[  ],
        components: { 
            "comp-profile":CompanyProfileVue,
            "web-submit":WebSubmitVue,
            "validation":ValidationVue
        },
        data: function () {
            return { 
                has_upload:false,
                has_info:false,
                chat_info:{
                    name:'',
                    email:'',
                    contact_no:''
                },
                errors:[]
            }
        },
        methods: { 
            start_chat:function(){
                var vm = this;
                var req = vm.chat_info; 
                vm.errors = [];
                return WebRequest2('POST', '/guest-chat/start-chat', JSON.stringify(req) ).then(resp=>{

                    return resp.json().then(data=>{ 
                        if(!resp.ok){
                            vm.errors = data;
                        }
                        return data;
                    });
                });
            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
