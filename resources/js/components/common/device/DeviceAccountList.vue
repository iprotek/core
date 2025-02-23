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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                triggerList:[]
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
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/accounts/list-device-triggers?'+this.queryString({
                    target_id: this.target_id,
                    target_name: this.target_name,
                    branch_id: this.branch_id
                })).then(resp=>{
                    
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
