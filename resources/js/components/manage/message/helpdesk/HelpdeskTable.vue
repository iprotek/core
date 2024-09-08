<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Helpdesk Tickets</div> 
                    <div class="card-body">  
                        <div class="input-group text-sm mb-1"> 
                            <span class="btn btn-default" @click="createNewTicket()">
                                <small title="Create Ticket" class="fa fa-plus text-primary"></small> 
                            </span>
                            <span class="btn btn-default">
                                <small title="Show" class="fa fa-search text-primary"></small> 
                            </span>
                            <input class="form-control" type="text" v-model="search" @keyup.enter="loadTicketList()" />
                        </div>
                        <table class="w-100 table custom-red-table">
                            <thead>
                                <tr>
                                    <th>Ticket No#</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Requestor</th>
                                    <th>Contacts</th>
                                    <th>Chats</th>
                                    <th>Status</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <th colspan="8" class="text-center">
                                        <code> -- LOADING TICKETS -- </code>
                                    </th>
                                </tr>
                                <tr v-else-if="ticketList.length == 0">
                                    <th colspan="8" class="text-center">
                                        <code> -- NO TICKET FOUND  -- </code>
                                    </th>
                                </tr>
                                <tr v-for="(ticket,ticketIndex) in ticketList" v-bind:key="'ticket-item-'+ticket.id+'-'+_uid+'-'+ticketIndex">
                                    <th v-text="ticketNumbering(ticket.id)"></th>
                                    <td>
                                        <small v-text="ticket.ticket_type"></small>
                                    </td>
                                    <td v-text="limitString(ticket.title)" style="max-width:500px;"></td>
                                    <td >
                                        <small>
                                            <small class="text-primary" v-text="ticketNumbering(ticket.customer_account_no)"></small>
                                            <span v-text="ticket.customer_name"></span>
                                        </small>
                                    </td>
                                    <td >
                                        <small>
                                            <span v-text="'Email: '+ ticket.customer_email"></span> | <span v-text="'Contact:'+ticket.customer_contact_no"></span>
                                        </small>
                                    </td>
                                    <td>
                                        <div v-if="ticket.message_count">
                                            <span class="far fa-comments text-success" ></span> <span v-text="ticket.message_count"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span v-if="!ticket.status" class="text-warning"> -- PENDING --</span>
                                        <div v-else>
                                            <span v-if="ticket.cater_at && ticket.current_status_id == 0" class="text-info"> CATERED </span>
                                            <span v-else-if=" ticket.current_status_id == 1" class="text-success" v-text="ticket.status.status_name"></span>
                                            <span v-else-if=" ticket.current_status_id == 2" class="text-danger" v-text="ticket.status.status_name"></span>
                                            <span v-else-if=" ticket.current_status_id == 3" class="text-primary" v-text="ticket.status.status_name"></span>
                                            <span v-else-if=" ticket.current_status_id == 4" class="text-danger" v-text="ticket.status.status_name"></span>
                                            <span v-else-if=" ticket.current_status_id == 5" class="text-secondary" v-text="ticket.status.status_name"></span>
                                            <span v-else> -- PENDING --</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" @click="showTicket(ticket.id)">
                                            <span class="fa fa-eye"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <page-footer v-model="pageData" @page_changed="page_changed"></page-footer>
                                    </td>
                                </tr>
                            </tfoot>

                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script> 
    import PageFooterVue from '../../../common/PageFooter.vue';

    export default {
        props:[  ],
        components: {
                "page-footer":PageFooterVue
        },
        data: function () {
            return {  
                pageData:null,
                ticketList:[],
                isLoading:false,
                current_page:1, 
                search:''
            }
        },
        methods: { 
            createNewTicket:function(){
                var  vm = this;
                if(window.add_edit_ticket_modal)
                    window.add_edit_ticket_modal.show().then(res=>{
                        //console.log(res);
                        if(res.status == 1){
                            vm.loadTicketList();
                        }
                    });
            },
            showTicket:function(id){
                var vm = this;
                if(window.add_edit_ticket_modal)
                    window.add_edit_ticket_modal.show(id).then(res=>{
                        if(res.status == 1){
                            vm.loadTicketList();
                        }
                    });

            },
            limitString:function(text, limit=20){
                if(text.length > 20)
                    return text.substring(0, limit)+'...';

                return text;
            },
            ticketNumbering:function(number){
                number = number+'';
                if(number.length > 7)
                    return number;


                number = "0000000"+number;
               return  number.substring(number.length - 7);
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadTicketList();
            },
            loadTicketList:function(){
                var vm = this;
                vm.ticketList = [];
                vm.isLoading = true;
                WebRequest2('GET', '/manage/sms-sender/ticket/list?'+this.queryString({
                    page: this.current_page,
                    search_text: this.search,
                    items_per_page: 10
                })).then(resp=>{

                    vm.isLoading = false;
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.pageData = data;
                        vm.ticketList = data.data;
                    });

                });
            }

        },
        mounted:function(){
            this.loadTicketList();   
        },
        updated:function(){

        }
    }
</script>
