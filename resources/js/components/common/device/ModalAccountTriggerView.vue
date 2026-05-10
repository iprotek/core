<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="80">
            <template #header >
                DEVICE LOGS
            </template> 
            <template #body >     
                <div v-if="filters.device_access_id" class="py-2">
                    <div class="mb-2 text-right">
                        <button class="btn btn-outline-primary rounded-0 text-primary">
                            RESOLVE ALL
                        </button>
                    </div>
                    <page-data-table 
                        ref="device_trigger_log_table"
                        :is_use_top_search="false"
                        :url="url"
                        :search_placeholder="'Search any keywords..'"
                        :json_filter="filters"

                        @update:items="deviceTriggerLogList = $event"
                        @update:is_loading="isLoading = $event"
                    >
                        <small>
                            <table class="table table-bordered w-100 my-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device / TriggerName</th>
                                        <th>Command</th>
                                        <th>Response</th>
                                        <th>Info</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td colspan="7" class="text-center">
                                            <label class="m-0"> -- LOADING --</label>
                                        </td>
                                    </tr>
                                    <tr v-else-if="deviceTriggerLogList.length <= 0">
                                        <td colspan="7" class="text-center" >
                                            <code> - NOTHING FOUND - </code>
                                        </td>
                                    </tr>
                                    <tr v-for="(log,logIndex) in deviceTriggerLogList" v-bind:key="'log-'+log.id+'-'+logIndex">
                                        <td v-text="log.id"></td>
                                        <td v-text="log.id"></td>
                                        <td v-text="log.id"></td>
                                        <td v-text="log.id"></td>
                                        <td v-text="log.id"></td>
                                        <td v-text="log.id"></td>
                                        <td >
                                            <button class="btn btn-outline-success btn-sm border border-success border-3">
                                                <span class="fa fa-check"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </small>
                    </page-data-table>
                </div>
            </template>
            <template #footer>
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
        <swal-input ref="swal_input" :custom_input_field_name="'resolved_reason'" />
    </div>

</template>

<script>    
    import SwalInputVue from '../SwalInput.vue';
    import PageDataTableVue from '../PageDataTable.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id", "device_access_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
            "swal-input":SwalInputVue,
            "page-data-table":PageDataTableVue
        },
        data: function () {
            return {        
                filters:{
                    device_access_id: 0
                },
                deviceTriggerLogList:[],
                isLoading:false,
                url:`/api/group/${this.group_id}/devices/trigger/log/list`,
                promiseExec:null,
                errors:[],
                trigger_id:0,
                target_name:'', 
                target_id:0
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(trigger_id, target_name, target_id, device_access_id){ 
                var vm = this;
                vm.trigger_id = trigger_id;
                vm.target_name = target_name;
                vm.target_id = target_id;
                vm.filters = {
                    device_access_id: device_access_id,
                    target_name: target_name,
                    target_id: target_id,
                    device_template_trigger_id: trigger_id
                }//.device_access_id = device_access_id;

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            add:function(){
                var vm = this;
                return vm.$refs.swal_input.alert("Has Resolved?", "Resolve", "PUT", "/api/", { } ).then(res=>{

                    if(res.isConfirmed){

                        //if(res.value.status == "1")
                        return res.value;

                    }

                });
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
