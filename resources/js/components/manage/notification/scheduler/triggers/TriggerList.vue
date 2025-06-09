<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" v-text="title"></div>
                    <div class="card-body">
                        
                        <page-data-table
                            ref="page_data_table"
                            v-if="url"
                            :url="url"
                            :is_loading="isLoading"
                            :is_use_top_search="false"
                            :items="smsTriggerList"
                            :search_placeholder="'Search '"
                            :json_filter="filters"

                            @update:items="smsTriggerList = $event"
                            @update:is_loading="isLoading = $event"
                            
                        >
                        <table class="custom-red-table">
                            <thead>
                                <tr>
                                    <th style="width:80px;" class="text-center">REF#</th>
                                    <th>MOBILE#</th>
                                    <th>MESSAGE</th>
                                    <th>STATUS</th>
                                    <th>SUBMITTED AT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <td colspan="5" class="text-center"> -- LOADING TRIGGERS -- </td>
                                </tr>
                                <tr v-else-if="smsTriggerList.length == 0">
                                    <td colspan="5" class="text-center"> -- NO TRIGGER/S Found! -- </td>
                                </tr>
                                <tr v-for="(trigItem,trigIndex) in smsTriggerList" v-bind:key="'trig-'+trigItem.id+'-'+trigIndex">
                                    <td class="text-center" v-text="trigItem.id"></td>
                                    <td v-text="trigItem.to_number"></td>
                                    <td v-text="trigItem.message"></td>
                                    <td v-text="trigItem.status_info"></td>
                                    <td v-text="trigItem.created_at"></td>
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

    import PageDataTable from '../../../../common/PageDataTable.vue';

    export default {
        props:[ "title", "group_id", "branch_id", "type", "target_id" ],
        $emits:[],
        watch: { 
            target_id:function(val){
                this.url = '/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/get/'+val+'/trigger-list';
            },
            type:function(val){
                this.filters = {
                    branch_id: this.branch_id,
                    type: val
                }
            }
        },
        components: {
            "page-data-table":PageDataTable
        },
        data: function () {
            return { 
                url:'/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/get/'+this.target_id+'/trigger-list',
                filters:{
                    branch_id: this.branch_id,
                    type : this.type
                },
                isLoading:false,
                smsTriggerList:[]
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
