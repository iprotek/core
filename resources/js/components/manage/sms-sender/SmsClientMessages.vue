<template>
    <div >
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">SMS Request Messages</div> 
                    <div class="card-body"> 
                        <button class="btn btn-outline-primary mb-2"  @click="$refs.modal_add_sms_message.show()"> 
                            <span class="fa fa-plus"></span> 
                            CREATE SMS MESSAGE
                        </button>
                        <div class="input-group text-sm my-1">
                            <span class="btn btn-default">
                                <small title="Search" class="fa fa-search text-primary">
                                    </small>
                            </span> 
                            <input v-model="search" type="text" class="form-control" @keyup.enter="loadMessages()">
                            <span class="btn btn-default">
                                <small title="Search" class="text-primary" @click="loadMessages()"> Search </small>
                            </span> 
                        </div>
                        <table class="table custom-red-table">
                            <thead>
                                <tr>
                                    <th style="width:20px;">#</th>
                                    <th style="width:35px;">To Number</th>
                                    <th>Messages</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width:40px;">SenderId</th> 
                                    <th style="width:100px;">Created At</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <th colspan="7" class="text-center">
                                        <code> Loading Messages </code>
                                    </th>
                                </tr>
                                <tr v-else-if="messageItems.length == 0">
                                    <th colspan="7" class="text-center">
                                        <code> No Message Found </code>
                                    </th>
                                </tr> 
                                <tr v-for="(msg, msgIndex) in messageItems" v-bind:key="'msg-'+msg.id+'-'+msgIndex">
                                    <th v-text="msg.id"></th>
                                    <td class="text-nowrap" v-text="msg.to_number"></td>
                                    <td v-text="msg.message"></td>
                                    <td>
                                        <small v-if="msg.status_id == 0" v-text="msg.status_info" class="text-warning"></small>
                                        <small v-else-if="msg.status_id == 1"  v-text="msg.status_info" class="text-success"></small>
                                        <small v-else v-text="msg.status_info" class="text-danger"></small>
                                        <div v-if="msg.sent_at">
                                            <small class="text-primary text-nowrap" v-text="msg.sent_at"></small>
                                        </div>
                                    </td>
                                    <td class="text-center" v-text="msg.sender_id"></td> 
                                    <td >
                                        <small v-text="msg.created_at" class="text-nowrap text-secondary"></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-danger btn-sm" @click="remove_message(msg.id)">
                                            <span class="fa fa-times"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <page-footer></page-footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-add-sms-message ref="modal_add_sms_message" @data_updated="data_updated"></modal-add-sms-message>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script> 
    import PageFooterVue from '../../common/PageFooter.vue';
    import ModalAddSmsClientMessageVue from './ModalAddSmsClientMessage.vue';
    export default {
        props:[ "group_id"  ],
        components: {
            "page-footer":PageFooterVue,
            "modal-add-sms-message":ModalAddSmsClientMessageVue
        },
        data: function () {
            return { 
                isLoading:false,
                pageData:null,
                messageItems:[],
                search:'',
                current_page:1
            }
        },
        methods: { 
            data_updated:function(){
                this.loadMessages();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadMessages:function(){

                var vm = this;
                vm.isLoading = true;
                vm.messageItems = [];

                WebRequest2('GET', '/manage/sms-sender/list-messages?'+this.queryString({search:this.search, page:this.current_page,items_per_page:10})).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.messageItems = data.data;
                    });
                });
            },
            remove_message:function(id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question',
                    "Delete this message?", 
                    "Confirm" , 
                    "DELETE", 
                    "/manage/sms-sender/delete-message/"+id 
                ).then(res=>{
                    if(res.isConfirmed){
                        vm.loadMessages();
                    }
                });
            }
        },
        mounted:function(){     
            this.loadMessages();
        },
        updated:function(){

        }
    }
</script>
