<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="60">
            <template slot="header" >
                <h3 v-if="id == 0" class="text-primary">
                    SUBMIT HELPDESK TICKET
                </h3>
                <h3 v-else class="text-info">
                    HELPDESK INFO
                </h3>
            </template> 
            <template slot="body" > 
                <div v-if="view_mode == 'details'">    
                    <div class="row mt-2" v-if="id>0">
                        <div class="col-sm-9">
                            <div v-if="id>0 && ticket_info">
                                <label>Created by: 
                                    <span v-if="ticket_info.creator" v-text="ticket_info.creator.name"></span> 
                                    <span v-else-if="ticket_info.customer_name" v-text="ticket_info.customer_name"></span> 
                                </label>
                                <div>
                                    <label>Created at: <span v-text="ticket_info.created_at"></span> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-outline-primary float-right" @click="view_mode = 'chats'">
                                CHATS (0) <span class="fa fa-arrow-right"></span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <user-input2 v-model="title" :input_style="'height:40px;'" :placeholder="'Title'" :placeholder_description="'Descriptive Title for helpdesk'"></user-input2>
                        
                        <small class="text-secondary mt-2">Ticket Type:</small>
                        <select class="form-control" v-model="ticket_type">
                            <option value="customer">Customer</option>
                            <option value="system-support">System Support</option>
                        </select>
                        <div>
                            <small>
                                <code v-if="ticket_type == 'customer'">Create ticket in behalf of customer</code>
                                <code v-if="ticket_type == 'system-support'">Create ticket for system developer in terms of system errors etc.</code>
                            </small>
                        </div>
                        <div class="card mt-2" v-if="ticket_type == 'customer'"> 
                            <div class="card-body pt-0">
                                <div class="row mt-2">
                                    <div class="col-sm-12"> <h4 class="mb-0"> Customer Info </h4> </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <user-input2 v-model="customer_account_no" :input_style="'height:40px;'" :placeholder="'Account Number'" :placeholder_description="'Billing account number of the customer'"></user-input2>
                                    </div>
                                    <div class="col-sm-6">
                                        <user-input2 v-model="customer_name" :input_style="'height:40px;'" :placeholder="'Customer Name'" :placeholder_description="'Name of the Customer'"></user-input2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <user-input2 v-model="customer_email" :input_style="'height:40px;'" :placeholder="'Email'" :placeholder_description="'Email of the Customer'"></user-input2>
                                    </div>
                                    <div class="col-sm-6">
                                        <user-input2 v-model="customer_contact_no" :input_style="'height:40px;'" :placeholder="'Contact#'" :placeholder_description="'Contact Number of the Customer'"></user-input2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <summernote v-model="details" :height="'300px'" :placeholder="'Descriptive Details'"  :is_local="false" :is_image_upload="true"  ></summernote>
                        </div>
                        <div v-if="!cater_by_id " class="text-right">
                            <button v-if="id>0 && ticket_type == 'customer'" class="btn btn-outline-primary btn-lg" @click="caterTicket">
                                CATER THIS TICKET?
                            </button>
                            <label v-else-if="id>0" class="text-warning"> -- Waiting for the system support to cater -- </label>
                        </div>
                        <div class="card" v-else-if="id>0">
                            <div   class="card-body">
                                <div v-if="ticket_info"> 
                                    <label>Catered by: <span v-text="ticket_info.cater_by_name"></span> </label>
                                    <div>
                                        <label>Catered at: <span v-text="ticket_info.cater_at"></span> </label>
                                    </div>
                                </div>
                                <div>
                                    <div>Status:</div>
                                    <div>
                                        <select v-model="current_status_id" class="form-control">
                                            <option :value="0">Pending</option>
                                            <option :value="1">Completed</option>
                                            <option :value="2">Failed</option>
                                            <option :value="3">Solved</option>
                                            <option :value="4">Cancelled</option>
                                            <option :value="5">Close</option> 
                                        </select>
                                        <textarea v-model="status_remarks" class="w-100" style="height: 80px" placeholder="Remarks">

                                        </textarea>
                                        <button class="btn btn-outline-primary" @click="updateTicketStatus"> UPDATE STATUS </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>  
                    <div class="text-left mt-2">
                        <button class="btn btn-outline-primary" @click="view_mode = 'details'">
                            <span class="fa fa-arrow-left"></span> DETAILS
                        </button>
                    </div>

                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" v-if="id == 0 && view_mode == 'details'" @click="submit_ticket()"  >
                        <span class="fa fa-paper-plane"></span>
                        SUBMIT TICKET
                    </button> 
                </div>
            </template>
        </modal-view>
    </div>
