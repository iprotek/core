<template>
    <div>
        <group-selector @selected="selected_group" :show_owned_group="true"></group-selector> 
        <branch-content v-if="group_id>0" :group_id="group_id"></branch-content>
    </div>
</template>

<script> 
    import GroupSelectorVue from '../GroupSelector.vue';  
    import BranchContentVue from './BranchContent.vue';
    export default {
        props:[  ],
        components: {  
            "group-selector":GroupSelectorVue,
            "branch-content":BranchContentVue
        },
        data: function () {
            return {     
                group_id:0
            }
        },
        methods: { 
            selected_group:function(val){
                
                var vm = this;
                this.group_id = 0;
                setTimeout(()=>{
                    vm.group_id = val.id;
                }, 50);
                
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
