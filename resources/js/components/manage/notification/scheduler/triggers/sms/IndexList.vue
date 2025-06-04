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
                                        <th class="text-center" style="width:100px;">Is Active</th> 
                                        <th class="text-center" style="width:100px;">Status</th> 
                                        <th class="text-center" style="width:150px;">Triggers</th> 
                                        <th class="text-center">Status Info</th> 
                                        <th class="text-center text-nowrap" style="width:100px;">Target Count</th> 
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td class="text-center text-danger" colspan="8"> -- LOADING TRIGGERS -- </td>
                                    </tr>
                                    <tr v-else-if="smsTriggerList.length == 0">
                                        <td class="text-center text-danger" colspan="8"> -- NO  SMS TRIGGER FOUND -- </td>
                                    </tr>
                                    <tr v-for="(item,itemIndex) in smsTriggerList" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                                        <th class="text-center p-1" v-text="item.id"></th>
                                        <th class="text-center p-1" v-text="item.name"></th>
                                        <th class="text-center p-1">
                                            <label v-if="item.is_active" class="text-primary pb-0"> YES</label>
                                            <label v-else class="text-danger pb-0"> NO </label>
                                        </th>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <span class="fa fa-list"></span>
                                            </button>
                                        </td>
                                        <td></td>
                                        <td>
                                            <label> No of targets </label>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm">
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
        <modal-sms-notif-sched ref="modal_sms_notif_sched" :branch_id="branch_id" :group_id="group_id" />
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
                    branch_id: this.branch_id
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
