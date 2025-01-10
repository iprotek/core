<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" >
            <template slot="header" >
                Branch Access
            </template> 
            <template slot="body" >     
                <div>
                    <label class="text-primary">*Every check click action will immediately take effect.</label>
                    <table class="w-100 table table-bordered">
                        
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <td>IsAllowed</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="isLoad">
                                <th colspan="2">
                                    <span class="fa fa-refresh fa-pulse"></span> Loading Branches
                                </th>
                            </tr>
                            <tr v-else-if="listBranch.length == 0">
                                <th colspan="2">
                                    No branch found.
                                </th>
                            </tr>
                            <tr v-for="(branch, branchIndex) in listBranch" v-bind:key="'branch-'+branch.id+'-'+branchIndex">
                                <th v-text="branch.name"></th>
                                <td>
                                    <input type="checkbox" :value="branch.id" :checked=" allowedBranches.indexOf(branch.id) >= 0 " @click="putAllowedBrach">
                                </td>
                            </tr>
                        </tbody>
                        
                    </table>
                    <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
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
    export default {
        props:[ "group_id" ],
        components: {  
            "page-footer":PageFooterVue 
        },
        data: function () {
            return {        
                allowedBranches:[],
                listBranch:[],
                pageFooterData:null,
                isLoad:false,
                search:'',
                current_page:1,
                shared_account_id:0
           }
        },
        methods:{ 
            page_changed:function(page){
                this.current_page = page;
                this.loadOwnBranchList();
            },
            reset:function(){

            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            show:function(shared_account_id){ 

                this.$refs.modal.show();

                this.current_page = 1;
                this.shared_account_id = shared_account_id;
                //Load user allowed branches
                this.loadOwnBranchList();
                this.getAllowedBranches();
            },
            loadOwnBranchList:function(){
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
            },
            getAllowedBranches:function(){
                var vm = this;
                vm.allowedBranches = [];
                WebRequest2('GET','/api/group/'+this.group_id+'/sharedaccount/get-own-allowed-branches/'+this.shared_account_id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log("Allowed Branches", data);
                        var braches_ids = [];
                        data.forEach((allowed_branch)=>{
                            braches_ids.push(allowed_branch.billing_branch_id);
                        });
                        vm.allowedBranches = braches_ids; 
                    });
                });
            },
            putAllowedBrach:function(evt){
                var vm = this;
                var el = evt.target;
                var is_checked = el.checked;
                var val = el.value;
                if(is_checked){
                    vm.allowedBranches.push(val * 1);
                }
                else{
                    vm.allowedBranches = vm.allowedBranches.filter(a=>a != val);
                }
                //console.log(vm.allowedBranches);
                
                WebRequest2(
                    'PUT',
                    '/api/group/'+this.group_id+'/sharedaccount/set-own-allowed-branch/'+this.shared_account_id,
                    JSON.stringify({
                        billing_branch_id: val,
                        is_allowed: is_checked ? 1:0
                    })
                ).then(resp=>{
                    resp.json().then(data=>{
                        //console.log("Result=>", data); 
                    });
                });
            }
        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
