<template>
    <div>
        <modal-view ref="modal" :prevent="true" :extended_width="true" :body_class="'pt-0'">
            <template slot="header" >
               <span v-if="id == 0">Add Field</span>
               <span v-else>Update Field</span>
            </template> 
            <template slot="body" >     
                <user-input2 v-model="name" :type="'text'"  :placeholder="'Name'" :placeholder_description="'Please add a unique name of field'" :input_style="'height:40px;'"></user-input2>
                <label class="mt-2">Type:</label>
                <select class="form-control" v-model="type" :disabled="id>0">
                    <option value="list">List</option>
                    <option value="text">Text</option>
                    <option value="bool">Bool</option>
                    <option value="container">Container</option>
                    <option value="date">Date</option>
                </select>
                <label class="mt-2">Description:</label>
                <textarea  v-model="details" class="w-100" style="min-height:180px;" placeholder="Description of the field.">

                </textarea>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-secondary mr-4" v-if="id > 0" @click="reset()">NEW</button> 
                    <button type="button" class="btn btn-outline-primary" v-if="id == 0" @click="save">Save</button> 
                    <button type="button" class="btn btn-outline-warning" v-else @click="save">Update</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {
            "user-input2": UserInput2Vue
        },
        data: function () {
            return {
                id:0,
                name:'',
                type:'list',
                details:''
           }
        },
        methods:{ 
            reset:function(){
                this.id = 0;
                this.name = '';
                this.type = 'list';
                this.details = '';
            },
            show:function(id=0, fieldInfo){ 
                this.reset();
                this.id = id;
                this.$refs.modal.show();
                if(this.id > 0){
                    this.name = fieldInfo.name;
                    this.type = fieldInfo.data_type;
                    this.details = fieldInfo.details;
                }
            },
            save:function(){
                var vm = this;
                var request = {
                    name: this.name,
                    data_type: this.type,
                    details: this.details
                };
                var title = "Add field?";
                var url = "/manage/projects-monitoring/model-fields/field/add"
                if(this.id > 0){
                    title = "Update Field";
                    url = "/manage/projects-monitoring/model-fields/field/update/"+this.id
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
                            //console.log("Data",res.value);
                            vm.id = res.value.data.id;
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
        },
        updated:function(){

        }
    }
</script>
