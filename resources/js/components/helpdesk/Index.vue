<template>
    <div>
        <div class="row" v-if="data">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header text-center" >
                        <span>Ticket#</span>
                        <b v-text="ticketNumbering(data.id)"></b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="text-right" style="width:200px;">Ticket Number</td>
                                    <th class="text-primary" v-text="ticketNumbering(data.id)"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >Title</td>
                                    <th v-text="data.title"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >APP NAME</td>
                                    <th v-text="data.app_name"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >APP URL</td>
                                    <th v-text="data.app_url"></th>
                                </tr>
                                <tr>
                                    <td class="text-right align-top"  >
                                        <span>Message </span>
                                     </td>
                                    <th v-text="data.details"></th>

                                </tr>
                                <tr v-if="!data.cater_by_name">
                                    <td colspan="2" class="text-center">
                                        <button class="btn btn-outline-primary btn-xl" @click="$refs.cater_modal.show()"> CATER THIS TICKET? </button>
                                    </td>
                                </tr>
                                <tr >
                                    <td class="text-right"> STATUS </td>
                                    <td v-if="data.status">
                                        <b class="text-primary" v-text="data.status.status_name"></b>
                                        <span v-if="data.status" v-text="' - '+ data.status.remarks"></span>
                                    </td>
                                    <td v-else class="text-danger"> -- PENDING -- </td>
                                </tr>
                                <tr v-if="data.cater_by_name">
                                    <td class="text-right">Cater by Info</td>
                                    <td>
                                        <b v-text="'ID#:'+data.cater_by_id+' Name:'+data.cater_by_name+' @'+data.cater_at"></b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-sm-12 mt-2" v-if="data.ticket_type == 'customer' || data.cater_by_name">
                <div class="card">
                    <div class="card-header" >
                        MESSAGE HERE 
                        <small> <code>**Please be reminded. messages are only limited to 20 from the latest to old.</code> </small>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary btn-sm" v-if="data.current_status_id != 5" @click="$refs.message_modal.show()">
                            <span class="fa fa-comment"></span> ADD MESSAGE 
                        </button>
                        <table class="table table-bordered w-100">
                            <tr v-if="data.chats.length == 0">
                                <td class="text-center">
                                    <code> -- No messages available -- </code>
                                </td>
                            </tr>
                            <tr v-for="(chat, chatIndex) in data.chats" v-bind:key="'chat-item-'+chat.id+'-'+_uid+'-'+chatIndex">
                                <td >
                                    <b v-text="chat.chat_by_name"></b> <small class="text-secondary" v-text="chat.created_at"></small>
                                    <div>
                                        <p v-html="chat.message" class="pb-0 mb-0"></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal>
        <cater-modal :data="data" ref="cater_modal"></cater-modal>
        <message-modal ref="message_modal" :data="data" ></message-modal>
    </div>
</template>

<script>
    import SwalVue from '../common/Swal.vue';
    import CaterModalVue from './CaterModal.vue';
    import MessageModalVue from './MessageModal.vue';
    export default {
        props:[ "data" ],
        components: { 
            "swal":SwalVue,
            "cater-modal":CaterModalVue,
            "message-modal":MessageModalVue
        },
        data: function () {
            return {    
            }
        },
        methods: { 
            ticketNumbering:function(number){
                number = number+'';
                if(number.length > 7)
                    return number;


                number = "0000000"+number;
               return  number.substring(number.length - 7);
            },

        },
        mounted:function(){   
            console.log("Data",this. data);  
        },
        updated:function(){

        }
    }
</script>
