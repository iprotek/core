<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label class="mb-0"> ROLES </label>
            </div> 
            <div v-if="!is_default_setting"> 
                    <label v-if="!disable_multi_branch" class="mb-0 mt-2 mx-2">Select Branch:</label>
                    <branch-selector :is_system_select="false" :input_size="'input-group-lg'" @selection_changed="branch_changed" />
           </div>
            <div v-else>
                <button class="btn btn-outline-success float-right" style="border-radius:0px;" title="Sync roles" @click="$refs.sync_roles.submit()">
                    <web-submit ref="sync_roles" :action="syncRoles" :icon_class="'fa fa-refresh'" :label="'SYNC'" :timeout="3000" />
                </button>
                <button class="btn btn-outline-primary float-right" style="border-radius:0px;" @click="$refs.modal_role.show()">
                    <span class="fa fa-plus"></span> ADD ROLE
                </button>
            </div>
            <table class="table m-0">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th style="width:20px;min-width:40px;"></th>
                        <th>Name</th>
                        <th></th>
                        <th v-if="is_default_setting" style="width:80px;" class="text-center text-nowrap">Users
                            <small class="text-nowrap">
                                 (<span class="fa fa-question text-secondary" title="Number of users assigned to this role."></span>)
                            </small>
                        </th>
                        <th v-if="is_default_setting" style="width:100px;" class="text-center">Is Active</th>
                        <th v-if="is_default_setting"  style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!is_default_setting"> 
                        <td colspan="2" class="text-center px-0">
                            <switch2 v-model="allow_access" :off_color="'red'" @value_changed="allow_access_changed" />
                        </td>
                        <td colspan="2">
                            <code v-if="!allow_access"> - NO ACCESS - </code>
                            <label class="text-primary" v-else>GRANT ACCESS</label>
                            <div>
                                <small v-if="!allow_access" class="text-secondary" > Disallow user to have access on selected branch. </small>
                                <small v-else> Granting access based on role in a selected branch. </small>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="isLoading">
                        <td :colspan="is_default_setting ? 7: 4"  class="text-center">
                            <code> Loading Roles</code>
                        </td>
                    </tr>
                    <tr v-else-if="roleList.length == 0">
                        <td :colspan="is_default_setting ? 7: 4"  class="text-center">
                            <code> No Role Found. </code>
                        </td>
                    </tr>
                    <tr v-for="(role, roleIndex) in roleList" v-bind:key="'role-item-'+role.id+'-'+roleIndex">
                        <th class="text-center" v-text="role.id"></th>
                        <td class="text-center px-0">
                            <input  style="width:40px;min-width:20px;" :id="'role-'+role.id" :value="role.id" :class="'form-control '+(role.id == role_id ? 'is-valid':'' )" type="radio" name="role-selection" @click=" role_id = role.id; $emit('selection_changed', role.id)" />
                        </td>
                        <td colspan="2" >
                            <label class="mb-0" :for="'role-'+role.id" v-text="role.name"></label>
                            <div>
                                <small  v-text="role.description" class="text-secondary" ></small>
                            </div>
                        </td>
                        <th v-if="is_default_setting" class="text-center">
                            <code>-N/A-</code>
                        </th>
                        <td v-if="is_default_setting" class="text-center">
                            <label v-if="role.is_active" class="text-success">YES</label>
                            <label v-else class="text-danger">NO</label>
                        </td>
                        <td v-if="is_default_setting" class="text-center" >
                            <button class="btn btn-outline-warning float-right" style="border-radius:0px;" @click="$refs.modal_role.show(role.id, role)">
                                <span class="fa fa-edit"></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> 
        <modal-role ref="modal_role" @modal_updated="loadRoles()" />
    </div>
</template>

<script> 
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import WebSubmitVue from '../../common/WebSubmit.vue';
    import BranchSelectorVue from './BranchSelector.vue';
    import ModalAddEditRoleVue from './modals/ModalAddEditRole.vue';
    export default {
        props:[ "app_user_id", "is_default_setting" ],
        components: {  
            "switch2":BoostrapSwitch2Vue,
            "branch-selector":BranchSelectorVue,
            "modal-role":ModalAddEditRoleVue,
            "web-submit":WebSubmitVue
        },
        watch: { 
            app_user_id(val){
                if(!val) return;
                this.loadUserBranchPositionAccess();
            }
        },
        data: function () {
            return { 
                allow_access:true,
                roleList:[],
                isLoading:false,
                role_id:0,
                branch_id:0,
                isUserLoading:false,
                disable_multi_branch:true
            }
        },
        methods: { 
            allow_access_changed:function(is_allow){ 
                var req = {
                    branch_id: this.branch_id,
                    app_account_id: this.app_user_id,
                    is_allowed: is_allow
                };
                WebRequest2('POST', '/manage/xrac/user-role/set-branch-role', JSON.stringify(req)).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                    });
                });
            },
            branch_changed:function(branch_id, disable_multi_branch){
                this.branch_id = branch_id;
                this.disable_multi_branch = disable_multi_branch;
                this.loadUserBranchPositionAccess();
                this.$emit('branch_changed', branch_id);
            },
            syncRoles:function(){
                var  vm = this;
                return WebRequest2('GET', '/manage/xrac/role/sync-roles').then(resp=>{
                    return  resp.json().then(data=>{
                        vm.loadRoles();
                        return data;
                    })
                })
            },
            modalBranch:function(){
                window.ModalBranchView.show();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadRoles:function(){
                var vm = this;
                vm.isLoading = true;
                vm.roleList = [];
                WebRequest2('GET', '/manage/xrac/role/list').then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.roleList = data;
                        if(vm.app_user_id){
                            vm.loadUserBranchPositionAccess();
                        }
                    })
                });
            },
            loadUserBranchPositionAccess:function(){
                var vm = this;
                //vm.allow_access = false;
                vm.role_id = 0;
                if(!this.branch_id || !this.app_user_id || vm.isUserLoading) return;
                vm.isUserLoading = true;
                WebRequest2('GET', '/manage/xrac/user-role/role-info/'+this.branch_id+'/'+this.app_user_id).then(resp=>{
                    resp.json().then(data=>{
                        if(!resp.ok){
                            vm.allow_access = false;
                        }

                        vm.isUserLoading = false;
                        vm.allow_access = data.is_allowed === true;
                        if(data.xrole_id > 0){
                            vm.role_id = data.xrole_id;
                            vm.$emit('selection_changed', data.xrole_id);
                        }
                    });
                });
            }

        },
        mounted:function(){     
            this.loadRoles();
        },
        updated:function(){

        }
    }
</script>
