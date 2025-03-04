<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                       <b> {{ title }} </b>
                    </div>
                    <div class="card-body">
                        <input type="checkbox" /> Show Existing Device Accounts Only
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <small> <b># </b> </small>
                                    </th>
                                    <th>
                                        <small> <b> Trigger Name </b> </small>
                                    </th>
                                    <th>
                                        <small> <b>Device</b> </small> 
                                    </th>
                                    <th>
                                        <small><b> Type</b> </small>
                                    </th>
                                    <th>
                                        <small><b> Auto </b> </small>
                                    </th>
                                    <th>
                                        <small> <b>Status</b> </small>
                                    </th>
                                    <th>
                                        <small><b> Info</b> </small>
                                    </th>
                                    <th>
                                        <small><b> Last Update</b> </small>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="branch_id === null || branch_id === undefined">
                                    <td colspan="8" class="text-center"> -- Branch is required. -- </td>
                                </tr>
                                <tr v-else-if="isLoading">
                                    <td colspan="8" class="text-center"> -- Loading Device Triggers -- </td>
                                </tr>
                                <tr v-if="triggerList.length == 0">
                                    <td colspan="8"> -- No Device Trigger Found! -- </td>
                                </tr>
                                <tr v-for="(item,itemIndex) in triggerList" v-bind:key="'trigger-item-'+item.id+'-'+itemIndex">
                                    <td v-text="item.id"></td>
                                    <td v-text="item.trigger_name"></td>
                                    <td>
                                        <b v-if="item.device_access" >
                                            <span v-text="item.device_access.name"></span>
                                            <div v-if="!item.device_access.is_active">
                                                <small class="text-danger">inactive device</small>
                                            </div>
                                        </b>
                                        <code v-else>N/A</code>
                                    </td>
                                    <td>
                                        <b v-if="item.device_access" v-text="item.device_access.type"></b>
                                        <code v-else>N/A</code>
                                    </td>
                                    <td> 
                                        <switch2 v-if="item.device_accounts.length > 0" v-model="item.device_accounts[0].is_auto_trigger"  />
                                    </td>
                                    <td>
                                        <code v-if="item.device_accounts.length <= 0">NO ACCOUNT</code>
                                    </td>
                                    <td> 
                                    </td>
                                    <td>
                                        <span class="text-primary" v-if="item.device_accounts.length > 0" v-text="item.device_accounts.updated_at" ></span>
                                    </td>
                                    <td>
                                        <div v-if="item.device_access && item.device_access.is_active">
                                            <template v-if="item.device_accounts.length <= 0">
                                                <button :class="'text-nowrap btn btn-sm '+( item.enable_register ? 'btn-outline-primary' : 'btn-outline-danger disabled')" @click="item.enable_register ? makeAction(item.id, 'register') : '' ">
                                                    <template v-if="item.enable_register">
                                                        <span class="fa fa-user-plus"></span>
                                                        REGISTER
                                                    </template>
                                                    <template v-else>
                                                        REGISTER NOT AVAILABLE
                                                    </template>
                                                </button>
                                            </template>
                                        </div>
                                        <div v-else>
                                            <code> -- Device currently Unavailable -- </code>
                                        </div>
                                
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import BoostrapSwitch2Vue from '../BoostrapSwitch2.vue';
    export default {
        props:[ "title", "group_id", "target_id", "target_name", "branch_id"  ],
        components: { 
            "switch2":BoostrapSwitch2Vue
        },
        watch: { 
        },
        data: function () {
            return {
                triggerList:[],
                isLoading:false
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            makeAction:function(device_template_trigger_id, action){
                var  vm = this;
                var method = '';
                var message = '';
                var uri = '';
                if(action == 'register'){
                    method = 'POST';
                    message = 'Register Account?';
                    uri = 'register-account';
                }
                else if(action == 'update'){
                    method = 'PUT';
                    message = 'Update Account?';
                    uri = 'update-account';
                }
                else if(action == 'active'){
                    method = 'PUT';
                    message = 'SET ACTIVE ACCOUNT?';
                    uri = 'set-active';
                }
                else if(action == 'inactive'){
                    method = 'PUT';
                    message = 'Inactive Account?';
                    uri = 'set-inactive';
                }
                else if(action == 'remove'){
                    method = 'DELETE';
                    message = 'Delete Account?';
                    uri = 'remove';
                }
                else{
                    return;
                }
                if(uri == 'remove'){
                     
                    return  this.$refs.swal_prompt.alert(
                        'question',
                        message, 
                        "Confirm" , 
                        method, 
                        '/api/group/'+this.group_id+'/devices/accounts/remove?'+this.queryString({
                            device_template_trigger_id: device_template_trigger_id,
                            target_id: this.target_id,
                            target_name: this.target_name
                        })
                    ).then(res=>{
                        if(res.isConfirmed){
                            if(res.value.status == 1){
                                vm.loadDeviceAccounts();
                            }
                        }
                    }); 

                } 
                     
                return this.$refs.swal_prompt.alert(
                    'question',
                    message, 
                    "Confirm" , 
                    method, 
                    '/api/group/'+this.group_id+'/devices/accounts/'+uri,
                    JSON.stringify({
                        device_template_trigger_id: device_template_trigger_id,
                        target_name: this.target_name,
                        target_id: this.target_id
                    })
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            vm.loadDeviceAccounts();
                        }
                    }
                }); 

            },
            loadDeviceAccounts:function(){
                var vm = this;
                vm.triggerList = [];
                vm.isLoading = true;
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/accounts/list-device-triggers?'+this.queryString({
                    target_id: this.target_id,
                    target_name: this.target_name,
                    branch_id: this.branch_id
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        console.log("Data", data);
                        vm.triggerList = data;
                    });
                    
                })
            }
        },
        mounted:function(){ 
            this.loadDeviceAccounts();    
        },
        updated:function(){

        }
    }
</script>
