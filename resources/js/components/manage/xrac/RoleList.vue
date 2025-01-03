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
                <span class="btn btn-default" @click="$refs.branch_list.show()">
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
                            <input type="radio" />
                        </td>
                        <td colspan="2"><a > - NO ACCESS - </a>
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
        <branch-list ref="branch_list" />
    </div>
</template>

<script>
    import ModalBranchListVue from './ModalBranchList.vue';
    export default {
        props:[ "app_user_id", "branch_id" ],
        components: { 
            "branch-list":ModalBranchListVue
        },
        watch: { 
        },
        data: function () {
            return {    
            }
        },
        methods: { 
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
