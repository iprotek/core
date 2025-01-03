<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole"> ROLE DEFAULTS </a>
        <div class="row">
            <div class="col-md-3">
                <app-user-list @selected_app_user="selected_app_user" />
            </div>
            <div class="col-md-3" v-if="selected_app_user_id">
                <role-list :app_user_id="selected_app_user_id" />
            </div>
            <div class="col-md-6" v-if="selected_app_user_id && xrole_id">
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
    import RoleListVue from './RoleList.vue';
    export default {
        props:[  ],
        components: {
            "app-user-list":AppUserListVue,
            "role-list":RoleListVue
        },
        data: function () {
            return {
                selected_app_user_id:0,
                xrole_id:0
            }
        },
        methods: { 
            selected_app_user:function(id){
                this.selected_app_user_id = 0;
                this.xrole_id = 0;
                var vm = this;
                setTimeout(()=>{
                    vm.selected_app_user_id = id;
                }, 100);
            },
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
