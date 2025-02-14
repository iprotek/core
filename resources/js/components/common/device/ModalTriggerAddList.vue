<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                ADD Trigger
            </template> 
            <template slot="body" >     
                <div class="row">
                    <div class="col-sm-5">
                        
                        <user-input2 :placeholder="'Trigger Name'" :input_style="'height:40px;'" />
                        <div class="mt-2">
                            <label class="mb-0" > Device Trigger </label>
                        </div>
                        <select2 :query_filters="{only_active:'yes'}"   :placeholder="'-- SELECT DEVICE --'" :url="'/api/group/'+group_id+'/devices/list-selection?only_active=yes'" :has_clear="true"  />
                        <div class="mt-1">
                            <switch2 v-model="is_active" /> <label class="mb-0"> IS ACTIVE </label>
                        </div>
                        <div class="mt-1" v-if="!is_active">
                            <textarea class="form-control" style="min-height:50px;" placeholder="Inactive trigger reason."></textarea>
                        </div>
                        <div class="mt-1">
                            <switch2 v-model="show_preview" /> <label class="mb-0">SHOW PREVIEW </label>
                        </div>
                        <div v-if="show_preview" class="mt-1">
                            <label class="mb-1">SELECT SOURCE</label>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                DYNAMIC VARIABLE DETAILS
                            </div>
                            <div class="card-body text-sm">
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
                                        <icheck :checked="enable_register" @update:checked="(is_checked)=>{ enable_register = is_checked; }" :label="'Enable Register / New Entry'" />
                                    </small>
                                </div>
                                <textarea v-if="enable_register" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
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
                                    <icheck :checked="enable_update" @update:checked="(is_checked)=>{ enable_update = is_checked; }" :label="'Enable Updates'" />
                                    </small>
                                </div>
                                <textarea v-if="enable_update" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
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
                                    <icheck :checked="enable_active" @update:checked="(is_checked)=>{ enable_active = is_checked; }" :label="'Enable ACTIVE'" />
                                    </small>
                                </div>
                                <textarea v-if="enable_active" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
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
                                    <icheck :checked="enable_inactive" @update:checked="(is_checked)=>{ enable_inactive = is_checked; }" :label="'Enable Inactive'" />
                                    </small>
                                </div>
                                <textarea v-if="enable_inactive" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
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
                                    <icheck :checked="enabe_delete" @update:checked="(is_checked)=>{ enabe_delete = is_checked; }" :label="'Enable Delete/Removal'" />
                                    </small>
                                </div>
                                <textarea v-if="enabe_delete" class="form-control text-sm" style="min-height:80px" placeholder="Please input your text command"></textarea>
                            </div>
                        </div>
                    </div>
                </div>  
            
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-primary"  >ADD / SAVE</button> 
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
                is_active:true,
                show_preview:false,
                enable_register:false,
                enable_update:false,
                enable_active:false,
                enable_inactive:false,
                enabe_delete:false,
                promiseExec:null
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                var vm = this;

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
