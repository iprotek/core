<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <button class="btn btn-primary" @click="$emit('cancel')">
                    <span class="fa fa-arrow-left"></span> BACK
                </button>
                <div class="card">
                    <!--
                    <div class="card-header">Add/Edit Data</div>-->
                    <div class="card-body">
                        <div v-if="id == 0">
                            <label>Model:</label>
                            <select2 @selected="selected" v-model="data_item" :has_clear="true" :url="'/manage/'+(is_data ? 'iprotek-data':'projects-monitoring')+'/model-fields/model/list-selection'" :placeholder="' -- Select Model -- '"></select2>
                            <div v-if="data_item.id <= 0">
                                <code>**Please select a model</code>
                            </div>
                            <div v-else>
                                <user-input2 v-model="name" :placeholder="'Name'" :placeholder_description="'A unique name for company, contact, etc.'" :input_style="'height:40px;'"></user-input2>
                                <user-input2 v-model="description" :placeholder="'Description'" :placeholder_description="'Any details, keywords, etc.'" :input_style="'height:40px;'" ></user-input2>
                                
                            </div>
                        </div>
                        <div v-else>
                            <label v-text="'Model: '+data_model_type"></label>
                            <user-input2 v-model="name" :placeholder="'Name'" :placeholder_description="'A unique name for company, contact, etc.'" :input_style="'height:40px;'"></user-input2>
                            <user-input2 v-model="description" :placeholder="'Description'" :placeholder_description="'Any details, keywords, etc.'" :input_style="'height:40px;'" ></user-input2>
                        </div>
                        <div v-if="data_item.id > 0" class="mt-1">
                            <button class="btn btn-outline-secondary btn-sm"  @click="$emit('cancel')"> CANCEL </button>
                            <button class="btn btn-outline-primary btn-sm" v-if="id == 0" @click="save_data"> ADD </button>
                            <button class="btn btn-outline-warning btn-sm" v-else @click="save_data"> UPDATE </button>
                            <div v-if="id>0" class="pt-3">
                                <label> <b> ** Data Info </b> </label>
                                <data-field-item  :group_id="group_id" :is_data="is_data" :data_id="id" v-for="(opt, optIndex) in fieldValues" v-bind:key="'data-field-item-'+optIndex+'-'+_uid"  v-model="fieldValues[optIndex]" ></data-field-item>
                                <project-list  :group_id="group_id" :is_data="is_data" v-if="data_model_type != 'project'" :data_id="id"></project-list>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
        <modal-add-data ref="modal_add_data"  :group_id="group_id" :is_data="is_data"></modal-add-data>
    </div>
</template>

<script>  
    import Select2Vue from '../../Select2.vue';
    import UserInput2Vue from '../../UserInput2.vue';
    import DataFieldItemVue from '../form/DataFieldItem.vue';
    import ModalAddDataVue from '../view/modal/ModalAddData.vue';//'../../../manage/projects-monitoring/searches/modal/ModalAddData.vue';
    import ProjectListVue from './ProjectList.vue';//'../../../manage/projects-monitoring/searches/ProjectList.vue'; 
    export default {
        props:[ "group_id", "is_data" ],
        components: { 
            "select2":Select2Vue,
            "user-input2":UserInput2Vue,
            "data-field-item":DataFieldItemVue,
            "modal-add-data":ModalAddDataVue,
            "project-list":ProjectListVue
        },
        data: function () {
            return {
                id:0,
                name:'',
                data_model_type:'',
                description:'',
                data_item:{
                    id:0,
                    text:''
                },
                fieldValues:[]
            }
        },
        methods: { 
            save_data:function(){
                var req = {
                    name:this.name,
                    details: this.description,
                    data_model_id: this.data_item.id,
                    data_model_type: this.data_item.type
                }
                var vm = this;
                vm.data_model_type = req.data_model_type;
 
                var data_src = "projects-monitoring";
                if(this.is_data){
                    data_src = "iprotek-data";
                }
                
                var title = "Add Data?";
                var url = "/manage/"+data_src+"/searches/data/add"
                if(this.id > 0){
                    title = "Update Data";
                    url = "/manage/"+data_src+"/searches/data/update/"+this.id
                }

                this.$refs.swal_prompt.alert(
                    'question', 
                    title, 
                    "Confirm" , 
                    this.id==0 ?"POST":"PUT", 
                    url, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.id = res.value.data.id;
                            vm.loadModel();
                        }
                    }
                });
            },
            reset:function(){
                this.id = 0;
                this.name = '';
                this.data_model_type = '';
                this.description = '';
                this.data_item = {
                    id:0,
                    text:''
                };
                this.fieldValues = [];

            },
            show:function(id=0, dataInfo=null){
                this.reset();
                var vm = this; 

                setTimeout(()=>{
                    vm.id = id;
                    vm.data_item = {
                        id:0,
                        text:''
                    }
                    //console.log(dataInfo);
                    if( vm.id>0 ){
                        vm.data_item.id = dataInfo.data_model_id;
                        vm.name = dataInfo.name;
                        vm.description = dataInfo.details;
                        vm.data_model_type = dataInfo.data_model_type;
                        vm.loadModel();
                    }
                }, 100);
            },
            loadModel:function(){
                ///manage/projects-monitoring/searches/data/get/1
                var vm = this;
                var data_src = "projects-monitoring";
                if(this.is_data){
                    data_src = "iprotek-data";
                }
                WebRequest2('GET', '/manage/'+data_src+'/searches/data/get/'+this.id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log( this.data_model_type, data);
                        vm.fieldValues = vm.fieldsFormatted( data.field_values );
                    })
                })
            },
            fieldsFormatted:function(fields){
                var fieldList = [];
                fields.forEach(field=>{
                    
                    if(!field.model_field) return;
                    
                    fieldList.push({
                        id: field.id,
                        name: field.model_field.name,
                        type: field.model_field.data_type,
                        fields: this.fieldsFormatted(field.fields),
                        data_model_id: field.data_model_id,
                        model_field_id: field.model_field_id,
                        order_no: field.order_no,
                        data_values: field.data_values
                    });
                });
                return fieldList;
            },
            selected:function(){
                if(this.data_item.id <= 0 || this.id > 0) return;

                
                var data_src = "projects-monitoring";
                if(this.is_data){
                    data_src = "iprotek-data";
                }
                //Get the format here..
                ///manage/projects-monitoring/model-fields/model/get/1
                WebRequest2('GET', '/manage/'+data_src+'/model-fields/model/get/'+this.data_item.id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                    })
                });


            }
        },
        mounted:function(){     
            window.ModalAddDataView = this.$refs.modal_add_data;
            //console.log("DataFormvue", this.group_id, this.is_data);
        },
        updated:function(){

        }
    }
</script>
