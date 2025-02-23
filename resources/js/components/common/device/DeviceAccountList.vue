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
                                    <td colspan="7" class="text-center"> -- Branch is required. -- </td>
                                </tr>
                                <tr v-else-if="isLoading">
                                    <td colspan="7" class="text-center"> -- Loading Device Triggers -- </td>
                                </tr>
                                <tr v-if="triggerList.length == 0">
                                    <td colspan="7"> -- No Device Trigger Found! -- </td>
                                </tr>
                                <tr v-for="(item,itemIndex) in triggerList" v-bind:key="'trigger-item-'+item.id+'-'+itemIndex">
                                    <td v-text="item.id"></td>
                                    <td v-text="item.trigger_name"></td>
                                    <td>
                                        <b v-if="item.device_access" v-text="item.device_access.name"></b>
                                        <code v-else>N/A</code>
                                    </td>
                                    <td>
                                        <b v-if="item.device_access" v-text="item.device_access.type"></b>
                                        <code v-else>N/A</code>
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
                                        <template v-if="item.device_accounts.length <= 0">
                                            <button :class="'btn btn-sm '+( item.enable_register ? 'btn-outline-primary' : 'btn-outline-danger disabled')">
                                                <template v-if="item.enable_register">
                                                    <span class="fa fa-user-plus"></span>
                                                    REGISTER
                                                </template>
                                                <template v-else>
                                                    REGISTER NOT AVAILABLE
                                                </template>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "title", "group_id", "target_id", "target_name", "branch_id"  ],
        components: { 
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
