<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Devices</div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary" @click="$refs.modal_device.show()">
                            <span class="fa fa-plus"></span> ADD DEVICE
                        </button>
                        <table class="table table-bordered w-100 mt-1">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <small> # </small>
                                    </th>
                                    <th>
                                        <small> Name </small>
                                    </th>
                                    <th>
                                        <small> Type </small>
                                    </th>
                                    <th>
                                        <small> Host </small>
                                    </th>
                                    <th>
                                        <small> User </small>
                                    </th>
                                    <th>
                                        <small> Port </small>
                                    </th>
                                    <th>
                                        <small> Branches </small>
                                    </th>
                                    <th >
                                        <small> Is Active </small>
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" title="Trigger Logs">
                                            <span class="fa fa-list"></span>
                                        </button>
                                        <button class="btn btn-warning btn-sm" title="Edit">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" title="Remove">
                                            <span class="fa fa-times"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
        <modal-add-device ref="modal_device"  :group_id="group_id" :set_branch_source="set_branch_source" :set_branch_source_url="set_branch_source_url" />
    </div>
</template>

<script>
    import ModalAddDeviceVue from './ModalAddDevice.vue';
    export default {
        props:[ "group_id", "set_branch_source", "set_branch_source_url" ],
        components: {
            "modal-add-device":ModalAddDeviceVue
        },
        data: function () {
            return { 
                branch_source:"branches",
                branch_source_url:""
            }
        },
        methods: { 

            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
        },
        mounted:function(){ 
            console.log("GROUPID", this.group_id);
            if(this.set_branch_source){
                this.branch_source = this.set_branch_source;
            } 
        },
        updated:function(){

        }
    }
</script>
