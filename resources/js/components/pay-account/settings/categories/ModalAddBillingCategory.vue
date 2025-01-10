<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Category Name
            </template> 
            <template slot="body" >     
                <user-input2 v-model="category_name" :input_style="'height:35px;'" :placeholder="'Category Name'" :placeholder_description="'A unique category name'"></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <button v-if="billing_category_id == 0" type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Add Category
                    </button> 
                    <button v-else type="button" class="btn btn-outline-warning" @click="save">
                        <span class="fa fa-edit"></span> Save Category
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>
    import UserInput2Vue from '../../../common/UserInput2.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {        
                billing_category_id:0,
                category_name:''
           }
        },
        methods:{ 
            reset:function(){
                this.billing_category_id = 0;
                this.category_name = '';
            },
            show:function(billing_category_id=0, name =""){
                this.reset();
                this.billing_category_id = billing_category_id;  
                this.$refs.modal.show();
                if(billing_category_id){
                    this.category_name = name;
                }
            },
            save:function(){
                
                var vm = this;
                var req = {
                    billing_category_id: this.billing_category_id,
                    name: this.category_name,
                }
                //return;
                var url = this.billing_category_id == 0 ?"add":"update";
                

                this.$refs.swal_prompt.alert(
                    'question', 
                    this.billing_category_id == 0 ? "Add Category?":"Update Category?", 
                    "Confirm" , 
                    this.billing_category_id == 0 ? "POST":"PUT", 
                    "/api/group/"+this.group_id+"/settings/category/"+url, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.billing_category_id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            },
            remove:function(billing_category_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to remove this category?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/settings/category/delete/"+billing_category_id
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
