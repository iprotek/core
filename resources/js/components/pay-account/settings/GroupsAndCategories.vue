<template>
    <div> 
        <div class="row" v-if="group_id">
            <div class="col-sm-6">
                <group-content :group_id="group_id"></group-content>
            </div>
            <div class="col-sm-6">
                <category-content :group_id="group_id"></category-content>
            </div>
        </div> 
    </div>
</template>

<script> 
    import CategoryContentVue from './categories/CategoryContent.vue';
    import GroupContentVue from './groups/GroupContent.vue';
    export default {
        props:[ "group_id" ],
        components: {  
            "group-content":GroupContentVue,
            "category-content":CategoryContentVue
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
