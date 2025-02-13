<template>
    <div  > 
        <button class="btn btn-outline-primary btn-sm mb-1">ADD {{action_name}} TRIGGER</button>
        <table class="w-100 table table-bordered">
            <thead>
                <tr>
                    <th>
                        <small># </small> 
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
                    <th></th>
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
</template>

<script>
    export default {
        props:[ "group_id" , "target_name", "target_id" ],
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
                /*
                var vm = this;
                vm.isLoading = true;
                vm.itemList = [];
                WebRequest2('POST', '/api/group/'+this.group_id+'/settings/account-device-template-triggers/'+this.action_name).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.itemList = data;
                    });
                });
                */
            }
        },
        mounted:function(){
            this.loadDeviceTrigger();     
        },
        updated:function(){

        }
    }
</script>
