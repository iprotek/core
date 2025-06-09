<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                ADD SMS SCHEDULE
            </template> 
            <template slot="body" >     
                <div class="row">
                    <div class="col-sm-6">
                        <input2 v-model="sms_notify_sched.name" :type="'text'" :placeholder="'SMS Schedule Trigger Name'" :input_style="'height:40px;'" />
                        <validation :errors="errors" :field="'name'" />
                        <div class="mt-2">
                            <label class="mb-0 mt-2">IS ACTIVE:</label>
                            <switch2 v-model="sms_notify_sched.is_active" />
                        </div>

                        <div class="mt-2" >
                            <label class="mb-0"> SMS SENDER </label>
                            <select2 v-model="selected_sms_sender" :query_filters="{active_only:1}" :placeholder="' -- SMS SENDER -- '" :url="'/manage/sms-sender/list'" :modal_selector="true" />
                        </div>
                        <validation :errors="errors" :field="'sms_client_api_request_link_id'" />
                        
                        <div>
                            <label class="mb-0 mt-2">TO TYPE:</label>
                            <select @change="to_type_changed" v-model="sms_notify_sched.to_type" class="form-control">
                                <option v-for="(item,itemIndex) in to_type_list" :value="item" v-bind:key="'item-type-'+item+'-'+itemIndex"> {{ item }}</option>
                            </select>
                        </div>

                        <div v-if="sms_notify_sched.to_type">
                            <select2 @selected="item_selected_changed" :is_clean_after_select="true" :has_clear="true" :placeholder="'-- select '+sms_notify_sched.to_type+'--'" :url="'/list/'+sms_notify_sched.to_type" :modal_selector="true" />
                        </div>
                        <validation :errors="errors" :field="'selected_items'" />
                        <validation :errors="errors" :field="'mobile_nos'" />
                        <div>
                            <span class="badge badge-pill badge-primary" v-for="(item,itemIndex) in sms_notify_sched.selected_items" v-bind:key="'item-'+item.id+'-'+itemIndex" >
                              <span class="fa fa-times text-danger" style="cursor: pointer;" @click="removeNotifSched(item)"></span>  {{ item.text }} - {{ item.mobile_no }}
                            </span>
                        </div>
                        <div class="my-2">
                            <label class="mb-0">SEND MESSAGE:</label> <button class="btn btn-sm btn-primary">Automate compose message</button>
                            <textarea v-model="sms_notify_sched.send_message" class="form-control w-100" style="min-height: 150px;"></textarea>
                        </div>
                        <div>
                            <small>                            
                                <label class="mb-0">DYNAMIC VARIABLES</label>
                                <div><code>[person_name]</code> - the name of the recipient</div>
                                <div><code>[total_due]</code> - automatically set based on total due</div>
                                <div><code>[total_paid]</code> - automatically adjust based on total paid</div>
                                <div><code>[total_balance]</code> - automatically set from total due deduced by total paid</div>
                                <div><code>[due_ref_no]</code> - automatically set the ref due for payment.</div>
                            </small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card p-0">
                            <div class="card-header"> NOTIFICATION </div>
                            <div class="card-body pt-0">
                                
                                <label class="mb-0 mt-2">TYPE:</label>
                                <select v-model="sms_notify_sched.notification_type" class="form-control">
                                    <option value="payment">PAYMENT</option>
                                    <option value="announcement">ANNOUNCEMENT</option>
                                </select>
                                <div v-if="sms_notify_sched.notification_type == 'payment'">
                                    <input2 v-model="sms_notify_sched.total_due" :placeholder="'Total Due:'" :input_style="'height:35px;'" />
                                    <validation :errors="errors" :field="'total_due'" />
                                    <input2 v-model="sms_notify_sched.total_paid" :placeholder="'Total Paid:'" :input_style="'height:35px;'" />
                                    <div class="mt-2">
                                        <switch2 v-model="sms_notify_sched.is_stop_when_fully_paid" /> STOP WHEN FULLY PAID
                                    </div>
                                </div>
                            </div>
                        </div>
                        <repeat-setting 
                            :set_repeat_info="sms_notify_sched.repeat_info" 
                            :set_repeat_type="sms_notify_sched.repeat_type"
                            :set_repeat_days_after="sms_notify_sched.repeat_days_after"
                            @update:set_repeat_info="sms_notify_sched.repeat_info = $event"
                            @update:set_repeat_type="sms_notify_sched.repeat_type = $event"
                            @update:set_repeat_days_after="sms_notify_sched.repeat_days_after = $event"
                            />
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" v-if="sms_notify_sched.id == 0" @click="save">
                        <span class="fa fa-plus"></span> ADD
                    </button> 
                    <button type="button" class="btn btn-outline-primary" v-else @click="save">
                        <span class="fa fa-save"></span>SAVE
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors=$event"></swal> 
    </div>

