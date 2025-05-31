<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label class="mb-0">Scheduler List</label>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary btn-sm mb-2" @click="add_click()">
                            <span class="fa fa-plus"></span> ADD SCHEDULER
                        </button>
                        <page-search-container 
                            
                            :searchText="search_text" 
                            :currentPage="current_page"
                            :isLoading="isLoading"
                            :itemList="scheduleList"
                            :search_placeholder="'Search schedule'" 
                            :is_use_top_search="true"

                            @update:currentPage="current_page = $event"
                            @update:searchText="search_text = $event"
                            @update:isLoading="isLoading = $event"
                            @update:itemList="scheduleList = $event"
                            
                            :fn_plus_click="add_click"
                            :fn_web_request2="loadingScheduleList"
                            
                            >
                            <table class="w-100 custom-red-table">
                                <thead>
                                    <tr>
                                        <th style="width:100px;">Ref#</th>
                                        <th>Name</th>
                                        <th>Type</th>
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
                                        <th class="p-1" >
                                            <label v-text="item.name" class="mb-0"></label>
                                        </th>
                                        <th class="p-1" style="width:200px;" v-text="item.type"> </th>
                                        <th class="text-nowrap p-1" style="width:250px;" >
                                            <button class="btn btn-success btn-sm ml-1">
                                                <span class="fa fa-list"></span>
                                            </button>
                                            <small class="text-success">0-Actives</small> | <small class="text-danger">0-Inactives</small>
                                        </th>
                                        <th class="p-1" style="width:120px;">
                                            <label v-if="item.is_active" class="text-success"> ACTIVE </label>
                                            <label v-else class="text-danger"> INACTIVE </label>
                                        </th>
                                        <th class="text-nowrap p-1" style="width:120px;" >
                                            <button class="btn btn-warning btn-sm ml-1">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                            <button class="btn btn-danger btn-sm ml-1">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </page-search-container>
                    </div>
                </div>
            </div>
        </div>
        <modal-sys-notification-scheduler :group_id="group_id" :branch_id="branch_id" ref="modal_sys_notif" @data_updated="loadingScheduleList()" />
    </div>
</template>

<script>
    import PageSearchContainerVue from '../../../common/PageSearchContainer.vue';
    import ModalSysNotificationScheduler from './ModalSysNotificationScheduler.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        components: {
            "page-search-container": PageSearchContainerVue,
            "modal-sys-notification-scheduler":ModalSysNotificationScheduler
        },
        watch: { 
        },
        data: function () {
            return {
                isLoading:false,
                scheduleList:[],
                current_page:1,
                search_text:''
            }
        },
        methods: { 
            add_click:function(){
                this.$refs.modal_sys_notif.show();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadingScheduleList:function(){
                var vm = this;
                return WebRequest2('GET', '/api/group/'+this.group_id+'/sys-notification/schedulers/list?'+this.queryString({
                    page: this.current_page,
                    search_text: this.search_text,
                    branch_id: this.branch_id,
                    items_per_page:10
                })).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    });
                });
            }
        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
