<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label class="mb-0">Scheduler List</label>
                    </div>
                    <div class="card-body">
                        
                        <a href="/manage" class="btn btn-outline-primary btn-sm mb-2 mr-2">
                            <span class="fa fa-arrow-left"></span> DASHBOARD
                        </a>
                        <button class="btn btn-outline-primary btn-sm mb-2" @click="add_click()">
                            <span class="fa fa-plus"></span> ADD SCHEDULER
                        </button> 
                        <page-data-table
                            ref="page_data_table"
                            v-if="url"
                            :url="url"
                            :is_loading="isLoading"
                            :is_use_top_search="true"
                            :items="scheduleList"
                            :search_placeholder="'Search Scheduler'"
                            :json_filter="filters"

                            @update:items="scheduleList = $event"
                            @update:is_loading="isLoading = $event"
                            
                        >
                            <table class="w-100 custom-red-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:100px;">Ref#</th>
                                        <th class="text-center">Type</th>
                                        <th>Name</th>
                                        <th>Triggers</th>
                                        <th>IsActive</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td class="text-center text-danger" colspan="6"> -- LOADING SCHEDULES -- </td>
                                    </tr>
                                    <tr v-else-if="scheduleList.length == 0">
                                        <td class="text-center text-danger" colspan="6"> -- NO  SCHEDULER/S FOUND -- </td>
                                    </tr>
                                    <tr v-for="(item,itemIndex) in scheduleList" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                                        <th class="text-center p-1" v-text="item.id"></th>
                                        <th class="p-1 text-center" style="width:100px;" v-text="item.type"> </th>
                                        <th class="p-1" >
                                            <label v-text="item.name" class="mb-0"></label>
                                        </th>
                                        <th class="text-nowrap p-1" style="width:220px;" >
                                            <div v-if="item.type == 'sms'">
                                                <a :href="'/manage/sys-notification/scheduler/triggers/'+item.type+'/'+item.id" class="btn btn-success btn-sm ml-1" >
                                                    <span class="fa fa-list"></span>
                                                </a>
                                                <small class="text-success">{{item.sms_schedule_active_count}}-Actives</small> | <small class="text-danger">{{item.sms_schedule_inactive_count}}-Inactives</small>
                                            </div>
                                            <div v-else>
                                                <code> SETTING NOT YET AVAILABLE </code>
                                            </div>
                                        </th>
                                        <th class="p-1" style="width:120px;">
                                            <label v-if="item.is_active" class="text-success"> ACTIVE </label>
                                            <label v-else class="text-danger"> INACTIVE </label>
                                        </th>
                                        <th class="text-nowrap p-1" style="width:120px;" >
                                            <button class="btn btn-warning btn-sm ml-1" @click="$refs.modal_sys_notif.show(item.id)">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                            <button @click="remove(item.id)" class="btn btn-danger btn-sm ml-1">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>

                        </page-data-table>
                    </div>
                </div>
            </div>
        </div>
        <modal-sys-notification-scheduler :group_id="group_id" :branch_id="branch_id" ref="modal_sys_notif" @data_updated="$refs.page_data_table.reloadPage()" />
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script> 
    import PageDataTable from '../../../common/PageDataTable.vue';
    import ModalSysNotificationScheduler from './ModalSysNotificationScheduler.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        components: { 
            "page-data-table":PageDataTable,
            "modal-sys-notification-scheduler":ModalSysNotificationScheduler
        },
        watch: { 
        },
        data: function () {
            return {
                url:'/api/group/'+this.group_id+'/sys-notification/schedulers/list',
                filters:{
                    branch_id: this.branch_id
                },
                isLoading:false,
                scheduleList:[]
            }
        },
        methods: { 
            add_click:function(){
                this.$refs.modal_sys_notif.show();
            },
            remove:function(id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question',
                    "REMOVE Scheduler?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/sys-notification/schedulers/list/"+id
                ).then(res=>{
                    if(res.isConfirmed && res.value.status == 1){
                        vm.$refs.page_data_table.reloadPage();
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
