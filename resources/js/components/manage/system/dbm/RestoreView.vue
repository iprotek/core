<template>
    <div>
        <div>
            <button @click="restoreClick()" class="btn btn-outline-info btn-sm">
                RESTORE FROM BACKUP
            </button>
            <input :id="sql_file" type="file" accept=".sql">
            <div>
                <small>
                    <code>**Please backup first before restoring</code>
                </small>
            </div>
        </div>
        
        <div class="mt-1"> 
            <page-datatable 
                ref="restore_page"
                :is_use_top_search="false" 
                :is_loading="isLoading" 
                :items="backupItems"
                :url="url"
                :search_placeholder="'Search Restoration History'"
                :json_filter="filters"
                
                @update:items="backupItems = $event"
                @update:is_loading="isLoading = $event"
            >
                <table class="w-100 custom-red-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:100px;">Ref#</th>
                            <th class="text-center">Restored At</th>
                            <th>Status</th> 
                            <th>is Completed</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isLoading">
                            <td class="text-center text-danger" colspan="6"> -- LOADING RESTORE HISTORY -- </td>
                        </tr>
                        <tr v-else-if="backupItems.length == 0">
                            <td class="text-center text-danger" colspan="6"> -- NO  RESTORATION FOUND -- </td>
                        </tr>
                        <tr v-for="(item,itemIndex) in backupItems" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                            <th v-text="item.id"></th>
                            <td v-text="item.created_at"></td>
                            <td v-text="item.status_info"></td>
                            <td>
                                <span v-if="item.is_restored" class="text-primary">Restored</span>
                                <span v-else class="text-danger">Failed</span>
                            </td>
                            <td> 
                            </td>
                        </tr>
                    </tbody>
                </table>

            </page-datatable>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import PageDataTableVue from '../../../common/PageDataTable.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "page-datatable":PageDataTableVue
        },
        data: function () {
            return {    
                current_page:1,
                isLoading:false,
                backupItems:[],
                url:"/api/group/"+this.group_id+"/system/dbm/restore-list",
                filters:{},
                sql_file:"file-"+this._uid
            }
        },
        methods: {
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            restoreClick:function(){

                var vm = this; 
                const formData = new FormData();
                var file = document.querySelector('#'+vm.sql_file).files[0];
                //var file_ext = this.getFileExt(file.name);
                formData.append('file', file);
                var url = "/api/group/"+vm.group_id+"/system/dbm/restore-from-file";
                
                return  vm.$refs.swal_prompt.alert(
                        'question', 
                        "Restore from File?", 
                        "Confirm" , 
                        "POST", 
                        url, 
                        formData,
                        null,
                        "multipart/form-data"
                    ).then(resp=>{
                        if(resp.isConfirmed ){
                            if(resp.value.status == 1){
                                //vm.loadFileImportList();
                                vm.$refs.restore_page.reloadPage();
                            }
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
