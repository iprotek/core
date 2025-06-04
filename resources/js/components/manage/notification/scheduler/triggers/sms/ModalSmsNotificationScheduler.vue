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
                        
                        <div class="mt-2">
                            <label class="mb-0 mt-2">IS ACTIVE:</label>
                            <switch2 v-model="sms_notify_sched.is_active" />
                        </div>

                        <div class="mt-2" >
                            <label class="mb-0"> SMS SENDER </label>
                            <select2 v-model="selected_sms_sender" :placeholder="' -- SMS SENDER -- '" :url="'/manage/sms-sender/list'" :modal_selector="true" />
                        </div>
                        
                        <div>
                            <label class="mb-0 mt-2">TO TYPE:</label>
                            <select @change="to_type_changed" v-model="sms_notify_sched.to_type" class="form-control">
                                <option v-for="(item,itemIndex) in to_type_list" :value="item" v-bind:key="'item-type-'+item+'-'+itemIndex"> {{ item }}</option>
                            </select>
                        </div>

                        <div v-if="sms_notify_sched.to_type">
                            <select2 @selected="item_selected_changed" :is_clean_after_select="true" :has_clear="true" :placeholder="'-- select '+sms_notify_sched.to_type+'--'" :url="'/list/'+sms_notify_sched.to_type" :modal_selector="true" />
                        </div>
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
                                <div><code>[total_due]</code> - automatically set based on total due</div>
                                <div><code>[total_paid]</code> - automatically adjust based on total paid</div>
                                <div><code>[total_balance]</code> - automatically set from total due deduced by total paid</div>
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
                            @update:set_repeat_info="sms_notify_sched.repeat_info = $event"
                            @update:set_repeat_type="sms_notify_sched.repeat_type = $event"
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
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import BoostrapSwitch2Vue from '../../../../../common/BoostrapSwitch2.vue';
    import Select2Vue from '../../../../../common/Select2.vue';
    import UserInput2Vue from '../../../../../common/UserInput2.vue';
    import RepeatSetting from '../RepeatSetting.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: {
            "input2":UserInput2Vue,
            "select2":Select2Vue,
            "switch2":BoostrapSwitch2Vue,
            "repeat-setting":RepeatSetting
        },
        data: function () {
            return {        
                promiseExec:null,
                to_type_list:[], 
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
                    sys_notify_schedule_id:0,
                    name:'',
                    notification_type:'payment', //payment, announcement
                    to_type:'', //customers,users
                    selected_items:[],
                    send_message:'',
                    mobile_nos:[],
                    total_due:0,
                    total_paid:0,
                    is_active:false,
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
                this.sms_notify_sched = {
                    id:0,
                    name:'',
                    sms_client_api_request_link_id:0,
                    sys_notify_schedule_id:0,
                    name:'',
                    send_message:'Hi, \r\n You had balance of [total_balance] from your total due of [total_due] with total paid of [total_paid].\r\n Please settle immediately. If you had already paid please ignore.',
                    notification_type:'payment',
                    to_type:'', //customers,users
                    selected_items:[],
                    mobile_nos:[],
                    total_due:0,
                    total_paid:0,
                    is_active:false,
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
                this.loadToTypeList().then(data=>{
                    vm.$refs.modal.show();
                });

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            save:function(){
                console.log(this.sms_notify_sched);
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
