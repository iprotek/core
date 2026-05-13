<template>
    <div>
        <div v-if="view == 'user-roles'">
            <xuser-role :theme_info="theme_info" :group_id="group_id" @role_click="view='role-defaults'" />
        </div>
        <div v-else>
            <xrole  :theme_info="theme_info" :group_id="group_id" @user_role_click="view='user-roles'" />
        </div>
    </div>
</template>

<script>
    import XroleVue from './Xrole.vue';
    import XuserRoleVue from './XuserRole.vue';
    export default {
        props:[ "theme_info", "group_id" ],
        components: { 
            "xrole":XroleVue,
            "xuser-role":XuserRoleVue
        },
        data: function () {
            return { 
                view:'user-roles'
            }
        },
        methods: { 
        },
        mounted:function(){  
        },
        updated:function(){

        }
    }
</script>
