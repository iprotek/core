<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                BRANCH LIST MODAL
            </template> 
            <template slot="body" >
                
                <div v-if="view_mode == 'list'">
                    <button class="btn btn-outline-primary my-2" @click="view_mode = 'add-edit'; addorEdit()">
                        <span class="fa fa-plus"></span> ADD BRANCH
                    </button>     

                    <div class="input-group text-sm my-2">
                        <span class="btn btn-default">
                            <small title="Search" class="fa fa-search text-primary"></small>
                        </span>
                        <input v-model="search" @keyup.enter="loadBranches()" type="text" class="form-control" placeholder="Search branch.." />
                    </div> 
                    
                    <button type="button" class="btn btn-outline-success my-1" @click="$refs.web_submit.submit()" >
                        <web-submit :action="syncBranches" ref="web_submit" :label="'Sync Branches'" :icon_class="'fa fa-refresh'" :timeout="3000" />
                    </button>
                    <div class="card">
                        <table class="table m-0">
                            <thead>
                                <tr> 
                                    <th class="text-center">ID#</th>
                                    <th>Branch Name</th> 
                                    <th>Address</th> 
                                    <th>IsActive</th> 
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <td class="text-center" colspan="5">
                                        <code> -- Loading Branches -- </code>
                                    </td>
                                </tr>
                                <tr v-else-if="itemList.length == 0">
                                    <td class="text-center" colspan="5">
                                        <code> -- No Branch Found -- </code>
                                    </td>
                                </tr>
                                <tr v-for="(item,index) in itemList" v-bind:key="'item-'+item.id+'-'+index"> 
                                    <td class="text-center" v-text="item.id"></td>
                                    <th v-text="item.name"></th> 
                                    <th v-text="item.address"></th> 
                                    <td>
                                        <span v-if="item.is_active ==1" class="text-primary">Active</span>
                                        <span v-else class="text-danger">Inactive</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm" title="Update Branch" @click="view_mode = 'add-edit'; addorEdit(item)">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" title="Remove Branch" @click="remove(item.id)">
                                            <span class="fa fa-times"></span>
                                        </button>
                                    </td>
                                </tr> 
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
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
                    <div class="card mx-4">
                        <div class="card-header">
                            Branch Info
                        </div>
                        <div class="card-body">
                            <user-input2 v-if="branch_info.id" :value="branch_info.id"  :readonly="true"  :placeholder="'ID#'" :input_style="'height:40px;'" />
                            <div class="mt-2">
                                <switch2 v-model="branch_info.is_active" /> Is Active
                            </div>
                            <user-input2 v-model="branch_info.name"  :placeholder="'Branch Name(Required)'" :input_style="'height:40px;'" />
                            <user-input2  v-model="branch_info.data.representative" :placeholder="'Representative'" :input_style="'height:40px;'" />
                            <user-input2  v-model="branch_info.data.tel_no" :placeholder="'Tel#'" :input_style="'height:40px;'" />
                            <user-input2  v-model="branch_info.data.mobile_no" :placeholder="'Mobile#'" :input_style="'height:40px;'" />
                            <user-input2 v-model="branch_info.address" :placeholder="'Address'" :input_style="'height:40px;'" />
                            
                        </div>
                        <div class="card-footer">
                            <button v-if="branch_info.id" class="btn btn-outline-warning" @click="$refs.save_web_submit.submit()"> 
                                <web-submit ref="save_web_submit" :action="addorSave" :icon_class="'fa fa-save'" :label="'UPDATE & SYNC'" :timeout="4000" />
                            </button>
                            <button v-else class="btn btn-outline-primary" @click="$refs.add_web_submit.submit()">
                                <web-submit ref="add_web_submit" :action="addorSave" :icon_class="'fa fa-plus'" :label="'ADD & SYNC'" :timeout="4000" />
                            </button>
                        </div>
                    </div>
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
    import UserInput2Vue from '../../common/UserInput2.vue';
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    export default {
        props:[  ],
        components: {
            "page-footer":PageFooterVue,
            "web-submit":WebSubmitVue,
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue
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
                isLoading:false,
                branch_info:{
                    id:0,
                    is_active:true,
                    name:'',
                    address:'',
                    data:{
                        tel_no:'',
                        mobile_no:'',
                        representative:''
                    }
                },
                errors:[]
           }
        },
        methods:{ 

            addorSave:function(){
                var vm = this;
                if(this.branch_info.id == 0){
                    return WebRequest2('POST', '/manage/xrac/branch/sync-add', JSON.stringify(this.branch_info) ).then(resp=>{
                        return resp.json().then(data=>{
                            if(!resp.ok){
                                vm.errors = data;
                                return;
                            }
                            setTimeout(()=>{
                                vm.branch_info.id = data.branch.id;
                                vm.loadBranches();
                            }, 3000);

                            return data;
                        });
                    })
                }
                return WebRequest2('POST', '/manage/xrac/branch/sync-update', JSON.stringify(this.branch_info) ).then(resp=>{
                    return resp.json().then(data=>{
                        if(!resp.ok){
                            vm.errors = data;
                            return;
                        }
                    
                        vm.loadBranches();
                        return data;
                    });
                });
            },

            addorEdit:function(branchInfo = null){
                var vm = this; 
                var b = vm.branch_info;
                b.id = 0;
                b.is_active = true;
                b.name = '';
                b.address = '';
                b.data.tel_no = '';
                b.data.mobile_no = '';
                b.data.representative = ''

                if(branchInfo){
                    b.id = branchInfo.id;
                    b.is_active = branchInfo.is_active;
                    b.name = branchInfo.name;
                    b.address = branchInfo.address;
                    if(branchInfo.data){
                        var data = JSON.parse(branchInfo.data);
                        if(data){
                            b.data.tel_no = data.tel_no;
                            b.data.mobile_no = data.mobile_no;
                            b.data.representative = data.representative;
                        }
                    }
                }
            },

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
            remove:function(branch_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question',
                    "Remove Branch", 
                    "Confirm" , 
                    "POST", 
                    "/manage/xrac/branch/sync-remove", 
                    JSON.stringify({id: branch_id})
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            vm.loadBranches();
                            vm.promiseExec(res.value);
                        }
                        //vm.$emit('data_updated');
                    }
                });

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
                            vm.search = '';
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
