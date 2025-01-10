<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">Form</div>
                    <div class="card-body pt-0">
                        <user-input2 v-model="name" :input_style="'height:40px;'" :placeholder="'Name'" :placeholder_description="'Descriptive name of the form'"></user-input2>
                        <user-input2 v-model="description" :input_style="'height:40px;'" :placeholder="'Description'" :placeholder_description="'Describing the form.'"></user-input2>
                        <div class="card mt-4">
                            <div class="card-header">Content</div>
                            <div class="card-body">
                                <div>

                                </div>
                                <div>
                                    <button class="btn btn-outline-primary" @click="$refs.modal_form_custom_field.show(0)">
                                        <span class="fa fa-plus"></span> Add Custom Field
                                    </button>
                                </div>

                                
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" v-if="form_custom_field_id == 0 || !form_custom_field_id">
                                <span class="fa fa-plus"></span> ADD
                            </button>
                            <button class="btn btn-outline-warning" v-else>
                                <span class="fa fa-save"></span> UPDATE
                            </button>
                            <button class="btn btn-outline-secondary">
                                CANCEL
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-form-custom-field :group_id="group_id" ref="modal_form_custom_field" id="modal-form-custom-field" name="modal-form-custom-field"></modal-form-custom-field>
    </div>
</template>

<script>
    import UserInput2Vue from '../../../common/UserInput2.vue'
    import ModalFormCustomFieldVue from './ModalFormCustomField.vue'
    export default {
        props:[ "group_id", "form_custom_field_id" ],
        components: { 
            "user-input2":UserInput2Vue,
            "modal-form-custom-field":ModalFormCustomFieldVue
        },
        data: function () {
            return {
                name:'',
                description:'',
                content:'' 
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
