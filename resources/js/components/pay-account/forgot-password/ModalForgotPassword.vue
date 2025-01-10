<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Forgot Password
            </template> 
            <template slot="body" >     
                <user-input2 v-model="email" :type="'text'" :input_style="'height:40px;'" :placeholder="'Email'"></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" onclick="ModalClose();" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" onclick="SendEmail();">
                        <span class="fa fa-paper-plane"></span>
                        Send Email Recovery Link
                    </button>
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
                email:''        
           }
        },
        methods:{ 
            reset:function(){

            },
            dismiss(){
                this.$refs.modal.dismiss();
            },
            show:function(){ 
                this.$refs.modal.show();
            },
            send_email:function(){

                var vm = this;
                //var email_el = document.querySelector('.modal-body input[type=text]');
                //var email = email_el.value;
                var req = {
                    email: vm.email
                }
                //console.log(req);
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Send Now?", 
                    "Confirm" , 
                    "POST", 
                    "/pay-forget-password", 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.email = '';
                            //email_el.value = "";
                        }
                    }
                }); 

            }

        },
        mounted:function(){
            var vm = this;       
            window.ModalClose = this.dismiss;
            window.SendEmail = this.send_email;
            document.querySelector('button.close').setAttribute("onclick","ModalClose()");
        },
        updated:function(){

        }
    }
</script>
