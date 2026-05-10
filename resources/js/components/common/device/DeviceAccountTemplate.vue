<template>
    <div>
        <div v-if="deviceAccounts">
            <div  >
                <label class="text-primary mb-0">*Integrated Device(s)</label>
                <table class="table table-bordered">
                    <tr v-for="(acc, accIndex) in deviceAccounts" v-bind:key="'device-account-'+(acc.id)+'-'+accIndex">
                        <td>
                            <div class="py-0">
                                <label v-if="acc.device_template_trigger && acc.device_template_trigger.device_access" :class="'mb-0 p-0 '+(acc.device_template_trigger.device_access.is_active ? '':'text-danger')">{{acc.device_template_trigger.device_access.name}}</label>
                                <label v-else class="mb-0 p-0 text-danger"> -- DEVICE NOT AVAILABLE -- </label>
                            </div>
                            <small class="pl-3 py-0 text-secondary"><i>{{acc.device_template_trigger.trigger_name}}</i></small>
                        </td>
                        <td style="width:45px;">
                            <button title="Trigger Infos" class="border border-3 border-primary text-primary py-0">
                                <span class="fa fa-list"></span>
                            </button>
                        </td>
                        <td style="width:45px;">
                            <button title="Remove" :class="'border border-3 border-danger text-danger rounded-0 mr-1 py-0'+( acc.device_template_trigger && acc.device_template_trigger.enable_remove ? '' : 'disabled')" @click="(acc.device_template_trigger && acc.device_template_trigger.enable_remove ? removeClick(acc):'')">
                                <span class="fa fa-times"></span>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    export default {
        props:[ "theme_info", "group_id", "branch_id", "device_accounts" ],
        $emits:[],
        watch: { 
            device_accounts:function(newValue){
                this.deviceAccounts = newValue;
            }
        },
        components: { 
        },
        data: function () {
            return {
                deviceAccounts:null
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            removeClick:function(acc){
                var vm = this;
                let target_id = acc.target_id;
                let device_template_trigger_id = acc.device_template_trigger_id;
                let target_name = acc.target_name;
                let device_account_id = acc.id;
                
                let method = 'DELETE';
                let message = 'Delete Account?';

                return  vm.$refs.swal_prompt.alert(
                    'question',
                    message, 
                    "Confirm" , 
                    method, 
                    '/api/group/'+vm.group_id+'/devices/accounts/remove?'+vm.queryString({
                        device_template_trigger_id: device_template_trigger_id,
                        target_id: target_id,
                        target_name: target_name,
                        device_account_id: device_account_id
                    })
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            //vm.loadDeviceAccounts();
                            vm.$emit('removed');
                        }
                    }
                }); 
            }

        },
        mounted:function(){
            this.deviceAccounts = this.device_accounts;
            console.log("GG",this.deviceAccounts);
        },
        updated:function(){

        }
    }
</script>
