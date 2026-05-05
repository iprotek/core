<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template #header >
                Mikrotik API Terminal
            </template> 
            <template #body >
                <div>
                    <small> 
                        <code>** To comply security measurements, this prompt has its own commandline and does not fully clone the mikrotik commandline prompt. This also support its own scripting condition for checking and viewing only. adding and modification action will be prevented or causes an error. Please refer to our documentation <a href="#"> here</a>. </code>
                    </small>
                </div>
                <b>{{title}}</b>
                <div>
                    <table class="w-100">
                        <tr>
                            <td>
                                <small>IDS:(multi separated by ,) <code>_added_ids[*]</code></small>
                                <input v-model="added_ids" class="w-100" />
                            </td>
                            <td>
                                <small>Initial Context: JSON Format</small>
                                <input v-model="ini_context" class="w-100" />
                            </td>
                        </tr>
                    </table>
                </div>
                    <small>SCRIPT:</small>
                <textarea v-model="commandline_script" class="form-control w-100" style="font-family:Consolas, 'Lucida Console', monospace; font-size:12px; min-height:120px;" />
                <div>
                    <validation :errors="errors" :field="'prompt_or_script'" :is_small="true" />
                </div>
                <web-submit ref="check_now" @update:set_errors="errors = $event" :action="chekNow" el_class="btn btn-outline-primary btn-sm px-1 py-0" icon_class="fa fa-play" label="Check" :timeout="3000" />
   
                <div v-if="command_result.status">
                    <small class="text-success">{{command_result.message}}</small>
                    <table class="w-100">
                        <tr>

                        </tr>
                    </table>
                </div>
                <div v-else>
                    <code v-html="command_result.message"> </code>
                </div>
                <template v-for="(table, tabIndex) in tables" v-bind:key="'tab-'+_uid+'-'+tabIndex" >
                    <table-viewer :table="table" :title="'Result '+(tabIndex + 1)+':'" />
                </template>
                
            </template>
            <template #footer>
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    import TableViewerVue from '../TableViewer.vue';
    import ValidationVue from '../Validation.vue';
    import WebSubmitVue from '../WebSubmit.vue';
    import { getCurrentInstance } from 'vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: {
            "web-submit": WebSubmitVue,
            "validation": ValidationVue,
            "table-viewer":TableViewerVue
        },
        data: function () {
            let _uid = getCurrentInstance().uid;
            return {       
                _uid:_uid, 
                promiseExec:null,
                errors:{},
                device_access_id:0,
                commandline_script:'',
                added_ids:'',
                ini_context:'{}',
                command_result:{
                    status:0,
                    message:'',
                    result:[]
                },
                tables:[]
           }
        },
        methods:{ 
            reset:function(){
                this.errors = [];
                this.device_access_id = 0;
                this.commandline_script = '';
                this.command_result = {
                    status:0,
                    message:'',
                    result:[]
                };
                this.title = '';
            },
            show:function(device_access_id, title){ 
                var vm = this;
                vm.reset();
                vm.title = title;
                vm.device_access_id = device_access_id;

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            chekNow:function(){
                var vm = this;
                vm.tables = [];
                return WebRequest2(
                    'POST', 
                    '/api/group/'+vm.group_id+'/devices/mikrotik-check-script',
                    JSON.stringify({ prompt_or_script:vm.commandline_script })
                ).then(resp=>{
                    return resp.json().then(checkResult=>{
                        if(checkResult.status != 1){
                            vm.command_result.status = 0;
                            vm.command_result.message = checkResult.message;
                            vm.command_result.result = [];
                            return checkResult;
                        }


                        //REPLACE FOR SCRIPT CHECK EXECUTION
                        return WebRequest2(
                            'POST', 
                            '/api/group/'+vm.group_id+'/devices/mikrotik-run-script',
                            JSON.stringify({
                                prompt_or_script:vm.commandline_script, 
                                device_access_id: vm.device_access_id, 
                                added_ids:this.added_ids,
                                ini_context: this.ini_context
                                })
                        ).then(res=>{
                                return res.json().then(data=>{

                                    vm.command_result.status = data.status;
                                    vm.command_result.message = data.message;
                                    vm.command_result.result = [];
                                    vm.tables = data.tables;
                                    return data;
                                
                                });
                            }
                        );
                    });
                })

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
