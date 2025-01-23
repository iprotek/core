<template>
    <div>
        <div v-if="view_mode == 'batch-view'" class="card">
            <div class="card-header">
                <label class="mb-0" v-text="importTitle"></label>
            </div> 
            <input @change="file_changed($event)" :id="import_file_name" type="file" style="display:none;" accept=".csv" />
            <div class="card-body p-1">
                <div class="input-group text-sm"> 
                    <span class="btn btn-primary" @click="fileUploadClick">
                        <small title="Show" class="fa fa-upload"> </small> IMPORT .CSV
                    </span> 
                    <span class="btn btn-default">
                        <small title="Show" class="fa fa-search text-primary"></small>
                    </span> 
                    <input v-model="search" @keyup.enter="current_page=1; loadFileImportList();" type="text" class="form-control">
                </div>
            </div>
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>
                            <small> Batch# </small>
                        </th>
                        <th>
                            <small> Filename </small>
                        </th>
                        <th>
                            <small> TargetField </small>
                        </th>
                        <th>
                            <small> UploadedBy </small>
                        </th>
                        <th>
                            <small> InterferBy </small>
                        </th>
                        <th class="text-nowrap">
                            <small> Valid/Processing/Total Lines </small> 
                        </th>
                        <th class="text-nowrap">
                            <small> Processed/Succeed/Failed  </small>
                        </th>
                        <th>
                            <small> Status </small>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="isLoading">
                        <td colspan="9" class="text-center"> <code> -- LOADING IMPORT -- </code> </td>
                    </tr>
                    <tr v-else-if="importList.length == 0">
                        <td colspan="9" class="text-center"> <code> -- NO IMPORT FOUND -- </code> </td>
                    </tr>
                    <tr v-for="(imp, importIndex) in importList"  v-bind:key="'import-'+imp.id+'-'+importIndex">
                        <td v-text="imp.id"></td>
                        <td v-text="imp.file_name"></td>
                        <td v-text="imp.target_field"></td>
                        <td >
                            <div v-if="imp.created_by && imp.created_by.user_admin" v-text="imp.created_by.user_admin.name"></div>
                            <small class="text-nowrap text-secondary" v-text="imp.created_at"></small>
                        </td>
                        <td >
                            <div v-if="imp.updated_by && imp.updated_by.user_admin" v-text="imp.updated_by.user_admin.name"></div>
                            <small class="text-nowrap text-secondary" v-text="imp.interfer_at"></small>
                        </td>
                        <td v-text="imp.line_valid+'/'+imp.line_processing+'/'+imp.total_lines"></td>
                        <td class="text-nowrap" >
                            <span v-text=" (imp.line_succeed+imp.line_failed)+'/'+imp.line_succeed+'/'+imp.line_failed"></span>
                            <button @click="selected_file_import_batch_id = imp.id ;selected_status_id = -1 ;view_mode = 'data-view'" class="btn btn-outline-primary btn-sm float-right" title="View Details"> <span class="fa fa-eye"></span> </button>
                        </td> 
                        <td >
                            <span v-if="imp.status_id == 0" class="text-warning" :title="imp.status_info" >Pending to Import</span>
                            <span v-if="imp.status_id == 1" class="text-success" :title="imp.status_info" >Completed</span>
                            <span v-if="imp.status_id == 2" class="text-danger" :title="imp.status_info" >Failed</span>
                            <span v-if="imp.status_id == 3" class="text-primary" :title="imp.status_info" >Processing</span>
                            <span v-if="imp.status_id == 4" class="text-secondary" :title="imp.status_info" >Stopped</span>
                            <div>
                                <small class="text-secondary" v-text="imp.updated_at"></small>
                            </div>
                            <div v-if="imp.status_info">
                                <small class="text-secondary" v-text="imp.status_info"></small>
                            </div>
                        </td>
                        <td class="text-nowrap" >
                            <button @click="action_prompt(imp,'stop', 'Do you want to Stop this'+(imp.status_id == 0?' from queue':'')+'?')" v-if="imp.status_id == 3 || imp.status_id == 0" class="btn btn-danger btn-sm">
                                <span class="fa fa-times"></span>
                            </button>
                            <button @click="action_prompt(imp,'start', 'Do you want to Start this?')"  v-if="imp.status_id == 0" class="btn btn-primary btn-sm" title="Force to Start replacing processing">
                                <span class="fa fa-play"></span>
                            </button>
                            <button @click="action_prompt(imp,'retry', 'Do you want to Retry this?')"  v-if="imp.status_id == 2 || imp.status_id == 4" class="btn btn-success btn-sm" title="Retry">
                                <span class="fa fa-redo"></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="9">
                            <page-footer v-model="pageData" @page_changed="page_changed"></page-footer>
                        </td>
                    </tr>
                </tfoot>
            </table> 
        </div>
        <div v-else-if="view_mode == 'data-view'">
            <file-import @close_file_import_data=" view_mode='batch-view'" :file_import_batch_id="selected_file_import_batch_id" :has_close="true" :status_id="selected_status_id" />
        </div>
        <swal ref="swal_prompt"></swal>
    </div>
