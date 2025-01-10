<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Categories</div>
                    <div class="card-body pt-0">
                        <div class="input-group" >
                            <span class="input-group-text btn btn-outline-success bg-success" @click="$refs.modal_billing_category.show()" > 
                                <span class="fa fa-plus"></span>&nbsp;&nbsp;Add Category
                            </span> 
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span> 
                            <input placeholder="Search category keywords.. " v-model="search" style="text-align:left;" class="form-control" type="text" @keyup.enter="input_search" />      
                            <button class="input-group-text" @click="input_search">Find</button> 
                        </div> 
                        <table class="w-100 table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoad">
                                    <th class="text-center" colspan="2">
                                        <h4>
                                            <span class="fa fa-refresh fa-pulse"></span> Loading Categories
                                        </h4>
                                    </th>
                                </tr>
                                <tr v-else-if="listCategory.length == 0">
                                    <th class="text-center" colspan="2">
                                        <h4> No Category Found. </h4>
                                    </th>
                                </tr>
                                <tr v-for="(billing_category, billingCategoryIndex) in listCategory" v-bind:key="'group-'+billing_category.id+'-'+billingCategoryIndex">
                                    <th v-text="billing_category.name"></th>
                                    <th>
                                        <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_billing_category.show(billing_category.id, billing_category.name)">
                                            <span class="fa fa-edit"></span> Edit
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" @click="$refs.modal_billing_category.remove(billing_category.id)">
                                            <span class="fa fa-times"></span> Remove
                                        </button>
                                    </th>
                                </tr>

                            </tbody>
                        </table>
                        <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
                    </div>
                </div>
            </div>
        </div>
        <modal-billing-category ref="modal_billing_category" :group_id="group_id" @modal_updated="loadCategoryList"></modal-billing-category>
    </div>
</template>

<script>
    import PageFooterVue from '../../../common/PageFooter.vue'
    import ModalAddBillingCategoryVue from './ModalAddBillingCategory.vue'
    export default {
        props:[ "group_id" ],
        components: { 
            "page-footer":PageFooterVue,
            "modal-billing-category":ModalAddBillingCategoryVue
        },
        data: function () {
            return {    
                isLoad:false,
                listCategory:[],
                search:'',
                pageFooterData:null,
                current_page:1
            }
        },
        methods: { 
            input_search:function(val){
                this.current_page = 1;
                this.loadCategoryList();

            },
            page_changed:function(page){
                this.current_page = page;
                this.loadCategoryList();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadCategoryList:function(){
                var vm = this;
                vm.isLoad = true;
                this.listCategory = [];
                WebRequest2('GET', '/api/group/'+this.group_id+'/settings/category/list?'+this.queryString({
                    search: vm.search,
                    page:vm.current_page,
                    items_per_page:10
                })).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.pageFooterData = data;
                        vm.isLoad = false;
                        vm.listCategory = data.data;

                    });
                });
            }
        },
        mounted:function(){    
            this.loadCategoryList(); 
        },
        updated:function(){

        }
    }
</script>
