<template>
    <div>
        <group-selector @selected="selected_group" :is_right="true" :can_log_out="can_log_out ? true:false"   :can_set_default="true"></group-selector>
        <div class="row">
            <div class="col-sm-12 my-2">
                <button :class="'btn '+(view== 'groups-and-categories'? 'btn-primary':'btn-outline-primary')" @click="view = 'groups-and-categories'"> 
                    GROUPS AND CATEGORIES
                </button>
                <button :class="'btn '+(view== 'form-fields'? 'btn-primary':'btn-outline-primary')" @click="view = 'form-fields'" >
                    FORM FIELDS
                </button>
            </div>
        </div> 
        <groups-and-categories v-if="group_id && view=='groups-and-categories'" :group_id="group_id"></groups-and-categories>
        <form-fields v-if="group_id && view=='form-fields'" :group_id="group_id"></form-fields>
        <!--<custom-mail v-if="group_id" :group_id="group_id"></custom-mail>-->
        <smtp v-if="group_id" :group_id="group_id"></smtp>
    </div>
</template>

<script> 
    import GroupSelectorVue from '../GroupSelector.vue';  
    import CustomMailVue from './CustomMail.vue';
    import FormFieldsVue from './FormFields.vue';
    import GroupsAndCategoriesVue from './GroupsAndCategories.vue';
    import SmtpVue from './smtp/Smtp.vue';
    export default {
        props:[ "can_log_out" ],
        components: {  
            "group-selector":GroupSelectorVue, 
            "groups-and-categories":GroupsAndCategoriesVue,
            "form-fields":FormFieldsVue,
            "custom-mail":CustomMailVue,
            "smtp":SmtpVue
        },
        data: function () {
            return {    
                group_id:0,
                view:'groups-and-categories'
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
