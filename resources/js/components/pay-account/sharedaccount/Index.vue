<template>
    <div>
        <group-selector @selected="selected_group" :show_owned_group="true"></group-selector>
        <shared-account v-if="group_id" :group_id="group_id" ></shared-account>
    </div>
</template>

<script> 
    import GroupSelectorVue from '../GroupSelector.vue'; 
    import SharedAccountContentVue from './SharedAccountContent.vue';
    export default {
        props:[],
        components: {  
            "group-selector":GroupSelectorVue, 
            "shared-account":SharedAccountContentVue
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
