<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="80">
            <template #header >
                DEVICE LOGS
            </template> 
            <template #body >     
                <div :id="modal_selector_name" v-if="target_name != '' " class="py-2">
                    <div class="mb-2 text-right">
                        <button class="btn btn-outline-primary rounded-0 text-primary" @click="resolve_all">
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
                        <small class="d-block overflow-auto">
                            <table class="table table-bordered w-100 my-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device / TriggerName</th>
                                        <th>Command</th>
                                        <th style="min-width:30vw;">Response</th>
                                        <th style="min-width:100px;">Info</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <td colspan="6" class="text-center">
                                            <label class="m-0"> -- LOADING --</label>
                                        </td>
                                    </tr>
                                    <tr v-else-if="deviceTriggerLogList.length <= 0">
                                        <td colspan="6" class="text-center" >
                                            <code> - NOTHING FOUND - </code>
                                        </td>
                                    </tr>
                                    <tr v-for="(log,logIndex) in deviceTriggerLogList" v-bind:key="'log-'+log.id+'-'+logIndex">
                                        <td v-text="log.id"></td>
                                        <td >
                                            <div class="p-0">
                                                <label class="mb-0" v-if="log.trigger && log.trigger.device_access">{{log.trigger.device_access.name}}</label>
                                                <label class="mb-0 text-danger" v-else>--DEVICE NOT FOUND--</label>
                                            </div>
                                            <small style="text-indent:50px;" class="text-secondary" v-if="log.trigger">{{log.trigger.trigger_name}}</small>
                                            <small style="text-indent:50px;" class="text-secondary" v-else>--TRIGGER NOT AVAILABLE--</small>
                                        </td>
                                        <td style="max-width:25vw;">
                                            <pre class="bg-light border rounded-0">{{log.command.trim()}}</pre>
                                        </td>
                                        <td >
                                            <code v-if="!isValidJSON(log.response)">{{log.response}}</code>
                                            <small v-else>
                                                <vue-json-pretty :data="JSON.parse(log.response)"       
                                                :deep="0"
                                                :showLength="true"
                                                :showLine="true"
                                                :showIcon="true"
                                                :collapsedOnClickBrackets="true" />
                                            </small>
                                            <!--
                                            <pre class="bg-light border rounded p-3">
                                                {{ log.response }}
                                            </pre>-->
                                        </td>
                                        <td >
                                            <small v-if="log.status_id == 2" class="text-danger" >
                                                {{log.log_info}}
                                                <div v-if="log.is_resolved" class="p-0">
                                                    <span class="text-primary"> {{log.resolved_info}} </span>
                                                </div>
                                            </small>
                                            <small v-else class="text-success" >{{log.log_info}}</small>
                                        </td>
                                        <td >
                                            <label v-if="log.status_id == 2 && log.is_resolved != 1" class="text-danger">FAILED</label>
                                            <label v-else-if="log.status_id == 2 && log.is_resolved" class="text-primary">RESOLVED</label>
                                            <label v-else class="text-success">SUCCESS</label>
                                            <button v-if="!log.is_resolved" class="btn btn-outline-success btn-sm border border-success border-3" @click="resolve(log)">
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
        <swal-input ref="swal_input" :custom_input_field_name="'resolved_info'" :modal_selector="'#'+modal_selector_name" />
    </div>

</template>
<style scoped>
.json-wrapper{
  background:#1e1e1e;
  padding:15px;
  border-radius:12px;
  overflow:auto;
}
:deep(.vjs-key) {
  color: #4fc3f7;
}

:deep(.vjs-value-string) {
  color: #81c784;
}

:deep(.vjs-value-number) {
  color: #ff8a65;
}

:deep(.vjs-value-boolean) {
  color: #ba68c8;
}
</style>

<script>    
    import SwalInputVue from '../SwalInput.vue';
    import PageDataTableVue from '../PageDataTable.vue';
    import VueJsonPretty from 'vue-json-pretty'
    import 'vue-json-pretty/lib/styles.css'
    import { getCurrentInstance } from 'vue'
    export default {
        props:[ "theme_info", "group_id", "branch_id", "device_access_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
            "vue-json-pretty":VueJsonPretty,
            "swal-input":SwalInputVue,
            "page-data-table":PageDataTableVue
        },
        data: function () {
            let _uid = getCurrentInstance().uid;
            return {  
                modal_selector_name:'modal-account-trigger-'+_uid,
                filters:{
                    device_access_id: 0,
                    target_name :'',
                    target_id:'',
                    device_template_trigger_id:0
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
            isValidJSON:function(str) {
                try {
                    JSON.parse(str);
                    return true;
                } catch (e) {
                    return false;
                }
            },
            show:function(trigger_id, target_name, target_id, device_access_id){ 
                var vm = this;
                vm.trigger_id = trigger_id;
                vm.target_name = target_name;
                vm.target_id = target_id;
                vm.deviceTriggerLogList = [];
                
                let newfilter = {};
                if(device_access_id) newfilter.device_access_id = device_access_id;
                if(target_name) newfilter.target_name = target_name;
                if(target_id) newfilter.target_id = target_id;
                if(trigger_id) newfilter.device_template_trigger_id = trigger_id;

                vm.filters = newfilter;
                
                console.log(vm.filters);

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            resolve:function(log){                
                var vm = this;
                return vm.$refs.swal_input.alert("question","Has Resolved?", "Resolve this.", "PUT", `/api/group/${this.group_id}/devices/trigger/log/resolve`, { 
                    log_id:log.id,
                    device_access_id: vm.device_access_id,
                    target_name:vm.target_name,
                    target_id: vm.target_id,
                    device_template_trigger_id: log.device_template_trigger_id
                } ).then(res=>{
                    if(res.isConfirmed){
                        //if(res.value.status == "1")
                        if(res.value.status == 1){
                            vm.$refs.modal.dismiss();
                            setTimeout(()=>{
                                     vm.$emit('resolved');
                            }, 50);
                            //vm.$emit('resolved');
                            //vm.$refs.device_trigger_log_table.reloadPage();
                        }
                        return res.value;
                    }
                });
            },
            resolve_all:function(){              
                var vm = this;
                return vm.$refs.swal_input.alert("question","Resolve all?", "Resolve Now!", "PUT", `/api/group/${this.group_id}/devices/trigger/log/resolve`, { 
                    device_access_id: vm.device_access_id,
                    target_name:vm.target_name,
                    target_id: vm.target_id
                } ).then(res=>{
                    if(res.isConfirmed){
                        //if(res.value.status == "1")
                        if(res.value.status == 1){
                            vm.$refs.modal.dismiss();
                            setTimeout(()=>{
                                     vm.$emit('resolved');
                            }, 50);
                            //vm.$refs.device_trigger_log_table.reloadPage();
                        }
                        return res.value;
                    }
                });
            },
            add:function(){
                var vm = this;
                return vm.$refs.swal_input.alert("Has Resolved?", "Resolve", "PUT", "/api/", { } ).then(res=>{

                    if(res.isConfirmed){

                        //if(res.value.status == "1")
                        vm.$refs.device_trigger_log_table.reloadPage();
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
