<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label> ROLES </label>
            </div> 
            <div v-if="!is_default_setting">
                <label class="mb-0 mt-2 mx-2">Select Branch:</label>
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
                            <switch2 v-model="allow_access" :off_color="'red'" />
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
        },
        data: function () {
            return { 
                allow_access:true,
                roleList:[],
                isLoading:false,
                role_id:0,
                branch_id:0
            }
        },
        methods: { 
            branch_changed:function(branch_id){
                this.branch_id = branch_id;
                this.loadUserBranchPositionAccess();
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
                console.log("BranchInfo", this.branch_id, this.app_user_id);
                if(!this.branch_id || !this.app_user_id) return;


                console.log("LOADING INFO");
            }

        },
        mounted:function(){     
            this.loadRoles();
        },
        updated:function(){

        }
    }
</script>
