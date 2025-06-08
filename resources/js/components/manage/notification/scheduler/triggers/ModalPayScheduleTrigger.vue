<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="90">
            <template slot="header" >
                PAYMENT
            </template> 
            <template slot="body" >     
                <div class="row" v-if="pay_info.sys_notify_schedule_sms_triggers_id">
                    <div class="col-sm-7 pt-4">
                        <paid-schedule-trigger :branch_id="branch_id" :group_id="group_id" :sys_notify_schedule_sms_triggers_id="pay_info.sys_notify_schedule_sms_triggers_id" :type="pay_info.type" />
                        <button class="btn btn-outline-primary btn-sm ml-2" v-if="!is_show_sent" @click="is_show_sent = true">
                            <span class="fa fa-sms"></span> SHOW NOTIFIED SMS
                        </button>
                        <button class="btn btn-outline-danger btn-sm ml-2" v-else @click="is_show_sent = false">
                            <span class="fa fa-sms"></span> HIDE NOTIFIED SMS
                        </button>
                        <paid-sms-schedule-trigger :branch_id="branch_id" :group_id="group_id" :sys_notify_schedule_sms_triggers_id="pay_info.sys_notify_schedule_sms_triggers_id" v-if="is_show_sent" :type="pay_info.type" />
                    </div>
                    <div class="col-sm-5 pt-4">
                        <div class="card p-0 card-primary">
                            <div class="card-header">
                                <label class="mb-0">PAY</label>
                            </div>
                            <div class="card-body pt-0">
                                <input2 :value="total_due" :readonly="true" :placeholder="'Total Due'" :input_style="'height:35px;'"/>
                                <input2 :value="total_paid" :readonly="true" :placeholder="'Total Paid'" :input_style="'height:35px;'"/>
                                <input2 :value="total_balance" :readonly="true" :placeholder="'Total Balance'" :input_style="'height:35px;'"/>
                                <input2 v-model="pay_info.paid_amount" :placeholder="'Pay Amount:'" :input_style="'height:35px;'"/>
                                <button class="btn btn-outline-primary my-2"> PAY NOW!</button>
                                <div>
                                    <switch2 v-model="pay_info.is_notify_sms" /> IS SMS NOTIFY
                                </div>
                                <div v-if="pay_info.is_notify_sms">
                                    <label>Template for SMS:</label>
                                    <textarea class="form-control text-sm" v-model="pay_info.message_template" style="min-height: 110px;"></textarea>
                                <small>                            
                                    <label class="mb-0">DYNAMIC VARIABLES</label>
                                    <div><code>[person_name]</code> - the name of the recipient</div>
                                    <div><code>[total_due]</code> - automatically set based on total due</div>
                                    <div><code>[total_paid]</code> - automatically adjust based on total paid</div>
                                    <div><code>[total_balance]</code> - automatically set from total due deduced by total paid</div>
                                    <div><code>[paid_amount]</code> - the amount you currently paid.</div>
                                    <div><code>[ref_no]</code> - is paid reference number.</div>
                                </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    import UserInput2 from '../../../../common/UserInput2.vue';
    import BoostrapSwitch2 from '../../../../common/BoostrapSwitch2.vue';
    import PaidScheduleTriggerList from './PaidScheduleTriggerList.vue';
    import PaidSmsScheduleTriggerList from './PaidSmsScheduleTriggerList.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: {
        },
        components: {   
            "input2":UserInput2,
            "switch2":BoostrapSwitch2,
            "paid-schedule-trigger":PaidScheduleTriggerList,
            "paid-sms-schedule-trigger":PaidSmsScheduleTriggerList
        },
        data: function () {
            return {        
                promiseExec:null,
                total_due:0,
                total_paid:0,
                is_show_sent:false,
                total_balance:0,
                pay_info:{
                    sys_notify_schedule_sms_triggers_id:0,
                    //due_amount:0,
                    note:'',
                    type:'sms',
                    paid_amount: 0,
                    message_template:'Hi [person_name],\r\n We received your payment amount of [paid_amount] with ref#: [ref_no].',
                    is_notify_sms:true
                }
           }
        },
        methods:{ 
            reset:function(){
                this.pay_info = {
                    sys_notify_schedule_sms_triggers_id:0,
                    note:'',
                    type:'sms',
                    paid_amount:0,
                    message_template:'Hi [person_name],\r\n We received your payment amount of [paid_amount] with ref#: [ref_no].',
                    is_notify_sms:true
                }
                //this.pay_info.sys_notify_schedule_sms_triggers_id = 0;
            },
            show:function(id){ 
                var vm = this;
                vm.reset();

                setTimeout(()=>{

                    vm.pay_info.sys_notify_schedule_sms_triggers_id = id;

                    vm.$refs.modal.show();

                    return new Promise((promiseExec)=>{
                        vm.promiseExec = promiseExec;
                    });
                    
                }, 50);
                
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
