<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                ADD SMS SCHEDULE
            </template> 
            <template slot="body" >     
                <input2 v-model="sms_nofify_sched.name" :type="'text'" :placeholder="'SMS Name'" :input_style="'height:35px;'" />
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../../../../common/UserInput2.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: {
            "input2":UserInput2Vue
        },
        data: function () {
            return {        
                promiseExec:null,
                sms_nofify_sched:{
                    name:'',
                    sms_client_api_request_link_id:0,
                    sys_notify_schedule_id:0,
                    name:'',
                    notification_type:'',
                    to_type:'', //customers,users
                    selected_items:[],
                    mobile_nos:[],
                    total_due:0,
                    total_paid:0,
                    is_active:false,
                    is_stop_when_fully_paid:0,
                    error_message:'',
                    repeat_days_after:0,
                    others_settings:{}
                }

           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(id = 0){ 
                var vm = this;

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            add:function(){
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
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
