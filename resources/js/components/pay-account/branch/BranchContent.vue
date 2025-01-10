<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Branches</div>
                    <div class="card-body">
                        <div class="input-group" >
                            <span class="input-group-text btn btn-outline-success bg-success" title="Invite" @click="$refs.modal_branch.show()" > 
                                <span class="fa fa-plus"></span>&nbsp;&nbsp;BRANCH
                            </span> 
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span> 
                            <input placeholder="Search shared accounts keywords." v-model="search" style="text-align:left;" class="form-control" type="text" @keyup.enter="input_search" />      
                            <button class="input-group-text" @click="input_search">Find</button> 
                        </div> 
                        <table class="w-100 table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Coordinates</th>
                                    <td>Status</td> 
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoad">
                                    <th colspan="5" class="text-center">
                                        <h4> <span class="fa fa-refresh fa-pulse"></span> Loading Branches </h4>
                                    </th>
                                </tr>
                                <tr v-else-if="listBranch.length == 0">
                                    <th colspan="5" class="text-center">
                                        <h4>No branch found.</h4>
                                    </th>                                    
                                </tr>
                                <tr v-for="(acc, accIndex) in listBranch" v-bind:key="'acc-'+acc.id+'-'+accIndex">
                                    <th v-text="acc.name"></th>
                                    <th v-text="acc.address"></th>
                                    <th v-text="acc.coordinates"></th>
                                    <th v-text="acc.status"></th> 
                                    <th>
                                        <div v-if="acc.id" class="text-nowrap">
                                            <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_branch.show(acc.id, acc)">
                                                <span class="fa fa-edit"></span> Edit
                                            </button> 
                                            <button class="btn btn-outline-danger btn-sm" @click="$refs.modal_branch.remove(acc.id)">
                                                <span class="fa fa-times"></span> Remove
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                        <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
                    </div>
                </div>
            </div>
        </div>
        <modal-branch ref="modal_branch" :group_id="group_id" @modal_updated="loadBranchesList"></modal-branch>
    </div>
</template>

<script>
    import PageFooterVue from '../../common/PageFooter.vue' 
    import ModalBrachVue from './ModalBrach.vue' 
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue, 
            "modal-branch":ModalBrachVue
        },
        data: function () {
            return {    
                isLoad:false,
                listBranch:[],
                pageFooterData:null,
                current_page:1,
                search:''
            }
        },
        methods: { 
            input_search:function(val){
                this.loadBranchesList();
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
            loadBranchesList:function(){
                var vm = this;
                vm.isLoad = true;
                vm.listBranch = [];
                WebRequest2('GET','/api/group/'+this.group_id+'/branch/own-list?'+this.queryString({
                    search: this.search,
                    page: this.current_page,
                    items_per_page: 10
                })).then(resp=>{
                    resp.json().then(data=>{
                        vm.pageFooterData = data;
                        vm.isLoad = false;
                        vm.listBranch = data.data;
                    });
                });

            }
        },
        mounted:function(){     
            this.loadBranchesList();
        },
        updated:function(){

        }
    }
</script>
