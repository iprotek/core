<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                BRANCH LIST MODAL
            </template> 
            <template slot="body" >
                
                <div class="input-group text-sm mt-2">
                    <span class="btn btn-default">
                        <small title="Search" class="fa fa-search text-primary"></small>
                    </span>
                    <input v-model="search" @keyup.enter="loadBranches()" type="text" class="form-control" placeholder="Search branch.." />
                </div> 
                <div v-if="view_mode == 'list'">
                    <button class="btn btn-outline-primary my-2" @click="view_mode = 'add-edit'">
                        <span class="fa fa-plus"></span> ADD BRANCH
                    </button>     
                    
                    <button type="button" class="btn btn-outline-success ml-2" @click="$refs.web_submit.submit()" >
                        <web-submit :action="syncBranches" ref="web_submit" :label="'Sync Branches'" :icon_class="'fa fa-refresh'" :timeout="3000" />
                    </button>
                    <div class="card">
                        <table class="table m-0">
                            <thead>
                                <tr> 
                                    <th class="text-center">ID#</th>
                                    <th>Branch Name</th> 
                                    <th>IsActive</th> 
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <td class="text-center" colspan="4">
                                        <code> -- Loading Branches -- </code>
                                    </td>
                                </tr>
                                <tr v-else-if="itemList.length == 0">
                                    <td class="text-center" colspan="4">
                                        <code> -- No Branch Found -- </code>
                                    </td>
                                </tr>
                                <tr v-for="(item,index) in itemList" v-bind:key="'item-'+item.id+'-'+index"> 
                                    <td class="text-center" v-text="item.id"></td>
                                    <th v-text="item.name"></th> 
                                    <td>
                                        <span v-if="item.is_active ==1" class="text-primary">Active</span>
                                        <span v-else class="text-danger">Inactive</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                    </td>
                                </tr> 
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <page-footer v-model="pageData" @page_changed="page_changed" />
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div v-else-if="view_mode == 'add-edit'">
                    <button class="btn btn-outline-danger my-2" @click="view_mode = 'list'">
                        <span class="fa fa-arrow-left"></span> BACK
                    </button>     

                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import PageFooterVue from '../../common/PageFooter.vue';
    import WebSubmitVue from '../../common/WebSubmit.vue';
    export default {
        props:[  ],
        components: {
            "page-footer":PageFooterVue,
            "web-submit":WebSubmitVue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                pageData:null,
                itemList:[],
                current_page:1,
                view_mode:'list',
                search:'',
                isLoading:false
           }
        },
        methods:{ 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
            },
            reset:function(){

            },
            show:function(){ 
                var vm = this;
                vm.view_mode = 'list'

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            add:function(){
                var vm = this;
                /*
                    this.$refs.swal_prompt.alert(
                        'question',
                        "Add Event", 
                        "Confirm" , 
                        "POST", 
                        "/manage/dashboard/resort-events/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            vm.$emit('data_updated');
                        }
                    });
                */
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },
            loadBranches:function(){
                var vm = this;
                vm.isLoading = true;
                vm.itemList = [];
                WebRequest2('GET', '/manage/xrac/branch/list?'+this.queryString({
                    search:vm.search,
                    page:vm.current_page,
                    items_per_page:10
                })).then(resp=>{

                    vm.isLoading = false;

                    resp.json().then(data=>{

                        vm.pageData = data;
                        vm.itemList = data.data;
                    
                    });

                })
            },

            syncBranches:function(){
                var  vm = this;
                return WebRequest2('GET', '/manage/xrac/branch/sync-branches').then(resp=>{
                    return resp.json().then(data=>{
                        if(data.status == 1){
                            vm.current_page = 1;
                            setTimeout(()=>{
                                vm.loadBranches();
                            },2000);
                        }
                        return data;
                    });
                });
            }

        },
        mounted:function(){
            this.loadBranches();   
        },
        updated:function(){

        }
    }
</script>
