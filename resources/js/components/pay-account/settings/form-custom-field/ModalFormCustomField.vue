<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" >
            <template slot="header" >
                 Custom Field
            </template> 
            <template slot="body">
                <div class="mt-4">  
                    <label>Select Custom Field:</label>  
                    <select2 :modal_selector="'#modal-form-custom-field .modal-body'" v-model="item_custom_field" @selected="selectedCustomField" :placeholder="' -- SELECT CUSTOM FIELD -- '"  :url="'/api/group/'+group_id+'/settings/custom-field/list-selection'" ></select2>
                    <div>
                        <div>
                            <label>ADD CONTAINER</label>
                        </div>
                        <button class="btn btn-outline-primary btn-sm">
                            <span class="fa fa-plus"></span> ADD CONTAINER
                        </button>
                    </div>
                    <label>Preview:</label>
                    <div></div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button  type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Select Custom Field
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>     
    import Select2Vue from '../../../common/Select2.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "select2":Select2Vue
        },
        data: function () {
            return { 
                item_custom_field:{
                    id:0,
                    text:' -- SELECT CUSTOM FIELD --'
                }
           }
        },
        methods:{ 
            selectedCustomField:function(val){
                console.log(val);
            },
            reset:function(){ 

            },
            show:function( ){
                this.$refs.modal.show();
            },
            save:function(){

            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
