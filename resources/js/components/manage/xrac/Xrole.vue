<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole/user-role-access"> USER ROLES </a>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <role-list :is_default_setting="true" />
            </div>
            <div class="col-md-8">
                <control-access />
            </div>
        </div>
    </div>
</template>

<script>
    import RoleListVue from './RoleList.vue';
    import ControlAccessListVue from './ControlAccessList.vue';
    export default {
        props:[  ],
        components: {
            "role-list":RoleListVue,
            "control-access":ControlAccessListVue
        },
        data: function () {
            return {    
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
