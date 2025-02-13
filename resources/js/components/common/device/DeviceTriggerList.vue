<template>
    <div  >
        <div class="card">
            <div class="card-header"> {{ title }} </div>
            <div class="card-body pt-1"> 
                <button class="btn btn-outline-primary btn-sm mb-1"> ADD Trigger</button>
                <table class="w-100 table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <small> Ref# </small> 
                            </th>
                            <th>
                                <small> Trigger Name </small> 
                            </th>
                            <th>
                                <small> Command Template </small> 
                            </th>
                            <th>
                                <small> Device Info </small> 
                            </th>
                            <th>
                                <small> Status </small> 
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isLoading">
                            <th colspan="6" class="text-center text-warning"> 
                                -- LOADING DEVICES --  
                            </th>
                        </tr>
                        <tr v-else-if="itemList.length == 0">
                            <th colspan="6" class="text-center">
                            <code> -- NO DEVICE TRIGGER FOUND -- </code>
                            </th>
                        </tr>
                        <tr v-for="(item, itemIndex) in itemList" v-bind:key="'device-trigger-'+item.id+'-'+itemIndex">
                            <th class="text-center">
                                <small v-text="item.id"> </small>
                            </th>
                            <th></th>
                            <th>
                                <span >
                                    <i class="fa fa-check"></i>
                                    ADD
                                </span>
                                <span>
                                    <i class="fa fa-check"></i>
                                    UPDATE
                                </span>
                                <span>
                                    <i class="fa fa-check"></i>
                                    ACTIVE
                                </span>
                                <span>
                                    <i class="fa fa-check"></i>
                                    INACTIVE
                                </span>
                                <span>
                                    <i class="fa fa-check"></i>
                                    DELETE
                                </span>

                            </th>
                            <th></th>
                            <th>
                                <small>
                                    <span class="text-success">ACTIVE</span>
                                    <span class="text-danger">INACTIVE</span>
                                </small>
                            </th>
                            <th class="text-center" style="width:200px;" >
                                <button class="btn btn-outline-primary btn-sm mx-1">
                                    <span class="fa fa-list"></span>
                                </button>
                                <button class="btn btn-outline-warning btn-sm mx-1">
                                    <span class="fa fa-edit"></span>
                                </button>
                                <button class="btn btn-outline-danger btn-sm mx-1">
                                    <span class="fa fa-times"></span>
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "group_id" , "title", "target_name", "target_id" ],
        components: { 
        },
        data: function () {
            return {
                itemList:[],
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

            loadDeviceTrigger:function(){
                
                var vm = this;
                vm.isLoading = true;
                vm.itemList = [];
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/trigger/list?'+ this.queryString({
                    "target_name":this.target_name,
                    "target_id":this.target_id
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.itemList = data;
                    });
                });
                
            }
        },
        mounted:function(){
            this.loadDeviceTrigger();     
        },
        updated:function(){

        }
    }
</script>
