<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Shared to Accounts</div>
                    <div class="card-body">
                        <div class="input-group" >
                            <span class="input-group-text btn btn-outline-success bg-success" title="Invite" @click="$refs.modal_send_invitation.show()" > 
                                <span class="fa fa-envelope"></span>&nbsp;&nbsp;Invite
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
                                    <th>Role</th>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoad">
                                    <th colspan="3" class="text-center">
                                        <h4> <span class="fa fa-refresh fa-pulse"></span> Loading shared accounts</h4>
                                    </th>
                                </tr>
                                <tr v-else-if="sharedAccountList.length == 0">
                                    <th colspan="3" class="text-center">
                                        <h4>No shared account found.</h4>
                                    </th>                                    
                                </tr>
                                <tr v-for="(acc, accIndex) in sharedAccountList" v-bind:key="'acc-'+acc.id+'-'+accIndex">
                                    <th v-text="acc.user_name_full_name"></th>
                                    <th v-text="acc.role"></th>
                                    <th>
                                        <div v-if="acc.share_to_app_user_account_id" class="text-nowrap">
                                            <button class="btn btn-outline-warning btn-sm">
                                                <span class="fa fa-edit"></span> Edit
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" @click="$refs.modal_branch_access.show(acc.share_to_app_user_account_id)">
                                                <span class="fa fa-list"></span> Branch Access
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm">
                                                <span class="fa fa-user-times"></span> Block
                                            </button>
                                            <!--
                                            <button class="btn btn-outline-danger btn-sm">
                                                <span class="fa fa-trash"></span> Removed
                                            </button>-->
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
        <modal-send-invitation ref="modal_send_invitation"></modal-send-invitation>
        <modal-branch-access ref="modal_branch_access" :group_id="group_id"></modal-branch-access>
    </div>
</template>

<script>
    import PageFooterVue from '../../common/PageFooter.vue'
    import ModalSendInvitationVue from './ModalSendInvitation.vue'
    import ModalBranchAccessVue from './ModalBranchAccess.vue'
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue,
            "modal-send-invitation":ModalSendInvitationVue,
            "modal-branch-access":ModalBranchAccessVue
        },
        data: function () {
            return {    
                isLoad:false,
                sharedAccountList:[],
                pageFooterData:null,
                current_page:1,
                search:''
            }
        },
        methods: { 
            input_search:function(val){
                this.loadSharedAccounts();
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
            loadSharedAccounts:function(){
                var vm = this;
                vm.isLoad = true;
                vm.sharedAccountList = [];
                WebRequest2('GET','/manage/shared-accounts/list?'+this.queryString({
                    search: this.search,
                    page: this.current_page,
                    items_per_page: 10
                })).then(resp=>{
                    resp.json().then(data=>{
                        vm.pageFooterData = data;
                        vm.isLoad = false;
                        vm.sharedAccountList = data.data;
                    });
                });

            }
        },
        mounted:function(){     
            this.loadSharedAccounts();
        },
        updated:function(){

        }
    }
</script>
