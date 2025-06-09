<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                       <label class="mb-0"> PAID HISTORY </label>
                    </div>
                    <div class="card-body">

                        <page-data-table 
                            ref="page_data_table"
                            v-if="url"
                            :url="url"
                            :is_loading="isLoading"
                            :is_use_top_search="false"
                            :items="paidScheduleTriggerList"
                            :search_placeholder="'Search any keywords...'"
                            :json_filter="filters"

                            @update:items="paidScheduleTriggerList = $event"
                            @update:is_loading="isLoading = $event"
                            
                        >
                        <table class="table custom-red-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> <small class="text-bold">Ref#</small> </th>
                                    <th> <small class="text-bold">DATETIME</small> </th>
                                    <th> <small class="text-bold">AMOUNT</small></th>
                                    <th> <small class="text-bold">PAID</small></th>
                                    <th> <small class="text-bold">BALANCE</small></th> 
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <th class="text-center" colspan="6"> -- LOADING PAID HISTORY -- </th>
                                </tr>
                                <tr v-else-if="paidScheduleTriggerList.length == 0" >
                                    <th class="text-center" colspan="6"> -- NO PAID HISTORY FOUND! -- </th>
                                </tr>
                                <tr v-for="(item,index) in paidScheduleTriggerList" v-bind:key="'item-sms-paid-'+item.id+'-'+index">
                                    <th class="text-center">
                                        <small class="text-bold" v-text="item.id"></small>
                                    </th>
                                    <th >
                                        <small class="text-bold" v-text="item.created_at"></small>
                                    </th>
                                    <th >
                                        <small class="text-bold" v-text="item.due_amount"></small>
                                    </th>
                                    <th >
                                        <small class="text-bold" v-text="item.paid_amount"></small>
                                    </th>
                                    <th >
                                        <small class="text-bold" v-text="item.balance_amount"></small>
                                    </th>
                                    <td>
                                        <button v-if="item.note" class="btn btn-outline-warning btn-sm" :title="'Note: '+item.note">
                                            <span class="fa fa-sticky-note"></span>
                                        </button> 
                                        <button class="btn btn-outline-primary" @click="resendSmsPayment(item.id)" >
                                            <span class="fa fa-sms"></span> Resend SMS
                                        </button>  
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </page-data-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageDataTableVue from '../../../../common/PageDataTable.vue';

    export default {
        props:[ "swal_prompt", "group_id", "branch_id", "type", "sys_notify_schedule_sms_triggers_id" ],
        $emits:[],
        watch: { 
            sys_notify_schedule_sms_triggers_id:function(val){

            }
        },
        components: { 
            "page-data-table":PageDataTableVue
        },
        data: function () {
            return {    
                type:'sms',
                url:'/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/get/'+this.sys_notify_schedule_sms_triggers_id+'/paid/list',
                filters:{
                    branch_id: this.branch_id,
                    type : this.type
                },
                isLoading:false,
                paidScheduleTriggerList:[],
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            resendSmsPayment:function(payment_id){
                var request = {
                    sys_notify_paid_schedule_trigger_id: payment_id
                }
                this.swal_prompt.alert(
                    'question',
                    "Resend SMS Payment Notif?", 
                    "Confirm" , 
                    "POST", 
                    '/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/get/'+this.sys_notify_schedule_sms_triggers_id+'/paid/resend-sms-payment', 
                    JSON.stringify(request)
                ).then(res=>{
                    //console.log(vm.errors);
                    if(res.isConfirmed && res.value.status == 1){
                        //vm.$emit('data_updated');
                        //vm.loadScheduleTrigger(vm.pay_info.sys_notify_schedule_sms_triggers_id);
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
