<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label> ROLES </label>
            </div> 
            <label class="mb-0 mt-2 mx-2">Select Branch:</label>
            <div class="input-group text-sm">
                <select class="form-control form-input-sm">
                    <option value="1"> -- DEFAULT BRANCH --</option>
                    <option>BRANCH 1</option>
                </select>
                <span class="btn btn-default" @click="modalBranch()">
                    <small title="Add/Show Branches" class="fa fa-list text-success"></small>
                </span>
            </div>
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
                            <switch2 v-model="allow_access" />
                        </td>
                        <td colspan="2">
                            <code > - NO ACCESS - </code>
                            <div>
                                <small class="text-secondary" > Disallow user to have access. </small>
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
    export default {
        props:[ "app_user_id", "branch_id" ],
        components: {  
            "switch2":BoostrapSwitch2Vue
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

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