</template>
<script>
    import SwalVue from './Swal.vue';
    import PageFooterVue from './PageFooter.vue';
    import FileImportDataVue from './FileImportData.vue';
    export default {
        props:[ "title", "target_field", "settings"  ],
        components: { 
            "page-footer":PageFooterVue,
            "swal":SwalVue,
            "file-import":FileImportDataVue
        },
        watch: { 
        },
        data: function () {
            return {
                selected_file_import_batch_id:0,
                selected_status_id:-1,
                view_mode:'batch-view',
                importTitle:'File Import',
                pageData:null,
                importList:[],
                search:'',
                current_page:1,
                isLoading:false,
                import_file_name:'file-import-'+this._uid
            }
        },
        methods: {  
            action_prompt:function(item, action, title ){
                var vm =this;
                var request = {
                    file_import_batch_id:item.id
                }
                 this.$refs.swal_prompt.alert(
                    'question',
                    title, 
                    "Confirm" , 
                    "POST", 
                    "/manage/file-imports/batch/action/"+action, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        //if(res.value.status == 1){
                        vm.loadFileImportList();
                        //} 
                    }
                });
            },
            getFileExt:function(filename){
                return filename.split('.').pop();
            },
            file_changed:function(evt){

                var vm = this;
                const formData = new FormData();
                var file = evt.target.files[0];
                var file_ext = this.getFileExt(file.name);
                
                formData.append('target_name', vm.target_name);
                formData.append('target_id', vm.target_id); 
                formData.append('file', file);
                formData.append('file_name', file.name);
                formData.append('file_type', file.type);
                formData.append('file_ext', file_ext);
                formData.append('target_field', vm.target_field);
                formData.append('settings', '{}' );

                var url = "/manage/file-imports/batch/add";
                
                vm.$refs.swal_prompt.alert(
                        'question', 
                        "Import File", 
                        "Confirm" , 
                        "POST", 
                        url, 
                        formData,
                        null,
                        "multipart/form-data"
                    ).then(resp=>{
                        if(resp.isConfirmed ){
                            if(resp.value.status == 1){
                                vm.loadFileImportList();
                            }
                        } 
                    }); 
            },
            fileUploadClick:function(){
                document.querySelector('#'+this.import_file_name).click();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadFileImportList();
            },
            loadFileImportList:function(){
                var vm = this;
                vm.isLoading = true;
                vm.importList = [];
                WebRequest2('GET', '/manage/file-imports/batch/list?'+this.queryString({
                    search: vm.search,
                    page: vm.current_page,
                    items_per_page: 10
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.importList = data.data;
                    });
                })
            }
        },
        mounted:function(){  
            if(this.title){
                this.importTitle = this.title;
            }    
            this.loadFileImportList(); 
        }
    }
</script>
