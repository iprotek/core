<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :extended_width="true">
            <template slot="header" >
                Own Branch
            </template> 
            <template slot="body" >     
                <user-input2 v-model="name" :input_style="'height:35px;'" :placeholder="'Branch name'" :placeholder_description="'A unique branch name.'" ></user-input2>
                <user-input2 v-model="address" :input_style="'height:35px;'" :placeholder="'Address'" :placeholder_description="'Address where branch located.'" ></user-input2>
                <user-input2 v-model="coordinates" :input_style="'height:35px;'" :placeholder="'Coordinates'" :placeholder_description="'Google geolocation'" ></user-input2>
                <user-input2 v-model="status" :input_style="'height:35px;'" :placeholder="'Status'" :placeholder_description="'Status(Active, Inactive, etc.).'" ></user-input2>
                <user-input2 v-model="status_info" :input_style="'height:35px;'" :placeholder="'Status Info'" :placeholder_description="'Details describing the status.'" ></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button v-if="billing_branch_id == 0" type="button" class="btn btn-outline-primary" @click="save">
                        <span class="fa fa-plus"></span> Add Branch
                    </button> 
                    <button v-else type="button" class="btn btn-outline-warning" @click="save">
                        <span class="fa fa-edit"></span> Save Branch
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../common/UserInput2.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {
                billing_branch_id:0,
                name:'',
                address:'',
                coordinates:'',
                status:'Active',
                status_info:''
           }
        },
        methods:{ 
            reset:function(){
                this.billing_branch_id = 0;
                this.name = '';
                this.address = '';
                this.coordinates = '';
                this.status = 'Active';
                this.status_info = '';
            },
            show:function(billing_branch_id = 0, billing_branch){
                this.reset();
                this.billing_branch_id = billing_branch_id; 
                this.$refs.modal.show();
                if(this.billing_branch_id > 0){
                    this.getBranchInfo(billing_branch);
                }

            },
            getBranchInfo:function(billing_branch){
                this.name = billing_branch.name;
                this.address = billing_branch.address;
                this.coordinates = billing_branch.coordinates;
                this.status = billing_branch.status;
                this.status_info = billing_branch.status_info;
            },
            save:function(){
                
                var vm = this;
                var req = {
                    billing_branch_id: this.billing_branch_id,
                    name: this.name,
                    address: this.address,
                    coordinates: this.coordinates,
                    status: this.status,
                    status_info: this.status_info
                }
                //return;
                var url = this.billing_branch_id == 0 ?"add-own":"update-own";
                this.$refs.swal_prompt.alert(
                    'question', 
                    this.billing_branch_id == 0 ? "Add Branch?":"Update Branch?", 
                    "Confirm" , 
                    this.billing_branch_id == 0 ? "POST":"PUT", 
                    "/api/group/"+this.group_id+"/branch/"+url, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.billing_branch_id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            },
            remove:function(billing_branch_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to remove this branch?", 
                    "Confirm" , 
                    "DELETE", 
                    "/api/group/"+this.group_id+"/branch/delete-own/"+billing_branch_id
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
