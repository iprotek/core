<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Send Invitation
            </template> 
            <template slot="body" >     
                <div>
                    <user-input2 v-model="email" :input_style="'height:40px;'" :placeholder="'Email'" :placeholder_description="'Email of the invetee.'"></user-input2>
                    <user-input2 v-model="role" :input_style="'height:40px;'" :placeholder="'Role'" :placeholder_description="'Role of the invitee.'"></user-input2>
                    <div class="card p-0 my-2">
                        <div class="card-header">
                            Your current <i>pay</i> account password.
                        </div>
                        <div class="card-body pt-0">
                            <user-input2 v-model="password" :type="'password'" :input_style="'height:40px;'" :placeholder="'Password'" ></user-input2>
                            <user-input2 v-model="password_confirmation" :type="'password'" :input_style="'height:40px;'" :placeholder="'Verify Password'" ></user-input2>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary mr-4" @click="send_invitation">Send Invitation</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {   
            "user-input2":UserInput2Vue
        },
        data: function () {
            return { 
                email:'',
                role:'',
                password:'',
                password_confirmation:""
           }
        },
        methods:{ 
            reset:function(){
                this.email = '';
                this.role = '';
                this.password = '';
                this.password_confirmation = '';
            },
            show:function(){ 
                this.$refs.modal.show();
            },
            reset:function(){

            },
            send_invitation:function(){
                var vm = this;
                var req = {
                    email: this.email,
                    role: this.role,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                }
                //console.log(req);
                //return;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Confirm invitation?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/shared-accounts/send-invitation", 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.reset();
                            //vm.billing_account_id = res.value.data.id;
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
