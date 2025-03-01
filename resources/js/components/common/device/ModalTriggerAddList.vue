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
                                        <select2 @selected="show_preview_selected" v-if="show_preview" :query_filters="{data_schema: target_name}" v-model="selected_preview" :has_clear="true" :modal_selector="true" :url="'/api/group/'+group_id+'/devices/dynamic-selection'" :placeholder="'--Select Source--'"  />
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
                                    <code>[account field="plan" data-json="data"]</code> - get the "plan" field value form target source model.
                                </div>
                                <div>
                                    <code>[account field="User Name" data-json="custom"]</code> - get the "User Name" field value form target json field custom.
                                </div>
                                <div>
                                    <code>[account field="User Name" data-model="contact"]</code> - get the "User Name" field value form target source custom model fields.
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
                                <div>
                                    <small class="text-primary">*First line of command should be registration check and getting id if exists</small>
                                </div>
                                <div>
                                    <small class="text-primary">*Second line of comamnd should be registration if not exists and getting the newly registered id</small>
                                </div>
                                <textarea 
                                    v-if="device_trigger_info.enable_register" 
                                    v-model="device_trigger_info.register_command_template" 
                                    class="form-control text-sm" 
                                    style="min-height:80px" 
                                    placeholder="Please input your text command"
                                    @change="loadPreview('register')"
                                ></textarea>
                                <div v-if="show_preview && device_trigger_info.enable_register && selected_preview.id > 0" >
                                    <code>Preview:</code>
                                    <div>
                                        <small class="text-primary" >
                                            <pre  v-text="register_command_template_preview"></pre>
                                        </small>
                                    </div>
                                </div>
                            
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
                                <textarea 
                                    v-if="device_trigger_info.enable_update" 
                                    v-model="device_trigger_info.update_command_template" 
                                    class="form-control text-sm" 
                                    style="min-height:80px" 
                                    placeholder="Please input your text command"
                                    @change="loadPreview('update')"
                                ></textarea>
                                <div v-if="show_preview && device_trigger_info.enable_update && selected_preview.id > 0" >
                                    <code>Preview:</code>
                                    <div>
                                        <small class="text-primary">
                                            <pre  v-text="update_command_template_preview"></pre>
                                        </small>
                                    </div>
                                </div>
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
                                <textarea 
                                    v-if="device_trigger_info.enable_active" 
                                    v-model="device_trigger_info.active_command_template" 
                                    class="form-control text-sm" 
                                    style="min-height:80px" 
                                    placeholder="Please input your text command"
                                    @change="loadPreview('active')"
                                ></textarea>
                                <div v-if="show_preview && device_trigger_info.enable_active && selected_preview.id > 0" >
                                    <code>Preview:</code>
                                    <div>
                                        <small class="text-primary">
                                            <pre  v-text="active_command_template_preview"></pre>
                                        </small>
                                    </div>
                                </div>
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
                                <textarea 
                                    v-if="device_trigger_info.enable_inactive" 
                                    v-model="device_trigger_info.inactive_command_template" 
                                    class="form-control text-sm" 
                                    style="min-height:80px" 
                                    placeholder="Please input your text command"
                                    @change="loadPreview('inactive')"
                                ></textarea>
                                <div v-if="show_preview && device_trigger_info.enable_inactive && selected_preview.id > 0" >
                                    <code>Preview:</code>
                                    <div>
                                        <small class="text-primary">
                                            <pre v-text="inactive_command_template_preview"></pre>
                                        </small>
                                    </div>
                                </div>
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
                                <textarea 
                                    v-if="device_trigger_info.enable_remove" 
                                    v-model="device_trigger_info.remove_command_template" 
                                    class="form-control text-sm" 
                                    style="min-height:80px" 
                                    placeholder="Please input your text command"
                                    @change="loadPreview('remove')"
                                ></textarea>
                                <div v-if="show_preview && device_trigger_info.enable_remove && selected_preview.id > 0" >
                                    <code>Preview:</code>
                                    <div>
                                        <small class="text-primary"  >
                                            <pre v-text="remove_command_template_preview"></pre>
                                        </small>
                                    </div>
                                </div>
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
                selected_preview:{
                    id:0,
                    text:' -- Select Preview -- '
                },
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
                },
                register_command_template_preview:'',
                update_command_template_preview:'',
                active_command_template_preview:'',
                inactive_command_template_preview:'',
                remove_command_template_preview:''

           }
        },
        methods:{ 
            reset:function(){
                this.show_preview = false;
                this.device_trigger_id = 0;
                this.selected_device = {
                    id:0,
                    text:' -- SELECT DEVICE -- '
                } 
                this.device_trigger_info = {
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
                };
            },

            show_preview_selected:function(val){

                //REGISTER
                this.loadPreview('register');

                //UPDATE
                this.loadPreview('update');

                //ACTIVE
                this.loadPreview('active');

                //INACTIVE
                this.loadPreview('inactive');

                //REMOVE
                this.loadPreview('remove');

            },

            loadPreview:function(command){
                
                var vm = this;
                var template = "";
                var command_url = "";
                if(command == 'register'){
                    command_url = 'register-account-preview';
                    template = this.device_trigger_info.register_command_template;
                    vm.register_command_template_preview = '';
                }
                else if(command == 'update'){
                    command_url = "update-account-preview";
                    template = vm.device_trigger_info.update_command_template;
                    vm.update_command_template_preview = '';
                    //return;
                }
                else if(command == 'active'){
                    command_url = "set-active-preview";
                    template = vm.device_trigger_info.active_command_template;
                    vm.active_command_template_preview = '';
                    //return;

                }
                else if(command == 'inactive'){
                    command_url = "set-inactive-preview";
                    template = vm.device_trigger_info.inactive_command_template;
                    vm.inactive_command_template_preview = '';
                    //return;
                }
                else if(command == 'remove'){
                    command_url = "remove-preview";
                    template = vm.device_trigger_info.remove_command_template;
                    vm.remove_command_template_preview = '';
                    //return;
                }
                else{
                    return;
                }

                if(!(this.selected_preview.id > 0 && this.show_preview && template)) return;

                var target_id = this.selected_preview.id;
                
                vm.active_command_template_preview = '';
                vm.inactive_command_template_preview = '';
                vm.remove_command_template_preview = '';

                WebRequest2('POST', '/api/group/'+this.group_id+'/devices/accounts/'+command_url, {
                    target_id: target_id,
                    target_name: this.target_name, 
                    template: template
                }).then(resp=>{
                    resp.json().then(data=>{
                        //console.log("Template Result", data, command);
                        if(data.status == 1){
                            if(command == 'register'){
                                vm.register_command_template_preview = data.template_translate;
                            }
                            else if(command == 'update')
                                vm.update_command_template_preview = data.template_translate;
                            else if(command == 'active')
                                vm.active_command_template_preview = data.template_translate;
                            else if(command == 'inactive')
                                vm.inactive_command_template_preview = data.template_translate;
                            else if(command == 'remove')
                                vm.remove_command_template_preview = data.template_translate;
                        }
                    });
                })
            },
            show:function(device_trigger_id = 0){ 
                var vm = this;
                vm.reset();

                this.$refs.modal.show();
                setTimeout(()=>{
                    vm.device_trigger_id = device_trigger_id;
                    if(vm.device_trigger_id){
                        vm.get_one();
                    }
                }, 50);

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
                        
                        vm.device_trigger_info = {
                            trigger_name: data.trigger_name,
                            is_active: data.is_active,
                            device_access_id: data.device_access_id,
                            
                            enable_register:data.enable_register,
                            register_command_template:data.register_command_template,

                            enable_update:data.enable_update,
                            update_command_template:data.update_command_template,

                            enable_active:data.enable_active,
                            active_command_template:data.active_command_template,

                            enable_inactive:data.enable_inactive,
                            inactive_command_template:data.inactive_command_template,

                            enable_remove:data.enable_remove,
                            remove_command_template:data.remove_command_template,
                            
                            target_name: vm.target_name,
                            target_id: vm.target_id
                        };
                    
                    
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
            },

            remove:function(trigger_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question',
                    "Remove DeviceTrigger?" , 
                    "Confirm" , 
                    "DELETE", 
                    '/api/group/'+this.group_id+'/devices/trigger/remove/'+trigger_id
                ).then(res=>{
                    if(res.isConfirmed && res.value.status == 1){
                        vm.$emit('data_updated');
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
