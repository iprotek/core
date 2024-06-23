<template>
    <div>
        <div :style="'display:'+(show_data_form ?'':'none')+';'">
            <data-form ref="data_form" @cancel="cancel" :group_id="group_id"></data-form>
        </div>
        <div v-if="show_data_form == false" class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="input-group" >
                                <span class="input-group-text btn btn-success" title="Add Searchable" @click="$refs.data_form.show(0);show_data_form = true"  > 
                                    <span class="fa fa-plus"></span> 
                                </span>
                                <span class="input-group-text">
                                    <span class="fa fa-search"></span>
                                </span>
                                <input v-model="search" class="form-control" placeholder="Seach any keywords" @keyup.enter="loadData()"  />

                                <span style="min-width: 250px;"> 
                                    <select2 @selected="loadData()" v-model="model_item" :has_clear="true" :url="'/manage/projects-monitoring/model-fields/model/list-selection'" :placeholder="' -- ALL Model -- '"></select2>
                                </span>

                                <span style="min-width: 250px;"> 
                                    <select2 @selected="loadData()" v-model="field_info"  :has_clear="true" :url="'/manage/projects-monitoring/model-fields/field/list-selection'"  :placeholder="'-- SEARCh ALL Field --'"></select2>
                                </span>
                                <button class="input-group-text">Find</button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td style="width:150px;">Type/Model</td>
                                        <td> Info </td>
                                        <td style="width:140px;" class="text-center"> Task Status </td>
                                        <td style="width:100px;">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td colspan="4" class="text-center"> -- Loading Data -- </td> 
                                    </tr>
                                    <tr v-else-if="pageDataList.length == 0">
                                        <td colspan="4" class="text-center"> -- No Data Found. -- </td> 
                                    </tr>
                                    <tr v-for="(dataItem, dataIndex) in pageDataList" v-bind:key="'item-'+dataItem.id+'-'+dataIndex">
                                        
                                        <td class="py-1" v-text="dataItem.data_model_type"></td>
                                        <td class="py-1" >
                                            <label v-text="dataItem.name" class="mb-0"></label> <small class="text-secondary" v-if="dataItem.details" v-text="' - '+dataItem.details"></small>
                                        </td>
                                        <td class="text-center py-1">
                                            <code v-if="dataItem.has_task != 1"> No Task </code>
                                            <div v-else>
                                                <span v-if="dataItem.task_status == 'pending' " class="text-warning" >Pending</span>
                                                <span v-else-if="dataItem.task_status == 'processing' " class="text-primary" >Processing</span>
                                                <b v-else-if="dataItem.task_status == 'completed' " class="text-success" >Completed</b>
                                                <span v-else-if="dataItem.task_status == 'cancelled' " class="text-danger" >CANCELLED</span>
                                                <span v-else-if="dataItem.task_status" class="text-secondary" v-text="dataItem.task_status"></span>
                                                <span v-else class="text-info">Waiting</span>
                                            </div>
                                        </td>
                                        <td class="py-1" >
                                            <button class="btn btn-outline-warning btn-sm" @click="$refs.data_form.show(dataItem.id,dataItem);show_data_form = true">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <page-footer v-model="pageData" @page_changed="page_changed"></page-footer>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import PageFooterVue from '../../../common/PageFooter.vue';
    import DataFormVue from '../../../common/data/form/DataForm.vue';
    import Select2Vue from '../../../common/Select2.vue';
    export default {
        props:[ "group_id", "is_data" ],
        components: { 
            "page-footer":PageFooterVue,
            "data-form":DataFormVue,
            "select2":Select2Vue
        },
        data: function () {
            return {    
                show_data_form:false,
                search:'',
                current_page:1,
                pageData:null,
                pageDataList:[],
                isLoading:false,
                field_info:{
                    id:0,
                    text:''
                },
                model_item:{
                    id:0,
                    text:''
                }
            }
        },
        methods: { 
            cancel:function(){
                this.show_data_form = false;
                this.loadData();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadData();
            },
            loadData:function(){
                this.pageDataList = [];
                this.isLoading = true;
                var vm = this;

                var url = "/manage/projects-monitoring/searches/data/list";
                if(vm.is_data){
                    url = "/manage/iprotek-data/searches/data/list";
                }

                WebRequest2('GET', url+'?'+this.queryString({
                    search:this.search,
                    page:this.current_page,
                    items_per_page:10,
                    field_id: this.field_info.id,
                    data_model_id: this.model_item.id
                })).then(resp=>{

                    vm.isLoading = false;
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.pageData = data;
                        vm.pageDataList = data.data;
                    });

                })
            }
        },
        mounted:function(){
            this.loadData();
            window.DataFormView = this.$refs.data_form;
        },
        updated:function(){

        }
    }
</script>
