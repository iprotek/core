<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        SMS SCHEDULE TRIGGER LIST
                    </div>
                    <div class="card-body">
                        <div v-if="target_id == 0">
                            <a href="/manage/sys-notification/scheduler" class="btn btn-outline-primary btn-sm mb-2 mr-2">
                                <span class="fa fa-arrow-left"></span> BACK TO SCHEDULERS
                            </a>
                            <button class="btn btn-outline-primary btn-sm mb-2" @click="$refs.modal_sms_notif_sched.show()">
                                <span class="fa fa-plus"></span> ADD SCHEDULE
                            </button>
                            <page-data-table 
                                ref="page_data_table"
                                v-if="url"
                                :url="url"
                                :is_loading="isLoading"
                                :is_use_top_search="true"
                                :items="smsTriggerList"
                                :search_placeholder="'Search Scheduler'"
                                :json_filter="filters"

                                @update:items="smsTriggerList = $event"
                                @update:is_loading="isLoading = $event"
                                
                            >
                                <table class="w-100 custom-red-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:100px;">Ref#</th> 
                                            <th >Name</th> 
                                            <th class="text-center" style="width:80px;">Type</th> 
                                            <th class="text-center" style="width:100px;">Is Active</th> 
                                            <th class="text-center" style="width:100px;">Status</th> 
                                            <th class="text-center" style="width:80px;">Triggers</th>  
                                            <th class="text-center" style="width: 100px;">Repeat</th> 
                                            <th class="text-center" style="width: 100px;">Status Info</th> 
                                            <th class="text-center text-nowrap" style="width:100px;">Target Count</th> 
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="isLoading">
                                            <td class="text-center text-danger" colspan="10"> -- LOADING TRIGGERS -- </td>
                                        </tr>
                                        <tr v-else-if="smsTriggerList.length == 0">
                                            <td class="text-center text-danger" colspan="10"> -- NO  SMS TRIGGER FOUND -- </td>
                                        </tr>
                                        <tr v-for="(item,itemIndex) in smsTriggerList" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                                            <th class="text-center p-1" v-text="item.id"></th>
                                            <th class="text-left p-1" v-text="item.name"></th>
                                            <th class="text-center p-1 text-nowrap" >
                                                <small>
                                                    <b> {{ item.notification_type }} </b>
                                                    <span class="text-nowrap" v-if="item.notification_type == 'payment'">
                                                        <button @click="$refs.modal_pay_schedule_trigger.show(item.id)" class="btn btn-outline-primary btn-sm">
                                                            PAY
                                                        </button>
                                                        BAL: <code> {{ item.total_due - item.total_paid }}</code> 
                                                    </span>
                                                </small>
                                            </th>
                                            <th class="text-center p-1">
                                                <label v-if="item.is_active" class="text-primary pb-0"> YES</label>
                                                <label v-else class="text-danger pb-0"> NO </label>
                                            </th>
                                            <td class="text-center"> 
                                                <small  v-text="item.status"></small>
                                            </td>
                                            <td>
                                                <button @click="target_id = item.id" class="btn btn-outline-primary btn-sm">
                                                    <span class="fa fa-list"></span>
                                                </button> {{ item.sms_trigger_count }}
                                            </td>
                                            <td class="text-left text-nowrap">
                                                <small>
                                                    <code :title="'No of days the notification will repeat after trigger.'"> [ {{ item.repeat_days_after }} ]</code>
                                                    <b v-if="item.repeat_type != 'datetime'" v-text="item.repeat_type"></b>
                                                    <code v-else><b>ONCE</b></code> :
                                                    <span v-if="item.repeat_info">

                                                        <span v-if="item.repeat_type == 'yearly'"> in {{ item.repeat_info.month_name }} </span>
                                                        <span v-if="item.repeat_type == 'yearly' || item.repeat_type == 'monthly'"> on {{ item.repeat_info.month_day }} </span>
                                                        <span v-if="item.repeat_type == 'weekly'"> every {{ item.repeat_info.week_day }} </span>
                                                        <span v-if="item.repeat_type == 'datetime'"> at {{ item.repeat_info.datetime }} </span>
                                                        <span v-else> at {{ item.repeat_info.time }}</span>
                                                    </span>
                                                </small>
                                                <div>
                                                    <small >
                                                        TRIGGER SCHED: <b v-text="item.datetime_schedule"></b>
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">
                                                <small  v-text="item.error_message"></small>
                                            </td>
                                            <td class="text-center">
                                                <label v-text="item.selected_items.length"></label>
                                            </td>
                                            <td style="width:80px;">
                                                <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_sms_notif_sched.show(item.id)">
                                                    <span class="fa fa-edit"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </page-data-table>
                        </div>
                        <div v-else>
                            <button class="btn btn-outline-primary btn-sm my-2" @click="target_id = 0">VIEW SCHEDULE TRIGGER</button>
                            <trigger-list :title="'SMS Due Schedule Trigger Notification Sent'" :group_id="group_id" :branch_id="branch_id" :target_id="target_id" :type="type"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-sms-notif-sched ref="modal_sms_notif_sched" :branch_id="branch_id" :group_id="group_id" :scheduler_id="scheduler_id" />
        <modal-pay-schedule-trigger :branch_id="branch_id" :group_id="group_id" ref="modal_pay_schedule_trigger" />
    </div>
</template>

<script>
    import PageDataTableVue from '../../../../../common/PageDataTable.vue';
    import ModalSmsNotificationScheduler from './ModalSmsNotificationScheduler.vue';
    import TriggerList from '../TriggerList.vue';
    import ModalPayScheduelTriggerVue from '../ModalPayScheduleTrigger.vue';

    export default {
        props:[ "group_id", "branch_id", "scheduler_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "page-data-table" : PageDataTableVue,
            "modal-sms-notif-sched" : ModalSmsNotificationScheduler,
            "trigger-list" : TriggerList,
            "modal-pay-schedule-trigger":ModalPayScheduelTriggerVue
        },
        data: function () {
            return {    
                target_id:0,
                type:'sms',
                url:'/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/list',
                filters:{
                    branch_id: this.branch_id,
                    scheduler_id : this.scheduler_id
                },
                isLoading:false,
                smsTriggerList:[],
                
            }
        },
        methods: {

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
