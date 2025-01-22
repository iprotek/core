<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Change Password
            </template> 
            <template slot="body" >     
                <div class="text-center py-2 mt-2">
                    <button class="btn btn-outline-primary" @click="send_email()"> SEND EMAIL FOR CHANGE PASSWORD </button>
                </div>
                <!--
                <user-input2 :input_style="'height:40px;'" v-model="current_pass" :placeholder="'Current Password'" />
                <user-input2 :input_style="'height:40px;'" v-model="new_pass" :placeholder="'New Password'" />
                <user-input2 :input_style="'height:40px;'" v-model="verify_pass" :placeholder="'Verify New Password'" />
                -->
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <!--<button type="button" class="btn btn-outline-primary"  >UPDATE NOW</button> -->
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../common/UserInput2.vue';
    export default {
        props:[ "group_id", "user_admin_id", "email" ],
        components: {
            "user-input2":UserInput2Vue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                current_pass:'',
                new_pass:'',
                verify_pass:''
           }
        },
        methods:{ 
            reset:function(){
                this.current_pass = '';
                this.new_pass = '';
                this.verify_pass = '';
            },
            show:function(){ 
                var vm = this;
                vm.reset();

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            update_pass:function(){
                var vm = this;
                /*
                    this.$refs.swal_prompt.alert(
                        'question',
                        "Add Event", 
                        "Confirm" , 
                        "POST", 
                        "/manage/dashboard/resort-events/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            vm.$emit('data_updated');
                        }
                    });
                */
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
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
                            //vm.email = '';
                            //email_el.value = "";
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
