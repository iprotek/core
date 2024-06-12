<template>
    <div id="modal_model_vue">
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                AddField
            </template> 
            <template slot="body" >   
                <select2 v-model="field_info"  :has_clear="true" :url="'/manage/projects-monitoring/model-fields/field/list-selection'" :modal_selector="'#modal_model_vue .modal-body'" :placeholder="'-- Select Field --'"></select2>   
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" @click="add_click" >ADD</button> 
                </div>
            </template>
        </modal-view>  
        <swal-alert ref="swal_alert"></swal-alert>
    </div> 

</template>

<script>
    import Select2Vue from '../../../../common/Select2.vue';
    import SwalAlertVue from '../../../../common/SwalAlert.vue';
    export default {
        props:[  ],
        components: { 
            "select2":Select2Vue,
            "swal-alert":SwalAlertVue
        },
        data: function () {
            return {
                data_model_id:0,
                cback:null,
                field_info:{
                    id:0,
                    text:''
                }     
           }
        },
        methods:{ 
            reset:function(){
                this.field_info = {
                    id:0,
                    text:''
                };
            },
            show:function(cback){ 
                this.reset();
                this.cback = cback;
                this.$refs.modal.show();
            },
            add_click:function(){
                this.field_info.data_model_id = this.data_model_id;
                console.log(this.field_info);
                if(this.field_info.id <=0)
                {
                    this.$refs.swal_alert.error("Please select a field");
                    return;
                }
                this.cback(this.field_info);
                this.$refs.modal.dismiss();
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
