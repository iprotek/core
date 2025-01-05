<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                <label v-if="role_info.id">
                    EDIT ROLE
                </label>
                <label v-else>
                    ADD ROLE
                </label>
            </template> 
            <template slot="body" >  
                <user-input2 v-if="role_info.id" :readonly="true" :value="role_info.id" :placeholder="'RoleID#'"  :input_style="'height:40px;'" :placeholder_description="'Unique id of the role'"/>   
                <user-input2 v-model="role_info.name" :placeholder="'Role Name'"  :input_style="'height:40px;'" :placeholder_description="'Unique role name assigned to a user'"/>
                <validation :errors="errors" :field="'name'" />
                <user-input2 v-model="role_info.description" :placeholder="'Description info about the role'" :placeholder_description="'Role summary info and its uses.'"  :input_style="'height:40px;'"/>
                <div class="mt-3">
                    <switch2 v-model="role_info.is_active" :off_color="'red'" /> Is Active 
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button v-if="role_info.id" type="button" class="btn btn-outline-danger mr-4" @click="removeRole()" >
                        <span class="fa fa-trash"></span>
                        REMOVE THIS ROLE
                    </button>

                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    
                    <button v-if="!role_info.id" type="button" class="btn btn-outline-primary"  @click="$refs.update_role.submit()">
                        <web-submit ref="update_role" :action="addOrUpdate" :icon_class="'fa fa-plus'" :label="'ADD & SYNC'" :timeout="3000" />
                    </button> 
                    <button v-else type="button" class="btn btn-outline-warning" @click="$refs.add_role.submit()"> 
                        <web-submit ref="add_role" :action="addOrUpdate" :icon_class="'fa fa-save'" :label="'SAVE & SYNC'" :timeout="3000" />
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>
    import BoostrapSwitch2Vue from '../../../common/BoostrapSwitch2.vue';
    import UserInput2Vue from '../../../common/UserInput2.vue';
    import ValidationVue from '../../../common/Validation.vue';
    import WebSubmitVue from '../../../common/WebSubmit.vue';
    export default {
        props:[  ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "user-input2":UserInput2Vue,
            "web-submit":WebSubmitVue,
            "validation":ValidationVue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                role_info:{
                    id:0,
                    name:'',
                    description:'',
                    is_active:true
                },
                errors:[]
           }
        },
        methods:{ 
            reset:function(){
                this.role_info.id = 0;
                this.role_info.name = '';
                this.role_info.description = '';
                this.role_info.is_active = true;
            },
            addOrUpdate:function(){
                var vm = this;
                vm.errors = [];
                var req = {
                    id: this.role_info.id,
                    name: this.role_info.name,
                    description: this.role_info.description,
                    is_active: this.role_info.is_active
                }
                if(!req.id)
                    return WebRequest2('POST', '/manage/xrac/role/sync-add', JSON.stringify(req)).then(resp=>{
                        return resp.json().then(data=>{
                            if(!resp.ok){
                                vm.errors = data;
                                return;
                            }
                            if(data.status == 1){
                                setTimeout(()=>{
                                    vm.role_info.id = data.role.id;
                                }, 2000);
                            }
                            vm.$emit('modal_updated');
                            return data;
                        })
                    });
                
                return WebRequest2('POST', '/manage/xrac/role/sync-update', JSON.stringify(req)).then(resp=>{
                    return resp.json().then(data=>{
                        if(!resp.ok){
                            vm.errors = data;
                            return;
                        }
                        vm.$emit('modal_updated');
                        return data;
                    })
                });
            },
            removeRole:function(){
                var vm = this; 
                this.$refs.swal_prompt.alert(
                    'question',
                    "Remove this Role?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/xrac/role/sync-remove", 
                    JSON.stringify({
                        id: vm.role_info.id
                    })
                ).then(res=>{
                    if(res.isConfirmed){
                        vm.$emit('modal_updated');
                        setTimeout(()=>{
                            vm.$refs.modal.dismiss();
                        }, 1000);
                    }
                }); 

            },
            show:function(id=0, role_info=null){ 
                var vm = this;
                vm.reset();

                if(id && role_info){
                    ///console.log(id, role_info);
                    this.role_info.id = role_info.id;
                    this.role_info.description = role_info.description;
                    this.role_info.is_active = role_info.is_active;
                    this.role_info.name = role_info.name;
                }

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            add:function(){
                var vm = this;
                /*
                    this.$refs.swal_prompt.alert(
                        'question',
                        "Add Event", 
                        "Confirm" , 
                        "POST", 
                        "/manage/dashboard/resort-events/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            vm.$emit('data_updated');
                        }
                    });
                */
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
