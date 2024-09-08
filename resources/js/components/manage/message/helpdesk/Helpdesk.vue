<template>
    <div>
        <div>
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="ion ion-help-buoy text-warning"></i> 
                <span v-if="total_tickets > 0"  class="badge badge-danger navbar-badge" v-text="total_tickets"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a  class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">
                            <small  class="text-sm text-secondary">
                                <small>Send a ticket for support customer/system.</small>
                            </small>
                            <div>
                                <button class="btn btn-outline-primary btn-sm" @click="clickAddEditTicketModal()" > Submit New Ticket </button>
                            </div>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                
                <a v-if="isLoading" href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <code class="w-100 text-center"> -- Loading Ticket -- </code>
                    </div>
                    <!-- Message End -->
                </a> 
                <template v-else-if="ticket_list.length == 0"> 
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <code class="w-100 text-center"> -- No ticket at this moment -- </code>
                        </div>
                        <!-- Message End -->
                    </a> 
                </template>
                <template  >
                    <div v-for="(ticketItem, ticketIndex) in ticket_list" v-bind:key="'ticket-item-'+_uid+'-'+ticketItem.id+'-'+ticketIndex">
                        <a href="#" class="dropdown-item" @click="clickAddEditTicketModal(ticketItem.id)">
                            <!-- Message Start -->
                            <div class="media"> 
                                <div class="media-body">
                                    <h3 class="dropdown-item-title"> 
                                        <span class="text-nowrap">
                                            <b v-text="limitString(ticketItem.title)"></b>
                                        </span>
                                        <span v-if="ticketItem.cater_by_id" class="float-right text-sm text-success" title="Catered"><i class="fas fa-star"></i></span> 
                                        <span  v-else class="float-right text-sm text-warning" title="Waiting"><i class="far fa-clock"></i></span> 
                                    </h3>
                                    <small class="text-sm">
                                        <small>
                                            <i class="fa fa-ticket text-info mr-1"></i> 
                                            <b class="text-secondary" v-text="ticketNumbering(ticketItem.id)"></b> 
                                            <i class="fa fa-user text-primary mx-1" title="Requestor"></i> 
                                            <span v-if="ticketItem.ticket_type == 'customer' " v-text="ticketItem.customer_name" ></span>
                                            <span v-else-if="ticketItem.ticket_type == 'system-support' && ticketItem.creator " v-text="ticketItem.creator.name" ></span>
                                            <span v-if="ticketItem.cater_by_name">
                                                <i  class="fa fa-wrench text-success mx-1" :title="ticketItem.cater_by_name"></i> Catered
                                            </span>
                                            
                                        </small>
                                    </small>
                                    <div>
                                        <small class="text-sm text-muted">
                                            <small v-if="ticketItem.ticket_type == 'customer'" class="text-primary" v-text="ticketItem.ticket_type"></small>
                                            <small v-else class="text-danger" v-text="ticketItem.ticket_type"></small>
                                            <small>
                                                <i class="far fa-clock mr-1"></i> 
                                                <span v-text="ticketItem.created_diff"> </span> 
                                            </small>
                                            <small class="btn btn-outline-success px-1 py-0" @click.stop="ticketSendEmail(ticketItem.id)">
                                                <small class="fa fa-paper-plane"></small>
                                            </small>
                                            <small class="btn btn-outline-primary px-1 py-0" title="Chats" v-if="ticketItem.message_count">
                                                <small class="fa fa-comments"></small> <small v-text="ticketItem.message_count"></small>
                                            </small>
                                        </small> 
                                    </div>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </template>
                <a href="/manage/helpdesk" id="link-see-tickets-el" class="dropdown-item dropdown-footer text-primary" style="border:1px solid blue;">See All Tickets</a>
            </div>
        </div> 
    </div>
</template>

<script> 
    export default {
        props:[  ],
        components: {  
        },
        data: function () {
            return {
                has_chat:false,
                ticket_list:[],
                isLoading:false,
                total_tickets: 0
            }
        },
        methods: { 
            ticketSendEmail:function(id){
                console.log("Ticket Send email"+id);
                var request = {

                };
                window.swal_prompt.alert(
                    'question',
                    "Notify this to recepient?", 
                    "Confirm" , 
                    "POST", 
                    "/", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){ 
                        var val = res.value;
                        if(val && val.status == 1){ 
                            //Reload List
                             window.loadHelpdesk();
                        }
                    }
                });
            },
            ticketNumbering:function(number){
                number = number+'';
                if(number.length > 7)
                    return number;
                
                number = "0000000"+number;
               return  number.substring(number.length - 7);
            },
            limitString:function(text){
                if(!text) return '';
                if(text.length > 20)
                    return text.substring(0, 20)+'...';
                return text;
            },
            clickAddEditTicketModal:function(id = 0){
                window.add_edit_ticket_modal.show(id);
            },
            loadHelpdesk:function(){ 
                var vm = this;
                //vm.ticket_list = [];
                vm.isLoading = true;
                vm.total_tickets = 0;
                WebRequest2('GET', '/manage/sms-sender/ticket/list?action=notification').then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.ticket_list = data.data;
                        vm.total_tickets = data.total;
                    });
                });
            }
        },
        mounted:function(){   
            this.loadHelpdesk();
            window.loadHelpdesk = this.loadHelpdesk;
        },
        updated:function(){

        }
    }
</script>
