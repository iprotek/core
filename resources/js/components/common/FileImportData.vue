<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Import Data</div>
                    <div class="card-body"> 
                        <button v-if="has_close" class="btn btn-outline-secondary" @click="$emit('close_file_import_data')">
                            <span class="fa fa-arrow-left"></span>  CLOSE 
                        </button>

                        <button @click="current_page=1; selected_status_id = -1; loadFileImportList();" :class="'btn btn'+(selected_status_id == -1 ? '':'-outline')+'-primary btn-sm'"> 
                           <span v-if="batch" v-text="'('+batch.line_valid+')'"></span> ALL 
                        </button>
                        <button @click="current_page=1; selected_status_id = 0; loadFileImportList();" :class="'btn btn'+(selected_status_id == 0 ? '':'-outline')+'-warning btn-sm'">
                             <span v-if="pending_count !== null" v-text="'('+pending_count+')'"></span>
                             PENDING 
                        </button>
                        <button @click="current_page=1; selected_status_id = 1; loadFileImportList();" :class="'btn btn'+(selected_status_id == 1 ? '':'-outline')+'-success btn-sm'">
                            <span v-if="succeed_count !== null" v-text="'('+succeed_count+')'"></span>
                            SUCCEED
                        </button>
                        <button @click="current_page=1; selected_status_id = 2; loadFileImportList();" :class="'btn btn'+(selected_status_id == 2 ? '':'-outline')+'-danger btn-sm'">
                            <span v-if="failed_count !== null" v-text="'('+failed_count+')'"></span>
                            FAILED 
                        </button>
                    </div>
                    <div class="card-body p-1">
                        <div class="input-group text-sm">
                            <span class="btn btn-default">
                                <small title="Show" class="fa fa-search text-primary"></small>
                            </span> 
                            <input v-model="search" @keyup.enter="loadFileImportList()" type="text" class="form-control">
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <th style="width:80px;">
                                <small>ReF#</small>
                            </th>
                            <th style="width:80px;">
                                <small>BatchID</small>
                            </th>
                            <th style="width:150px;">
                                <small>TargetField</small>
                            </th>
                            <th>
                                <small>Data</small>
                            </th>
                            <th style="width:80px;">
                                <small>Status</small>
                            </th>
                            <th>
                                <small>Status Info</small>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-if="isLoading">
                                <th colspan="6" class="text-center"> <code> -- LOADING IMPORT DATA -- </code> </th>
                            </tr>
                            <tr v-else-if="importDataList.length == 0">
                                <th colspan="6" class="text-center"> <code> -- NO IMPORT DATA FOUND! -- </code> </th>
                            </tr>
                            <tr v-for="(item, itemIndex) in importDataList" v-bind:key="'import-data-'+item.id+'-'+itemIndex">
                                <th class="text-center text-danger" v-text="item.id" ></th>
                                <td>
                                    <label class="mb-0" v-text="file_import_batch_id"></label>
                                </td>
                                <td class="text-nowrap">
                                    <label v-if="batch" class="mb-0" v-text="batch.target_field"></label>
                                </td>
                                <td>
                                    <button @click="toggle_view(item)" class="btn btn-outline-primary btn-sm">
                                        <span v-if="item.view === false" :class=" item.view === false ?  'fa fa-eye':'' "></span>
                                        <span v-else  v-text="json_data(item.json_data)"></span> 
                                    </button>
                                 </td>
                                <td>
                                    <small v-if="item.status_id == 0" class="text-warning">
                                        PENDING
                                    </small>
                                    <small v-else-if="item.status_id == 1" class="text-success">
                                        SUCCEED
                                    </small>
                                    <small  v-else-if="item.status_id == 2" class="text-danger">
                                        FAILED
                                    </small>
                                </td>
                                <td>
                                    <small v-text="item.status_info"></small>
                                </td>
                            </tr>
                        </tbody> 
                    </table>
                    <div class="p-2">
                        <page-footer v-model="pageData" @page_changed="page_changed" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageFooterVue from './PageFooter.vue'; 
    export default {
        props:[ "file_import_batch_id", "status_id", "has_close" ],
        components: {
            "page-footer":PageFooterVue
        },
        watch: { 
        },
        data: function () {
            return { 
                selected_status_id: -1,
                current_page:1,
                pageData:null,
                importDataList:[],
                isLoading:false,
                batch:null,
                search:'',
                failed_count:null,
                succeed_count:null,
                pending_count:null
            }
        },
        methods: { 
            toggle_view:function(item){ 
                item.view = !item.view; 
                this.importDataList = this.importDataList.filter(a=>a); 
            },
            json_data:function(data){
                return JSON.stringify(data);
            },
            page_changed:function(page){  
                this.current_page = page;
                this.loadFileImportList(); 
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadFileImportList:function(){
                var vm = this;
                vm.importDataList = [];

                WebRequest2('GET', '/manage/file-imports/data/list?'+this.queryString({
                    file_import_batch_id:vm.file_import_batch_id,
                    status_id: vm.selected_status_id,
                    search:vm.search,
                    page:vm.current_page
                })).then(resp=>{
                    resp.json().then(data=>{ 
                        vm.pageData = data.paginate;
                        data.paginate.data.forEach((item)=>{
                            item.view = false;
                        });
                        vm.importDataList = data.paginate.data;
                        vm.batch = data.batch;
                        vm.failed_count = data.failed_count;
                        vm.succeed_count = data.succeed_count;
                        vm.pending_count = data.pending_count; 
                        //console.log(vm.importDataList);
                    });
                });
            }
        },
        mounted:function(){ 
            this.loadFileImportList();  
        },
        updated:function(){

        }
    }
</script>
