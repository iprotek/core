<template>
    <div> 
        <div class="row">
            <div class="col-sm-6">
                <form-field-table :group_id="group_id"></form-field-table>
            </div>
            <div class="col-sm-6">
                <custom-field-table :group_id="group_id"></custom-field-table>
            </div>
        </div>
    </div>
</template>

<script>  
    import FormFieldTableVue from './form-custom-field/FormFieldTableList.vue';
    import CustomFielTableListVue from './custom-fields/CustomFieldTableList.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "form-field-table":FormFieldTableVue,
            "custom-field-table":CustomFielTableListVue
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