</template>

<script> 
    import SummernoteVue from '../../../common/Summernote.vue';
    import UserInput2Vue from '../../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {
            "user-input2":UserInput2Vue,
            "summernote":SummernoteVue
        },
        data: function () {
            return {
                id:0,
                title:'',
                details:'',
                ticket_type:'system-support',
                customer_account_no:'',
                customer_name:'',
                customer_email:'',
                customer_contact_no:'', 
                current_status_id:0,
                status_remarks:'',
                view_mode:'details',
                cater_by_id:0,
                cater_by_name:'',
                ticket_info:null,
                promiseExec:null
           }
        },
        methods:{ 
            updateTicketStatus:function(){
                var vm = this;
                var request = {
                    status_id: this.current_status_id,
                    remarks: this.status_remarks
                };
                window.swal_prompt.alert(
                    'question',
                    "Cater this ticket?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/sms-sender/ticket/update-status/"+this.id, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        //vm.$emit('data_updated');
                        //console.log(res.value);
                        var val = res.value;
                        vm.promiseExec(val);
                        if(val && val.status == 1){
                            window.loadHelpdesk();
                        }
                    }
                });

            },
            caterTicket:function(){
                var vm = this;
                window.swal_prompt.alert(
                    'question',
                    "Cater this ticket?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/sms-sender/ticket/cater/"+this.id, 
                    '{}'
                ).then(res=>{
                    if(res.isConfirmed){
                        //vm.$emit('data_updated');
                        //console.log(res.value);
                        var val = res.value;
                        vm.promiseExec(val);
                        if(val && val.status == 1){
                            //vm.id = val.data.id;
                            //Reload List
                            //vm.cater_by_id = val.data.cater_by_id;
                            //vm.cater_by_name = val.data.cater_by_name;
                            window.loadHelpdesk(); 
                            vm.loadInfo(vm.id);
                        }
                    }
                });
            },
            submit_ticket:function(){
                //WebRequest2('POST', '/manage/sms-sender/ticket/add')
                var vm = this;
                var request = {
                    ticket_type: this.ticket_type,
                    title: this.title,
                    details: this.details,
                    customer_account_no: this.customer_account_no,
                    customer_name: this.customer_name,
                    customer_email: this.customer_email,
                    customer_contact_no: this.customer_contact_no
                };
                window.swal_prompt.alert(
                    'question',
                    "Add Ticket?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/sms-sender/ticket/add", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){ 
                        var val = res.value;
                        vm.promiseExec(val);
                        if(val && val.status == 1){
                            vm.id = val.data.id; 
                            vm.ticket_info = val.data;
                             window.loadHelpdesk();
                        }
                    }
                });
            },
            reset:function(){
                this.id = 0;
                this.title = '';
                this.details = '';
                this.view_mode = 'details';
                this.ticket_type = 'system-support';
                this.customer_account_no = '';
                this.customer_contact_no = '';
                this.customer_email = '';
                this.customer_name = '';
                this.cater_by_id = 0;
                this.cater_by_name = '';
                this.ticket_info = null;

                this.current_status_id = 0;
                this.status_remarks = '';
            },
            loadInfo:function(id){
                var vm = this;
                WebRequest2('GET','/manage/sms-sender/ticket/get-info/'+id).then(resp=>{
                    resp.json().then(data=>{
                        if(data){
                            vm.title = data.title;
                            vm.details = data.details;
                            vm.ticket_type = data.ticket_type;
                            vm.customer_account_no = data.customer_account_no;
                            vm.customer_name = data.customer_name;
                            vm.customer_email = data.customer_email;
                            vm.customer_contact_no = data.customer_contact_no;
                            vm.current_status_id = data.current_status_id;
                            vm.cater_by_id = data.cater_by_id;
                            vm.cater_by_name = data.cater_by_name;
                            vm.ticket_info = data;
                            
                            //console.log("Status Info",data);
                            if(data.status){
                                vm.status_remarks = data.status.remarks;
                            }
                            vm.$refs.modal.show();
                        }
                    });
                });
            },
            show:function(id = 0){ 
                var vm = this;
                this.reset();
                this.id = id;

                //Load by details
                if(id){
                    this.loadInfo(id);

                }else{
                    this.$refs.modal.show();
                }

                //
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },

        },
        mounted:function(){      
            if(!window.add_edit_ticket_modal)
                window.add_edit_ticket_modal = this;
        },
        updated:function(){

        }
    }
</script>
