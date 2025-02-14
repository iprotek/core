<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                <label v-if="device_trigger_id == 0">ADD TRIGGER</label>
                <label v-else>UPDATE TRIGGER</label>
            </template> 
            <template slot="body" >     
                <div class="row">
                    <div class="col-sm-5">
                        
                        <user-input2 v-model="device_trigger_info.trigger_name" :placeholder="'Trigger Name'" :input_style="'height:40px;'" />
                        <div class="mt-2">
                            <label class="mb-0" > Device ACCES ID </label>
                        </div>
                        <select2 v-model="selected_device" :query_filters="{only_active:'yes'}"   :placeholder="'-- SELECT DEVICE --'" :url="'/api/group/'+group_id+'/devices/list-selection?only_active=yes'" :has_clear="true"  />
                        <div class="mt-1">
                            <switch2 v-model="device_trigger_info.is_active" /> <label class="mb-0"> IS ACTIVE </label>
                        </div>
                        <div class="mt-1" v-if="!device_trigger_info.is_active">
                            <textarea class="form-control" style="min-height:50px;" placeholder="Inactive trigger reason." v-model="device_trigger_info.inactive_reason"></textarea>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <b>
                                    DYNAMIC VARIABLES
                                </b>
                            </div>
                            <div class="card-body text-sm">
                                <div class="mb-4">
                                    <div class="mt-1">
                                        <switch2 v-model="show_preview" /> <label class="mb-0">SHOW PREVIEW </label>
                                    </div>
                                    <div v-if="show_preview" class="mt-1">
                                        <label class="mb-1">SELECT SOURCE</label>
                                    </div>
                                </div>
                                <small class="text-primary">*use this dynamic variable to replace your command on the current instance value.</small>
                                <div>
                                    <code>[account field="id" ]</code> - get the data id
                                </div>
                                <div>
                                    <code>[device_account_id]</code> - get the account id from the device upon registration.
                                </div>
                                <div>
                                    <code>[account field="plan" source="data"]</code> - get the "plan" field value form target source model.
                                </div>
                                <div>
                                    <code>[account field="User Name" source="custom"]</code> - get the "User Name" field value form target source custom fields.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card mt-2">

                            <div class="card-header text-center p-1">
                                <small> <b>
                                REGISTER / NEW ENTRY TRIGGER COMMAND
                                </b></small>
                            </div>
                            <div class="card-body p-1">
                                <div class="pb-1">
                                    <small>
                                        <icheck :checked="device_trigger_info.enable_register" @update:checked="(is_checked)=>{ device_trigger_info.enable_register = is_checked; }" :label="'Enable Register / New Entry'" />
                                    </small>
                                </div>
                                <textarea v-if="device_trigger_info.enable_register" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header text-center p-1">
                                <small> <b>
                                UPDATE TRIGGER COMMAND
                                </b></small>
                            </div>
                            <div class="card-body p-1">
                                <div class="pb-1">
                                    <small>
                                    <icheck :checked="device_trigger_info.enable_update" @update:checked="(is_checked)=>{ device_trigger_info.enable_update = is_checked; }" :label="'Enable Updates'" />
                                    </small>
                                </div>
                                <textarea v-if="device_trigger_info.enable_update" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header text-center p-1">
                                <small> <b>
                                ACTIVE TRIGGER COMMAND
                                </b></small>
                            </div>
                            <div class="card-body p-1">
                                <div class="pb-1">
                                    <small>
                                    <icheck :checked="device_trigger_info.enable_active" @update:checked="(is_checked)=>{ device_trigger_info.enable_active = is_checked; }" :label="'Enable ACTIVE'" />
                                    </small>
                                </div>
                                <textarea v-if="device_trigger_info.enable_active" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header text-center p-1">
                                <small> <b>
                                INACTIVE TRIGGER COMMAND
                                </b></small>
                            </div>
                            <div class="card-body p-1">
                                <div class="pb-1">
                                    <small>
                                    <icheck :checked="device_trigger_info.enable_inactive" @update:checked="(is_checked)=>{ device_trigger_info.enable_inactive = is_checked; }" :label="'Enable Inactive'" />
                                    </small>
                                </div>
                                <textarea v-if="device_trigger_info.enable_inactive" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                           </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header text-center p-1">
                                <small> <b>
                                DELETE TRIGGER COMMAND
                                </b></small>
                            </div>
                            <div class="card-body p-1">
                                <div class="pb-1">
                                    <small>
                                    <icheck :checked="device_trigger_info.enable_remove" @update:checked="(is_checked)=>{ device_trigger_info.enable_remove = is_checked; }" :label="'Enable Delete/Removal'" />
                                    </small>
                                </div>
                                <textarea v-if="device_trigger_info.enable_remove" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                            </div>
                        </div>
                    </div>
                </div>  
            
            </template>
            <template slot="footer">
                <div>
                    <button v-if="device_trigger_id == 0" @click="save" class="btn btn-outline-primary"  >
                        <span class="fa fa-plus"></span> ADD
                    </button> 
                    <button v-else type="button" class="btn btn-outline-primary"  @click="save" >
                        <span class="fa fa-save"></span> SAVE
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>
    import UserInput2Vue from '../UserInput2.vue'; 
    import Select2Vue from '../Select2.vue';
    import iCheckVue from '../iCheck.vue';
    import BoostrapSwitch2Vue from '../BoostrapSwitch2.vue';
    export default {
        props:[ "group_id", "target_name", "target_id" ],
        components: { 
            "user-input2":UserInput2Vue,
            "select2":Select2Vue,
            "icheck":iCheckVue,
            "switch2":BoostrapSwitch2Vue
        },
        watch: { 
        },
        data: function () {
            return {
                promiseExec:null,
                show_preview:false,
                device_trigger_id:0,
                selected_device:{
                    id:0,
                    text:' -- SELECT DEVICE -- '
                },
                device_trigger_info:{
                    trigger_name:'',
                    is_active:true,
                    device_access_id:0,
                    
                    enable_register:false,
                    register_command_template:'',

                    enable_update:false,
                    update_command_template:'',

                    enable_active:false,
                    active_command_template:'',

                    enable_inactive:false,
                    inactive_command_template:'',

                    enable_remove:false,
                    remove_command_template:'',
                    
                    target_name: this.target_name,
                    target_id: this.target_id
                }
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(device_trigger_id = 0){ 
                var vm = this;
                vm.device_trigger_id = device_trigger_id;

                this.$refs.modal.show();
                if(vm.device_trigger_id){
                    this.get_one();
                }

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            get_one:function(){
                var vm = this;
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/trigger/get-one?id='+this.device_trigger_id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.selected_device = data.device_access;
                        
                        //TODO:: Update selected fields
                    
                    
                    })
                });
            },
            save:function(){
                var vm = this;
                //'/api/group/'+this.group_id+'/devices/trigger/add'
                var request = this.device_trigger_info;
                request.id = this.device_trigger_id;
                request.device_access_id = this.selected_device.id ? this.selected_device.id : ''
                //vm.device_access_id = this.selected_device.id ? this.selected_device.id : ''

                //console.log("REQUEST: ",request, this.selected_device);
                //return;
                
                this.$refs.swal_prompt.alert(
                    'question',
                    this.device_trigger_id? "Update Trigger?":"Add Trigger?" , 
                    "Confirm" , 
                     this.device_trigger_id ? "PUT":"POST", 
                    '/api/group/'+this.group_id+'/devices/trigger/'+(this.device_trigger_id ? 'update' : 'add'), 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed && res.value.status == 1){
                        vm.$emit('data_updated');
                        vm.device_trigger_id = res.value.data.id;

                    }
                });
                
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
