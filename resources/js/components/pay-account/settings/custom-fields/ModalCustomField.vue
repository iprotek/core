<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Custom Fields
            </template> 
            <template slot="body" >
                <label class="mt-2">Field Data Type:</label>
                <select class="form-control" v-model="data_type" :disabled="custom_field_id > 0">
                    <option value="textbox">TEXTBOX</option>
                    <option value="select">SELECT</option>
                    <option value="radio">RADIO</option>
                    <option value="checkbox">CHECKBOX</option>
                </select>
                <div>
                    <user-input2 v-model="name" :input_style="'height:40px;'" :placeholder="'Name'" :placeholder_description="'Descriptive name of the custom field'"></user-input2>
                    <user-input2 v-model="description" :input_style="'height:40px;'" :placeholder="'Description'" :placeholder_description="'Describing the custom field.'"></user-input2>
                    <div v-if="data_type == 'select' || data_type == 'radio' || data_type == 'checkbox'">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mt-2 mb-0">Options:</h4>
                            </div>
                            <div class="card-body pt-0">
                                <table class="w-100">
                                    <tr v-for="(optionItem,optionIndex) in options" v-bind:key="'option-'+optionIndex">
                                        <td>
                                            <user-input2 v-model="optionItem.value" :input_style="'height:40px;'" :placeholder="'Option#'+(optionIndex+1)"></user-input2>
                                        </td>
                                        <td style="width:50px; vertical-align:bottom;">
                                            <button class="btn btn-outline-danger" @click="remove_option(optionItem)">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <div class="mt-2">
                                    <button class="btn btn-outline-primary" @click="add_option">
                                        <span class="fa fa-plus"></span> Add Options
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button v-if="custom_field_id == 0" type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Add Custom Field
                    </button>
                    <button v-else type="button" class="btn btn-outline-warning" @click="save">
                        <span class="fa fa-edit"></span> Update Custom Field
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../../common/UserInput2.vue';
import CustomFieldTableListVue from './CustomFieldTableList.vue';
    //import SwalAlertVue from '../../../common/SwalAlert.vue';
    export default {
        props:[ "group_id" ],
        components: {   
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {        
                custom_field_id:0,
                data_type:'textbox',
                name:'',
                description:'',
                options:[]
           }
        },
        methods:{ 
            reset:function(){
                this.name = '';
                this.description = '';
                this.data_type = 'textbox';
                this.custom_field_id = 0;
                this.options = [];
            },
            show:function(custom_field_id=0){ 
                this.reset();
                this.custom_field_id = custom_field_id;
                this.$refs.modal.show();
                if(custom_field_id){
                    this.getFieldInfo();
                }
            },
            getFieldInfo:function(){
                WebRequest2('GET', "/api/group/"+this.group_id+"/settings/custom-field/get/"+this.custom_field_id ).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        this.name = data.name;
                        this.data_type = data.data_type;
                        this.description = data.description;
                        var options = [];
                        var opt = JSON.parse(data.options);
                        if(opt){
                            opt.forEach(op=>{
                                options.push({value:op});
                            });

                        }

                        this.options = options;


                    });
                });
            },
            add_option:function(){
                var options = this.options;
                options.push({value:''});
                this.options = options;
            },
            remove_option:function(option){
                var options = this.options.filter(a=>a!=option);
                this.options = options;
            },
            save:function(){
                var vm = this;
                var options = [];
                this.options.forEach((item)=>{
                    options.push(item.value);
                });
                var req = {
                    custom_field_id: this.custom_field_id,
                    name: this.name,
                    description: this.description,
                    data_type: this.data_type,
                    options: options
                }
                var url = this.custom_field_id == 0 ?"add":"update";

                this.$refs.swal_prompt.alert(
                    'question', 
                    this.custom_field_id == 0 ? "Add Custom Field?":"Update Custom Field?", 
                    "Confirm" , 
                    this.custom_field_id == 0 ? "POST":"PUT", 
                    "/api/group/"+this.group_id+"/settings/custom-field/"+url, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.custom_field_id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 

            },
            remove:function(custom_field_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Remove this custom field?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/settings/custom-field/delete/"+custom_field_id
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