</template>

<script>    
    import BoostrapSwitch2Vue from '../../../../../common/BoostrapSwitch2.vue';
    import Select2Vue from '../../../../../common/Select2.vue';
    import UserInput2Vue from '../../../../../common/UserInput2.vue';
    import RepeatSetting from '../RepeatSetting.vue';
    import ValidationVue from '../../../../../common/Validation.vue';
    export default {
        props:[ "group_id", "branch_id", "scheduler_id" ],
        $emits:[],
        watch: { 
        },
        components: {
            "input2":UserInput2Vue,
            "select2":Select2Vue,
            "switch2":BoostrapSwitch2Vue,
            "repeat-setting":RepeatSetting,
            "validation":ValidationVue
        },
        data: function () {
            return {        
                promiseExec:null,
                to_type_list:[], 
                errors:[],
                selected_to_type:{
                    id:0,
                    text:''
                },
                selected_sms_sender:{
                    id:0,
                    text:''
                },
                sms_notify_sched:{
                    id:0,
                    name:'',
                    sms_client_api_request_link_id:0,
                    sys_notify_scheduler_id:0,
                    name:'',
                    notification_type:'payment', //payment, announcement
                    to_type:'', //customers,users
                    selected_items:[],
                    send_message:'',
                    mobile_nos:[],
                    total_due:0,
                    total_paid:0,
                    is_active:true,
                    is_stop_when_fully_paid:true,
                    error_message:'',
                    repeat_days_after:0,
                    repeat_type:'yearly', //yearly, monthly, weekly, daily,
                    repeat_info:{
                        month_name:'Jan',
                        month_day:1,
                        week_day:'Mon',
                        datetime:'',
                        time:'08:00'
                    }, //February, Tuesday, 31, 14:00:00
                    others_settings:{}
                }

           }
        },
        methods:{ 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            removeNotifSched:function(item){
                var vm = this;
                vm.sms_notify_sched.selected_items = vm.sms_notify_sched.selected_items.filter(i=>{
                    return i != item;
                });
            },
            to_type_changed:function(val){
                var vm = this;
                
                var to_type = vm.sms_notify_sched.to_type;

                vm.sms_notify_sched.selected_items = [];
                vm.sms_notify_sched.mobile_nos = [];
                vm.sms_notify_sched.to_type = '';

                setTimeout(()=>{
                    vm.sms_notify_sched.to_type = to_type;
                }, 100);

            },
            item_selected_changed:function(val){
                var vm = this;
                if(val.id <= 0) return;

                //CHECK IF EXISTS
                var exists =  vm.sms_notify_sched.selected_items.filter((item)=>{
                    return item.id == val.id;
                });
                if(exists.length > 0){
                    return;
                }
            
                //console.log(val);
                vm.sms_notify_sched.selected_items.push(val);
                vm.sms_notify_sched.mobile_nos.push(val.mobile_no);

                console.log(vm.sms_notify_sched.selected_items);
            
            },
            reset:function(){
                this.to_type_list = []; 
                //if(this.selected_sms_sender.id < 0){
                    this.selected_sms_sender = {
                        id:0,
                        text:''
                    }
                //}
                this.sms_notify_sched = {
                    id:0,
                    name:'',
                    sms_client_api_request_link_id:0,
                    sys_notify_scheduler_id:0,
                    name:'',
                    send_message:'Hi [person_name], \r\n You had balance of [total_balance] from your total due of [total_due] with total paid of [total_paid] ref#:[due_ref_no].\r\n Please settle. If you had already paid please ignore.',
                    notification_type:'payment',
                    to_type:'', //customers,users
                    selected_items:[],
                    mobile_nos:[],
                    total_due:0,
                    total_paid:0,
                    is_active:true,
                    is_stop_when_fully_paid:true,
                    error_message:'',
                    repeat_days_after:0,
                    repeat_type:'yearly', //yearly, monthly, weekly, daily,
                    repeat_info:{
                        month_name:'Jan',
                        month_day:1,
                        week_day:'Mon',
                        datetime:'',
                        time:'08:00'
                    }, //February, Tuesday, 31, 14:00:00
                    others_settings:{}
                }

            },
            show:function(id = 0){ 
                var vm = this;
                vm.reset();
                vm.sms_notify_sched.id = id;

                if(id){
                    WebRequest2('GET', '/api/group/'+this.group_id+'/sys-notification/schedulers/triggers/sms/get/'+id+'?'+this.queryString({
                        branch_id: this.branch_id
                    })).then(resp=>{
                        resp.json().then(data=>{
                            //console.log("Result:", data);

                            if(data.sms_client_api_request_link){
                                vm.selected_sms_sender = {
                                    id:data.sms_client_api_request_link.id,
                                    text:data.sms_client_api_request_link.name
                                }
                            }

                            vm.sms_notify_sched = {
                                id: data.id,
                                name: data.name,
                                sms_client_api_request_link_id: data.sms_client_api_request_link_id,
                                sys_notify_scheduler_id: data.sys_notify_scheduler_id,
                                name: data.name,
                                notification_type: data.notification_type, //payment, announcement
                                to_type: data.to_type, //customers,users
                                selected_items: data.selected_items,
                                send_message: data.send_message,
                                mobile_nos: data.mobile_nos,
                                total_due: data.total_due,
                                total_paid: data.total_paid,
                                is_active: data.is_active,
                                is_stop_when_fully_paid: data.is_stop_when_fully_paid,
                                error_message: data.error_message,
                                repeat_days_after: data.repeat_days_after,
                                repeat_type: data.repeat_type, //yearly, monthly, weekly, daily,
                                repeat_info:data.repeat_info, //February, Tuesday, 31, 14:00:00
                                others_settings: data.others_settings

                            };
                            
                            vm.loadToTypeList().then(data=>{
                                vm.$refs.modal.show();
                            });
                        });
                    });
                }
                else{
                    this.loadToTypeList().then(data=>{
                        vm.$refs.modal.show();
                    });
                }

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            save:function(){

                //console.log(this.sms_notify_sched);
                var vm = this;

                var request = JSON.parse(JSON.stringify(this.sms_notify_sched));
                
                //SELECTED SENDER
                request.sms_client_api_request_link_id = vm.selected_sms_sender.id ? vm.selected_sms_sender.id : null; 


                //GROUPED
                request.branch_id = this.branch_id;
                request.sys_notify_scheduler_id = this.scheduler_id;
                
                //REPEATH SETTINGS
                request.month_name = request.repeat_info.month_name;
                request.month_day = request.repeat_info.month_day;
                request.week_day = request.repeat_info.week_day;
                request.datetime = request.repeat_info.datetime;
                request.time = request.repeat_info.time;
                if(request.id == 0){
                    vm.$refs.swal_prompt.alert(
                        'question',
                        "Add Trigger Now?", 
                        "Confirm" , 
                        "POST", 
                        "/api/group/"+this.group_id+"/sys-notification/schedulers/triggers/sms/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        //console.log(vm.errors);
                        if(res.isConfirmed && res.value.status == 1){
                            vm.sms_notify_sched.id = res.value.data_id;
                        }
                    });
                }
                else {
                    vm.$refs.swal_prompt.alert(
                        'question',
                        "Add Trigger Now?", 
                        "Confirm" , 
                        "PUT", 
                        "/api/group/"+this.group_id+"/sys-notification/schedulers/triggers/sms/update", 
                        JSON.stringify(request)
                    ).then(res=>{
                        //console.log(vm.errors);
                        if(res.isConfirmed && res.value.status == 1){
                            vm.sms_notify_sched.id = res.value.data_id;
                        }
                    });
                }

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
            loadToTypeList:function(){
                var vm = this;
                return WebRequest2('GET', "/api/group/"+this.group_id+"/sys-notification/to-type-list").then(resp=>{

                    return resp.json().then(data=>{
                        vm.to_type_list = data;
                        if(data.length > 0){
                            vm.sms_notify_sched.to_type = data[0];
                        }
                        return data;
                    });
                
                });
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
