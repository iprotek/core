<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b> List of Devices </b>
                    </div>
                    <div class="card-body">
                        <div class="input-group text-sm mb-1">
                            <span class="btn btn-default" @click="$refs.modal_device.show()">
                                <small title="Add Device" class="fa fa-plus text-primary"></small>
                            </span> 
                            <span class="btn btn-default" @click="loadDeviceList()">
                                <small title="Show" class="fa fa-search text-primary"></small>
                            </span> 
                            <input v-model="search" type="text" class="form-control" @keyup.enter="current_page=1; loadDeviceList();" >
                        </div>
                        <table class="table table-bordered w-100 mt-1">
                            <thead>
                                <tr>
                                    <th class="text-center text-primary">
                                        <small> # </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Name </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Type </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Host </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> User </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Port </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Branches </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> Is Active </small>
                                    </th>
                                    <th class="text-primary">
                                        <small> AutoRegister? </small>
                                    </th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <td colspan="10" class="text-center">
                                        -- LOADING DEVICES -- 
                                    </td>
                                </tr>
                                <tr v-else-if="itemList.length == 0">
                                    <td colspan="10" class="text-center">
                                        <code> NO DEVICE FOUND </code> 
                                    </td>
                                </tr>
                                <tr v-for="(device,deviceIndex) in itemList" v-bind:key="'device-item-'+device.id+'-'+deviceIndex">
                                    <th class="text-center">
                                        <small> <code v-text="device.id"></code> </small>
                                    </th>
                                    <td>
                                        <small>
                                            <b v-text="device.name"></b>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <b v-text="device.type"></b>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <b v-text="device.host"></b>
                                        </small>
                                    </td>
                                    <td >
                                        <small>
                                            <b v-text="device.user"></b>
                                        </small>
                                    </td>
                                    <td >
                                        <small>
                                            <b v-text="device.port"></b>
                                        </small>
                                    </td>
                                    <td >
                                        <small>
                                            <b v-text="device.branch_ids ? device.branch_ids.length: 0"></b>
                                        </small>
                                    </td>
                                    <td >
                                        <span v-if="device.is_active" class="text-success">ACTIVE</span>
                                        <span v-else class="text-danger">Inactive</span>
                                    </td>
                                    <td >
                                        <span v-if="device.is_trigger_registration" class="text-success">Yes</span>
                                        <span v-else class="text-danger">No</span>
                                    </td>
                                    <td>
                                        <button @click="$refs.device_log.show(device.id)" class="btn btn-primary btn-sm" title="Trigger Logs">
                                            <span class="fa fa-list"></span>
                                        </button>
                                        <button class="btn btn-warning btn-sm" title="Edit" @click="$refs.modal_device.show(device.id)">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" title="Remove" @click="$refs.modal_device.remove(device.id)">
                                            <span class="fa fa-times"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <page-footer v-model="pageData" @page_changed="page_changed" />
                    </div> 
                </div>
            </div>
        </div>
        <modal-add-device @data_updated="loadDeviceList()" ref="modal_device"  :group_id="group_id" :set_branch_source="set_branch_source" :set_branch_source_url="set_branch_source_url" />
        <modal-device-log ref="device_log" :group_id="group_id" />
    </div>
</template>

<script>
    import ModalAddDeviceVue from './ModalAddDevice.vue';
    import PageFooterVue from '../PageFooter.vue';
    import ModalDeviceLogVue from './ModalDeviceLog.vue';
    export default {
        props:[ "group_id", "set_branch_source", "set_branch_source_url" ],
        components: {
            "modal-add-device":ModalAddDeviceVue,
            "page-footer":PageFooterVue,
            "modal-device-log":ModalDeviceLogVue
        },
        data: function () {
            return {
                pageData:null,
                itemList:[],
                current_page:1,
                search:'',
                isLoading:false
            }
        },
        methods: { 
            page_changed:function(page){
                this.current_page = page;
                this.loadDeviceList();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadDeviceList:function(){
                if(!this.group_id) return;
                
                var vm = this;
                vm.isLoading = true;
                vm.itemList = [];
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/list?'+this.queryString({
                    page: this.current_page,
                    search_text: this.search,
                    items_per_page: 10
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.itemList = data.data;
                    })
                })
            }
        },
        mounted:function(){ 
            if(this.group_id)
                this.loadDeviceList();
        },
        updated:function(){

        }
    }
</script>
