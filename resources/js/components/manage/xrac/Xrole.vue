<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole/user-role-access"> USER ROLES </a>
        <div class="row">
            <div class="col-md-4">
                <role-list :is_default_setting="true" @selection_changed="role_selection" />
            </div>
            <div v-if="role_id" class="col-md-8">
                <control-access :is_default_setting="true" :role_id="role_id" />
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
                role_id:0,
            }
        },
        methods: { 
            role_selection:function(id){
                var vm = this;
                vm.role_id = 0;
                setTimeout(()=>{
                    vm.role_id = id;
                }, 100);
            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
