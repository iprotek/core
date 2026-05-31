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
                        <div>
                           <code> *Notice: If you use [account field=""] variable please select a demo target before checking. </code>
                        </div>
                    </small>
                </div>
                <b>{{title}}</b>
                <div>
                    <table class="w-100">
                        <tr>
                            <td style="width:34%;">     
                                <small class="mb-1">SELECT DEMO: <b>{{ (target_name ? target_name : '').toUpperCase() }}</b></small>
                                <select2 @selected="loadPreviewScript" :query_filters="{data_schema: target_name, trigger_fields: [], branch_id:branch_id}" v-model="selected_preview" :has_clear="true" :modal_selector="true" :url="'/api/group/'+group_id+'/devices/dynamic-selection'" :placeholder="'--Select Demo Target--'"  />
                            </td>
                            <td style="width:33%;">
                                <small>IDS:(multi separated by ,) <code>_added_ids[*]</code></small>
                                <input class="form-control" v-model="added_ids" />
                            </td>
                            <td style="width:33%;">
                                <small>Initial Context: JSON Format</small>
                                <input class="form-control" v-model="ini_context"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <table class="w-100">
                    <tr>
                        <td :style="'vertical-align:top; width:'+( selected_preview.id >0 ?'50':'100' )+'%'">
                            <small>SCRIPT:</small>
                            <textarea ref="t1" v-model="commandline_script" @input="sync" class="form-control w-100" :style="`font-family:Consolas, 'Lucida Console', monospace; font-size:12px; min-height:120px;height:${t1_height};`" />
                        </td>
                        <td :style="'vertical-align:top; display:'+(selected_preview.id>0 ?'':'none')">    
                            <small>TRANSLATION:</small> &nbsp;<span class="fa fa-redo text-primary" @click="loadPreviewScript()"></span> 
                            <textarea ref="t2" :value="preview_script" readonly class="form-control w-100" :style="`font-family:Consolas, 'Lucida Console', monospace; font-size:12px; min-height:120px;height:${t2_height};`" />
                        </td>
                    </tr>
                </table>
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
    import Select2Vue from '../Select2.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id", "target_name" ],
        $emits:[],
        components: {
            "web-submit": WebSubmitVue,
            "validation": ValidationVue,
            "table-viewer":TableViewerVue,
            "select2":Select2Vue
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
                tables:[],
                selected_preview:{
                    id:0,
                    text:' -- Select Preview -- '
                },
                title:'',
                preview_script:'',
                timer:null,
                t1_height:'120px',
                t2_height:'120px',
                counting:0
           }
        },
        watch: { 
            commandline_script:function(newValue){
                var vm = this;
                this.debounceHandler(function(){
                    vm.onTypingStopped(newValue);
                },  1500);
            }
        },
        methods:{ 
            debounceHandler:function(callback,  interval = 5000) {
                if (this.timer) {
                    clearTimeout(this.timer);
                }
                this.timer = setTimeout(() => {
                    //this.onTypingStopped(value);
                    if(callback){
                       callback();
                    }
                }, interval);
            },
            onTypingStopped:function(value) {
                // 🔁 your refresh logic here
                //console.log(this.counting++);
                this.counting++;
                this.loadPreviewScript();
            },
            loadPreviewScript(){
                var vm = this;
                
                if(vm.selected_preview.id <= 0) return;

                if(!vm.commandline_script || !vm.commandline_script.trim()){ vm.preview_script = ''; return;}

                return WebRequest2(
                    'POST', 
                    '/api/group/'+vm.group_id+'/devices/mikrotik-preview-script',
                    JSON.stringify({
                        prompt_or_script:vm.commandline_script, 
                        target_name: vm.target_name, 
                        target_id:vm.selected_preview.id
                        })
                ).then(res=>{
                        return res.json().then(data=>{
                            if(data.status == 1)
                                vm.preview_script = data.result;
                            else vm.preview_script = '';

                            return data;
                        });
                    }
                );

            },
            reset:function(){
                this.preview_script = '';
                this.errors = [];
                this.device_access_id = 0;
                this.commandline_script = '';
                this.command_result = {
                    status:0,
                    message:'',
                    result:[]
                };
                this.tables = [];
                this.title = '';
                this.selected_preview = {
                    id:0,
                    text:' -- Select Preview -- '
                }
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
                                added_ids:vm.added_ids,
                                ini_context: vm.ini_context,
                                target_name: vm.target_name,
                                target_id: vm.selected_preview.id
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
            },
            sync:function() {
                var vm = this;
                this.$nextTick(() => {  
                    const t1 = vm.$refs.t1;
                    const t2 = vm.$refs.t2;

                    // reset first so shrinking works
                    //t1.style.height = 'auto';
                    //t2.style.height = 'auto';

                    // get tallest content height
                    const max = Math.max(t1.scrollHeight, t2.scrollHeight);
                    let t2Height = t2.scrollHeight;
                    if(t2Height < t1.scrollHeight)
                        t2Height = t1.scrollHeight;

                    //if(t1.scrollHeight == t2Height) return;

                    // apply same height
                    if(vm.t1_height != (t1.scrollHeight + 'px')){
                        vm.t1_height = (t1.scrollHeight + 'px');
                        //t1.style.height = t1.scrollHeight + 'px';
                    }
                    if(vm.t2_height != (t2Height + 'px')){
                        vm.t2_height = (t2Height + 'px');
                        //t2.style.height = t2Height + 'px';
                    } 
                });
            }

        },
        mounted:function(){
        },
        beforeUnmount() {
            clearInterval(this._syncInterval);
        },
        updated:function(){

        }
    }
</script>
