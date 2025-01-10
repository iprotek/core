<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :extended_width="true">
            <template slot="header" >
                SMTP Setup
            </template> 
            <template slot="body" >     
                <div>
                    <div class="row">
                        <div class="col-sm-12 mt-4">
                            <bootstrap-switch v-model="is_active" ></bootstrap-switch> <label> Active </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <user-input2 v-model="sender_name" :placeholder="'Sender Name'" :input_style="'height:35px;'" :placeholder_description="'Sender Name which is used also in reply to.'"></user-input2>
                        </div>
                        <div class="col-sm-6"> 
                            <user-input2 v-model="reply_to" :placeholder="'Reply To (Email)'" :input_style="'height:35px;'" :placeholder_description="'Email used which recepient will reply.'"></user-input2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <user-input2 v-model="transport" :placeholder="'Transport(Smtp)'" :input_style="'height:35px;'" :placeholder_description="'Transport thru: smtp or any.'"></user-input2>
                        </div>
                        <div class="col-sm-6">
                            <user-input2 v-model="host" :placeholder="'Host'" :input_style="'height:35px;'" :placeholder_description="'The email server where its hosted.'"></user-input2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <user-input2 v-model="port" :placeholder="'Port'" :input_style="'height:35px;'" :placeholder_description="'The email server port.'"></user-input2>
                        </div>
                        <div class="col-sm-6">
                            <user-input2 v-model="encryption" :placeholder="'Encryption'" :input_style="'height:35px;'" :placeholder_description="'The email server protocol encryption: tls, ssl.'"></user-input2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <user-input2 v-model="username" :placeholder="'Username(email)'" :input_style="'height:35px;'" :placeholder_description="'The email account name.'"></user-input2>
                        </div>
                        <div class="col-sm-6" v-if="id">
                            <user-input2 :type="'password'" v-model="password" :placeholder="'Password'" :input_style="'height:35px;'" :placeholder_description="'( set Empty for unchanged password. )'"></user-input2>
                        </div>
                        <div class="col-sm-6" v-else>
                            <user-input2 :type="'password'" v-model="password" :placeholder="'Password'" :input_style="'height:35px;'" :placeholder_description="'The email account password.'"></user-input2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"> 
                            <user-input2 v-model="priority" :placeholder="'Priority No.'" :input_style="'height:35px;'" :placeholder_description="'The priority number'"></user-input2>
                        </div>
                        <div class="col-sm-9"> 
                            <user-input2 v-model="target_name" :placeholder="'Target Name'" :input_style="'height:35px;'" :placeholder_description="'The target name where email is triggered. mail-messaging, account-recovery, etc.'"></user-input2>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-header">
                            Email Sending Conditions
                        </div>
                        <div class="card-body">
                            <div>
                                <code>*Emails - are only sent from Monday to Friday</code>
                            </div>
                            <div>
                                <code>*Email allowed per setup to sent random from 80 to 120 emails per day. The more the email you have the more your can send.</code>
                            </div>
                            <div>
                                <code>*Email sending randomly starting time from 8AM to 10AM with 5 - 15 minutes each mail interval.</code>
                            </div>
                            <div>
                                <code>*Email sending are randomly ended from 4PM to 10PM.</code>
                            </div>
                            <div>
                                <code>*Email are trigger when set active.</code>
                            </div>
                            <div>
                                <label>These are the conditions needed to avoid blacklisted as spammer.</label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button v-if="id > 0" type="button" class="btn btn-outline-secondary" @click="set_new">
                        New
                    </button> 
                    <button v-if="id == 0" type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Add SMTP
                    </button> 
                    <button v-else type="button" class="btn btn-outline-warning" @click="save">
                        <span class="fa fa-edit"></span> Save SMTP
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script> 
    import UserInput2Vue from '../../../common/UserInput2.vue';
    import BoostrapSwitch2Vue from '../../../common/BoostrapSwitch2.vue';
    export default {
        props:[ "group_id" ],
        components: { 
            "user-input2":UserInput2Vue,
            "bootstrap-switch":BoostrapSwitch2Vue
        },
        data: function () {
            return {
                id:0,
                sender_name:'',
                reply_to:'',
                transport:'smtp',
                host:'',
                port:'',
                encryption:'',
                username:'',
                password:'',
                is_active:false,
                target_name:'',
                priority:10
           }
        },
        methods:{ 
            reset:function(){
                this.id = 0;
                this.sender_name = '';
                this.reply_to = '';
                this.transport = 'smtp';
                this.host = '';
                this.port = '';
                this.encryption = '';
                this.username = '';
                this.password = '';
                this.is_active = false;
                this.target_name = '';
            },
            set_new:function(){
                this.reset();
            },
            show:function(id=0){ 
                this.reset();
                this.id = id;
                this.$refs.modal.show();
                var vm = this;
                //GET HERE
                if(!id) return;
                WebRequest2('GET',"/api/group/"+this.group_id+"/settings/smtp-connection/get/"+id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.sender_name = data.from_name;
                        vm.reply_to = data.reply_to;
                        vm.transport = data.transport;
                        vm.host = data.host;
                        vm.port = data.port;
                        vm.encryption = data.encryption;
                        vm.username = data.username;
                        vm.is_active = data.is_active == 1;
                        vm.target_name = data.target_name;
                    })
                });
            },
            remove:function(id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to remove this smtp?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/settings/smtp-connection/delete/"+id
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){  
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            },
            save:function(){
                var vm = this;
                var req = {
                    id: this.id,
                    sender_name: this.sender_name,
                    reply_to: this.reply_to,
                    transport: this.transport,
                    host: this.host,
                    port: this.port,
                    encryption: this.encryption,
                    username: this.username,
                    password: this.password,
                    is_active: this.is_active ? 1:0,
                    priority: this.priority,
                    target_name: this.target_name
                };
                //console.log(req)
                //return;
                var title = "Would you like to add smtp?";
                var action = "add";
                if(this.id > 0){
                    title = "Would you like to update this smtp?";
                    action = "update";
                }

                this.$refs.swal_prompt.alert(
                    'question', 
                    title, 
                    "Confirm" , 
                    this.id == 0 ? "POST":"PUT", 
                    "/api/group/"+this.group_id+"/settings/smtp-connection/"+action,
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            vm.id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
