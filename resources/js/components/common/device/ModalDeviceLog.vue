<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                Device Logs
            </template> 
            <template slot="body" >
                <div v-if="device_id">
                    <div class="input-group text-sm mb-1 mt-2"> 
                        <span class="btn btn-default" @click="current_page=1;loadDeviceLogs()">
                            <small title="Show" class="fa fa-search text-primary"></small>
                        </span> 
                        <input v-model="search" type="text" class="form-control" @keyup.enter="current_page=1; loadDeviceLogs();" >
                    </div>
                    <table class="w-100 table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Target</th>
                            <th>TargetRef#</th>
                            <th>Command</th>
                            <th>Log Info</th>
                            <th>Status</th>
                            <th>At</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="(item,itemIndex) in itemList" v-bind:key="'item-'+item.id+'-'+itemIndex">
                                <th v-text="item.id"></th>
                                <td v-text="item.target_name"></td>
                                <td v-text="item.target_id"></td>
                                <td v-text="item.command"></td>
                                <td v-text="item.log_info"></td>
                                <td >
                                    <span v-if="item.status_id == 0" class="text-warning">Pending</span>
                                    <span v-else-if="item.status_id == 1" class="text-success">Success</span>
                                    <span v-else-if="item.status_id == 2" class="text-danger">Failed</span>
                                </td>
                                <td v-text="item.created_at"></td>
                                <td></td>
                            </tr>
                            <tr v-if="isLoading">
                                <td colspan="8" class="text-center">
                                    <code> -- Loading Device Logs -- </code>
                                </td>
                            </tr>
                            <tr v-else-if="itemList.length == 0">
                                <td colspan="8" class="text-center">
                                    <code> -- No Device Logs Found! -- </code>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <page-footer v-model="pageData" @page_changed="page_changed" />
                </div>
            
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import PageFooterVue from '../PageFooter.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                device_id:0,
                pageData:null,
                itemList:[],
                current_page:1,
                isLoading:false,
                search:''
           }
        },
        methods:{ 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadDeviceLogs();
            },
            reset:function(){

            },
            show:function(device_id){ 
                var vm = this;
                vm.device_id = 0;

                this.$refs.modal.show();

                setTimeout(()=>{
                    vm.device_id = device_id;
                    vm.loadDeviceLogs();
                })

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            loadDeviceLogs:function(){
                var vm = this;
                vm.isLoading = true;
                vm.itemList = [];
                WebRequest2('GET', ('/api/group/'+this.group_id+'/devices/logs?')+vm.queryString({
                    device_access_id:vm.device_id,
                    search_text:vm.search,
                    page:vm.current_page,
                    items_per_page: 10
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.itemList = data.data;
                    });
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
