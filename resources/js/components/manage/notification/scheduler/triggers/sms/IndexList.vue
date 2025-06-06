<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        SMS SCHEDULE TRIGGER LIST
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary btn-lg mb-2" @click="$refs.modal_sms_notif_sched.show()">
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
                                        <th class="text-center">Schedule Time</th> 
                                        <th class="text-center">Repeat</th> 
                                        <th class="text-center">Status Info</th> 
                                        <th class="text-center text-nowrap" style="width:100px;">Target Count</th> 
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td class="text-center text-danger" colspan="11"> -- LOADING TRIGGERS -- </td>
                                    </tr>
                                    <tr v-else-if="smsTriggerList.length == 0">
                                        <td class="text-center text-danger" colspan="11"> -- NO  SMS TRIGGER FOUND -- </td>
                                    </tr>
                                    <tr v-for="(item,itemIndex) in smsTriggerList" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                                        <th class="text-center p-1" v-text="item.id"></th>
                                        <th class="text-center p-1" v-text="item.name"></th>
                                        <th class="text-center p-1 text-nowrap" >
                                            {{ item.notification_type }} 
                                            <button v-if="item.notification_type == 'payment'" class="btn btn-sm btn-outline-primary">
                                                PAY
                                            </button>
                                        </th>
                                        <th class="text-center p-1">
                                            <label v-if="item.is_active" class="text-primary pb-0"> YES</label>
                                            <label v-else class="text-danger pb-0"> NO </label>
                                        </th>
                                        <td v-text="item.status"> </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <span class="fa fa-list"></span>
                                            </button>
                                        </td>
                                        <td>
                                            <small >
                                                <b v-text="item.datetime_schedule"></b>
                                                <code v-text="item.repeat_days_after"></code>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <b  v-text="item.repeat_type"></b>
                                            <span v-if="item.repeat_info">

                                                <span v-if="item.repeat_type == 'yearly'"> in {{ item.repeat_info.month_name }} </span>
                                                <span v-if="item.repeat_type == 'yearly' || item.repeat_type == 'monthly'"> on {{ item.repeat_info.month_day }} </span>
                                                <span v-if="item.repeat_type == 'weekly'"> every {{ item.repeat_info.week_day }} </span>
                                                <span v-if="item.repeat_type == 'datetime'"> at {{ item.repeat_info.datetime }} </span>
                                                <span v-else> at {{ item.repeat_info.time }}</span>
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <small  v-text="item.error_message"></small>
                                        </td>
                                        <td class="text-nowrap">
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
                </div>
            </div>
        </div>
        <modal-sms-notif-sched ref="modal_sms_notif_sched" :branch_id="branch_id" :group_id="group_id" :scheduler_id="scheduler_id" />
    </div>
</template>

<script>
    import PageDataTableVue from '../../../../../common/PageDataTable.vue';
    import ModalSmsNotificationScheduler from './ModalSmsNotificationScheduler.vue';
    export default {
        props:[ "group_id", "branch_id", "scheduler_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "page-data-table":PageDataTableVue,
            "modal-sms-notif-sched":ModalSmsNotificationScheduler
        },
        data: function () {
            return {    
                
                url:'/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/list',
                filters:{
                    branch_id: this.branch_id,
                    scheduler_id : this.scheduler_id
                },
                isLoading:false,
                smsTriggerList:[]
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
