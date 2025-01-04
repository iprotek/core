<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label> ROLES </label>
            </div> 
            <label class="mb-0 mt-2 mx-2">Select Branch:</label>
            <branch-selector :is_system_select="false" :input_size="'input-group-lg'" />
            <table class="table m-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
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
                    </tr>
                </tbody>
            </table>
        </div> 
    </div>
</template>

<script> 
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import BranchSelectorVue from './BranchSelector.vue';
    export default {
        props:[ "app_user_id", "branch_id" ],
        components: {  
            "switch2":BoostrapSwitch2Vue,
            "branch-selector":BranchSelectorVue
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
