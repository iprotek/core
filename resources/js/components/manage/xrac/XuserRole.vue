<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole"> ROLE DEFAULTS </a>
        <div class="row">
            <div class="col-md-3">
                <app-user-list @selected_app_user="selected_app_user" />
            </div>
            <div class="col-md-2" v-if="selected_app_user_id">
                <role-list :app_user_id="selected_app_user_id" :is_default_setting="false" @selection_changed="(val)=>{ xrole_id = val }" @branch_changed="branch_changed" />
            </div>
            <div class="col-md-7" v-if="selected_app_user_id && xrole_id">
                <control-access :branch_id="branch_id" :is_default_setting="false" :app_account_id="selected_app_user_id" :role_id="xrole_id"  />
            </div>
        </div>
    </div>
</template>

<script>
    import AppUserListVue from './AppUserList.vue';
    import RoleListVue from './RoleList.vue';
    import ControlAccessListVue from './ControlAccessList.vue';
    export default {
        props:[  ],
        components: {
            "app-user-list":AppUserListVue,
            "role-list":RoleListVue,
            "control-access":ControlAccessListVue
        },
        data: function () {
            return {
                selected_app_user_id:0,
                xrole_id:0,
                branch_id:0
            }
        },
        methods: { 
            branch_changed:function(id){
                this.branch_id = id;
            },
            selected_app_user:function(id){
                this.selected_app_user_id = id;
                this.xrole_id = 0;
                /*
                var vm = this;
                setTimeout(()=>{
                    vm.selected_app_user_id = id;
                }, 100);
                */
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
