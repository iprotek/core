<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label> ROLES </label>
            </div> 
            <div v-if="!is_default_setting">
                <label class="mb-0 mt-2 mx-2">Select Branch:</label>
                <branch-selector :is_system_select="false" :input_size="'input-group-lg'" />
            </div>
            <div v-else>
                <button class="btn btn-outline-primary float-right" style="border-radius:0px;" @click="$refs.modal_role.show()">
                    <span class="fa fa-plus"></span> ADD ROLE
                </button>
            </div>
            <table class="table m-0">
                <thead>
                    <tr>
                        <th></th>
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
                        <td class="text-center">
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
                    <tr>
                        <td class="text-center">
                            <input type="radio" />
                        </td>
                        <td colspan="2"><a >Staff</a>
                            <div>
                                <small class="text-secondary" >Regular User</small>
                            </div>
                        </td>
                        <th v-if="is_default_setting" class="text-center">8</th>
                        <td v-if="is_default_setting" class="text-center">
                            <label class="text-success">YES</label>
                        </td>
                        <td v-if="is_default_setting" class="text-center" >
                            <button class="btn btn-outline-warning float-right" style="border-radius:0px;" @click="$refs.modal_role.show()">
                                <span class="fa fa-edit"></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> 
        <modal-role ref="modal_role" />
    </div>
</template>

<script> 
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import BranchSelectorVue from './BranchSelector.vue';
    import ModalAddEditRoleVue from './modals/ModalAddEditRole.vue';
    export default {
        props:[ "app_user_id", "is_default_setting" ],
        components: {  
            "switch2":BoostrapSwitch2Vue,
            "branch-selector":BranchSelectorVue,
            "modal-role":ModalAddEditRoleVue
        },
        watch: { 
        },
        data: function () {
            return { 
                allow_access:true
            }
        },
        methods: { 
            modalBranch:function(){
                window.ModalBranchView.show();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadActiveBranches:function(){

            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
