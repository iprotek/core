<template>
    <div>
        <div class="row" v-if="add_model == false">
            <div class="col-md-6"> 
                <models-view @show_add_model="show_add_model" :group_id="group_id" :is_data="is_data"></models-view>
            </div>
            <div class="col-md-6"> 
                <fields-view @modal_updated="modal_updated" :group_id="group_id" :is_data="is_data"></fields-view>
            </div>
        </div> 
        <div :style="'display:'+(add_model?'':'none')+';'" >
            <model-form @modal_updated="modal_updated" ref="model_form" :group_id="group_id" :is_data="is_data"></model-form>
        </div>
    </div>
</template>

<script>

    import FieldsVue from '../../../common/data/view/Fields.vue';
    import ModelsVue from '../../../common/data/view/Models.vue';
    import ModelFormVue from '../../../common/data/view/ModelForm.vue';
    export default {
        props:[ "group_id", "is_data" ],
        components: { 
            "fields-view":FieldsVue,
            "models-view":ModelsVue,
            "model-form":ModelFormVue
        },
        data: function () {
            return { 
                add_model:false
            }
        },
        methods: { 
            show_add_model:function(id = 0, modelInfo = null){ 
                this.add_model = true;
                this.$refs.model_form.show(id, modelInfo);
            },
            modal_updated:function(){
                this.add_model = false;
            }


        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
