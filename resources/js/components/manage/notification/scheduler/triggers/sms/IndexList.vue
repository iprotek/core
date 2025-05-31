<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        SMS SCHEDULE TRIGGER LIST
                    </div>
                    <div class="card-body">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td class="text-center text-danger" colspan="6"> -- LOADING TRIGGERS -- </td>
                                    </tr>
                                    <tr v-else-if="smsTriggerList.length == 0">
                                        <td class="text-center text-danger" colspan="6"> -- NO  SMS TRIGGER FOUND -- </td>
                                    </tr>
                                    <tr v-for="(item,itemIndex) in smsTriggerList" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                                        <th class="text-center p-1" v-text="item.id"></th>
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
    import PageDataTableVue from '../../../../../common/PageDataTable.vue';
    export default {
        props:[ "group_id", "branch_id", "scheduler_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "page-data-table":PageDataTableVue
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
