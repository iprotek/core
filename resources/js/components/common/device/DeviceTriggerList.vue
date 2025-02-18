<template>
    <div  >
        <div class="card">
            <div class="card-header"> {{ title }} </div>
            <div class="card-body pt-1"> 
                <button class="btn btn-outline-primary btn-sm mb-1" @click="$refs.modal_trigger_add.show()"> ADD Trigger</button>
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
                            <th v-text="item.trigger_name"></th>
                            <th>
                                <small class="mx-2 text-nowrap">
                                    <i :class="'fa fa-'+(item.enable_register? 'check text-success':'times text-danger')"></i>
                                    REGISTER
                                </small>
                                <small class="mx-2 text-nowrap">
                                    <i :class="'fa fa-'+(item.enable_update? 'check text-success':'times text-danger')"></i>
                                    UPDATE
                                </small>
                                <small class="mx-2 text-nowrap">
                                    <i :class="'fa fa-'+(item.enable_active? 'check text-success':'times text-danger')"></i>
                                    ACTIVE
                                </small>
                                <small class="mx-2 text-nowrap">
                                    <i :class="'fa fa-'+(item.enable_inactive? 'check text-success':'times text-danger')"></i>
                                    INACTIVE
                                </small>
                                <small class="mx-2 text-nowrap">
                                    <i :class="'fa fa-'+(item.enable_remove? 'check text-success':'times text-danger')"></i>
                                    DELETE
                                </small>

                            </th>
                            <th>
                                <small class="text-nowrap">
                                    <span v-if="item.device_access"  v-text="item.device_access.text"></span> 
                                    <code v-else >Deleted/Removed</code> 
                                </small>

                            </th>
                            <th>
                                <small>
                                    <span v-if="item.is_active" class="text-success text-primary">ACTIVE</span>
                                    <span v-else class="text-danger textt-danger">INACTIVE</span>
                                </small>
                            </th>
                            <th class="text-center" style="width:200px;" >
                                <button class="btn btn-outline-primary btn-sm mx-1 px-1 py-0" title="Logs" @click="$refs.modal_device_log.show(item.device_access_id, 0, '')" >
                                    <span class="fa fa-list"></span>
                                </button>
                                <button class="btn btn-outline-warning btn-sm mx-1 px-1 py-0" @click="$refs.modal_trigger_add.show(item.id)">
                                    <span class="fa fa-edit"></span>
                                </button>
                                <button class="btn btn-outline-danger btn-sm mx-1 px-1 py-0" @click="$refs.modal_trigger_add.remove(item.id)">
                                    <span class="fa fa-times"></span>
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <modal-trigger-add @data_updated="loadDeviceTrigger()" ref="modal_trigger_add" :group_id="group_id" :target_id="target_id" :target_name="target_name" />
        <modal-device-log ref="modal_device_log" :group_id="group_id" />
    </div>
</template>

<script>
    import ModalDeviceLogVue from './ModalDeviceLog.vue';
    import ModalTriggerAddListVue from './ModalTriggerAddList.vue';

    export default {
        props:[ "group_id" , "title", "target_name", "target_id" ],
        components: {
            "modal-trigger-add":ModalTriggerAddListVue,
            "modal-device-log":ModalDeviceLogVue
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
                        //console.log(vm.itemList);
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
