<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole"> ROLE DEFAULTS </a>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <app-user-list />
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <label> ROLES </label>
                    </div> 
                    <label class="mb-0 mt-2 mx-2">Select Branch:</label>
                    <select class="form-control form-input-sm">
                        <option value="1"> -- DEFAULT BRANCH --</option>
                        <option>BRANCH 1</option>
                    </select>
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
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <input type="radio"/> DEFAULT  
                        <input type="radio"/> CUSTOM  
                        <button class="btn btn-outline-primary btn-sm text-nowrap"> SAVE & SYNC USER ROLE ACCESS </button>
                    </div>
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th style="width: 50px;"></th>
                                <th>ACCESS CONTROL DESCRIPTION</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" />
                                </td>
                                <td colspan="2"> Control1  <small class="text-secondary"> cont1 </small>
                                </td> 
                            </tr> 
                            <tr>
                                <td></td>
                                <td class="text-center ml-2" >
                                    <input type="checkbox" />
                                </td>
                                <td> ACCESS 1  <small class="text-secondary"> access desc </small>
                                </td> 
                            </tr> 
                        </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AppUserListVue from './AppUserList.vue';
    export default {
        props:[  ],
        components: {
            "app-user-list":AppUserListVue
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
            }

        },
        mounted:function(){  
        },
        updated:function(){

        }
    }
</script>
