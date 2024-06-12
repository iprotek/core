<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Models</div> 
                    <div class="card-body">
                        <div>
                            <div v-if="id == 0">
                                <label>Select Model:</label>
                                <select class="form-control" v-model="name">
                                    <option value="project">Project</option>
                                    <option value="contact">Contact</option>
                                </select> 
                            </div>
                            <div v-else>
                                 <label >Model: </label> <span v-text="name"></span>
                            </div>
                            <label class="mt-2">Description:</label>
                            <input  v-model="details" class="form-control mb-2" placeholder="Description of the field."/>
                            <label>Fields:</label>
                            <button class="btn btn-outline-success btn-sm" @click="addField">
                                <span class="fa fa-plus"></span> Add Field
                            </button>
                            <div>
                                <field-item v-for="(opt, optIndex) in fieldList" v-bind:key="'field-item-'+optIndex+'-'+_uid" :has_down="optIndex < fieldList.length - 1" v-model="fieldList[optIndex]" @move_up="move_up(opt);" @move_down="move_down(opt);" @remove_item="remove_item(opt);"></field-item>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div>
                            <button type="button" class="btn btn-outline-dark mr-4"  @click="$emit('modal_updated')">Close</button> 
                            <button type="button" class="btn btn-outline-secondary mr-4" v-if="id > 0" @click="reset()">NEW</button> 
                            <button type="button" class="btn btn-outline-primary" v-if="id == 0" @click="save">Save</button> 
                            <button type="button" class="btn btn-outline-warning" v-else @click="save">Update</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
        <modal-model-add-vue ref="modal_model_add_vue" @add_field_click="add_field_click"></modal-model-add-vue>
        <swal-alert ref="swal_alert"></swal-alert>
    </div>
</template>

<script> 
    import ModalModelAddField from  '../form/Modals/ModalModelAddField.vue';//'./Modals/ModalModelAddField.vue';
    import FieldItemVue from './FieldItem.vue';
    import SwalAlertVue from '../../../common/SwalAlert.vue';
    export default {
        props:[  ],
        components: { 
            "field-item":FieldItemVue,
            "modal-model-add-vue":ModalModelAddField,
            "swal-alert":SwalAlertVue
        },
        data: function () {
            return { 
                id:0,
                name:'project',
                details:'',
                fieldList:[]
            }
        },
        methods: { 
            add_field_click:function(){

            },
            remove_item:function(item){

                this.$refs.swal_alert.confirm("Remove this field? Make sure it doesn't exist or it can greatly affect your data.", "Yes", "Cancel").then(res=>{
                    if(res.isConfirmed){
                        var order_no = 1;
                        this.fieldList = this.fieldList.filter(a=>a!= item).filter(b=>{
                            b.order_no = order_no;
                            order_no++;
                            return true;
                        }); 
                    }
                });

            },
            move_up:function(item){
                var order_no = item.order_no - 1; 
                //Swap 1
                var swap1 =  this.fieldList.filter(a=>a.order_no == order_no)[0];
                if(!swap1) return; 

                //Swap 2
                var swap2 = this.fieldList.filter(a=>a.order_no == item.order_no)[0];
                if(!swap2) return; 

                swap1.order_no = order_no+1;
                swap2.order_no = order_no;

                this.fieldList = this.fieldList.sort((a,b)=>{
                    return a.order_no - b.order_no;
                });
 
            },
            move_down:function(item){
               
                var order_no = item.order_no + 1; 
                //Swap 1
                var swap1 =  this.fieldList.filter(a=>a.order_no == order_no)[0];
                if(!swap1) return; 

                //Swap 2
                var swap2 = this.fieldList.filter(a=>a.order_no == item.order_no)[0];
                if(!swap2) return; 

                swap1.order_no = order_no-1;
                swap2.order_no = order_no;

                this.fieldList = this.fieldList.sort((a,b)=>{
                    return a.order_no - b.order_no;
                });

            },
            addField:function(){
                //Modal SHow
                window.ModalModelAddFieldView.data_model_id = this.id;
                window.ModalModelAddFieldView.show(this.field_add);
            },
            field_add:function(field){
                var order_no = this.fieldList.length + 1;
                this.fieldList.push({
                    id:0, 
                    name:field.name,
                    type:field.data_type,
                    fields:[],
                    data_model_id:field.data_model_id,
                    model_field_id:field.id,
                    order_no: order_no
                });
            },
            reset:function(){
                this.id = 0;
                this.name = 'project';
                this.details = '';
                this.fieldList = [];
            },
            show:function(id=0, modelInfo=null){ 
                this.reset();
                this.id = id; 
                if(this.id > 0){
                    this.name = modelInfo.name;
                    this.details = modelInfo.details;
                }
                //Load modelInfos and fields
                var vm = this;
                if(id>0){
                    WebRequest2('GET', '/manage/projects-monitoring/model-fields/model/get/'+this.id).then(resp=>{
                        resp.json().then(data=>{
                            //console.log(data);
                            vm.id = data.id;
                            vm.name = data.name;
                            vm.details = data.details;

                            vm.fieldList = vm.fieldsFormatted(data.fieldList);

                            //Fix Field List

                        });
                    });
                }
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
                        order_no: field.order_no
                    });
                });
                return fieldList;
            },

            save:function(){
                var type = this.name;
                if(type != 'contact' && type != 'project'){
                    type = 'custom';
                }


                var request = {
                    name: this.name, 
                    details: this.details,
                    fields:this.fieldList,
                    type: type
                };

                console.log(request);

                var title = "Add model?";
                var url = "/manage/projects-monitoring/model-fields/model/add"
                if(this.id > 0){
                    title = "Update model";
                    url = "/manage/projects-monitoring/model-fields/model/update/"+this.id
                }

                this.$refs.swal_prompt.alert(
                    'question', 
                    title, 
                    "Confirm" , 
                    this.id==0 ?"POST":"PUT", 
                    url, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            this.$emit('modal_updated');
                        }
                    }
                }); 


            },
            remove:function(id, fieldInfo){

                this.$refs.swal_prompt.alert(
                    'question', 
                    "Remove this field:"+fieldInfo.name+"? This will affect all your model.", 
                    "Confirm" , 
                    "DELETE", 
                    "/manage/projects-monitoring/model-fields/field/"+id
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            this.$emit('modal_updated');
                        }
                    }
                }); 

            }
        },
        mounted:function(){   
            window.ModalModelAddFieldView = this.$refs.modal_model_add_vue;
        },
        updated:function(){

        }
    }
</script>
