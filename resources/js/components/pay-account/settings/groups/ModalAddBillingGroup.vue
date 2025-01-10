<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Group Name
            </template> 
            <template slot="body" >     
                <user-input2 v-model="group_name" :input_style="'height:35px;'" :placeholder="'Group Name'" :placeholder_description="'A unique group name'"></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <button v-if="billing_group_id == 0" type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Add Group
                    </button> 
                    <button v-else type="button" class="btn btn-outline-warning" @click="save">
                        <span class="fa fa-edit"></span> Save Group
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
                billing_group_id:0,
                group_name:''
           }
        },
        methods:{ 
            reset:function(){
                this.billing_group_id = 0;
                this.group_name = '';
            },
            show:function(billing_group_id=0, name =""){
                this.reset();
                this.billing_group_id = billing_group_id;  
                this.$refs.modal.show();
                if(billing_group_id){
                    this.group_name = name;
                }
            },
            save:function(){
                
                var vm = this;
                var req = {
                    billing_group_id: this.billing_group_id,
                    name: this.group_name,
                }
                //return;
                var url = this.billing_group_id == 0 ?"add":"update";
                

                this.$refs.swal_prompt.alert(
                    'question', 
                    this.billing_group_id == 0 ? "Add Group?":"Update Group?", 
                    "Confirm" , 
                    this.billing_group_id == 0 ? "POST":"PUT", 
                    "/api/group/"+this.group_id+"/settings/group/"+url, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.billing_group_id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            },
            remove:function(billing_group_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to remove this group?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/settings/group/delete/"+billing_group_id
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
