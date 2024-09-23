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
                        <table class="table custom-red-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>To Number</th>
                                    <th>Messages</th>
                                    <th>Status</th>
                                    <th>Sender Id</th>
                                    <th>Sender Number</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <th colspan="8" class="text-center">
                                        <code> Loading Messages </code>
                                    </th>
                                </tr>
                                <tr v-else-if="messageItems.length == 0">
                                    <th colspan="8" class="text-center">
                                        <code> No Message Found </code>
                                    </th>
                                </tr> 
                                <tr v-for="(msg, msgIndex) in messageItems" v-bind:key="'msg-'+msg.id+'-'+msgIndex">
                                    <th v-text="msg.id"></th>
                                    <td v-text="msg.to_number"></td>
                                    <td v-text="msg.message"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
            }
        },
        mounted:function(){     
            this.loadMessages();
        },
        updated:function(){

        }
    }
</script>
