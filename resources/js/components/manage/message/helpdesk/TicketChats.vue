<template>
    <div>
        <div class="row">
            <div class="col-md-12 mt-2 ">
                <page-footer v-model="pageData" :is_reverse="true" @page_changed="page_changed"></page-footer>
                <div class="card w-100">
                    <div class="card-header">Ticket Chats</div> 
                    <div class="card-body"> 
                        <table class="w-100 table table-bordered">
                            <tbody>
                                <tr v-for="(chatItem, chatIndex) in chatList" v-bind:key="'chat-'+chatItem.id+'-'+_uid+'-'+chatIndex">
                                    <td>
                                        <label :class="'m-0 '+(chatItem.is_end_user ? 'text-secondary':'text-primary')" v-text="chatItem.chat_by_name"></label>
                                        <small class="text-secondary" v-text="chatItem.created_at"></small>
                                        <div v-text="chatItem.message" style="text-indent:15px;"></div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <div class="input-group input-group-lg text-sm mb-1">
                                            <input  v-model="message" type="text" :class="'form-control '+(isSubmitting ? 'disabled':'')" @keyup.enter="add_message"> 
                                            <span :class="'btn btn-outline-primary '+( message.trim() == '' || isSubmitting  ? 'disabled':'')" @click="add_message">
                                                    <small title="Submit" class="fa fa-paper-plane text-primary fa-2x"></small>
                                            </span>  
                                        </div>
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
    import PageFooterVue from '../../../common/PageFooter.vue'
    export default {
        props:[ "ticket_id" ],
        components: {
            "page-footer":PageFooterVue
        },
        data: function () {
            return {
                pageData:null,
                chatList:[],
                current_page:1,
                isLoading:false,
                message:'',
                isSubmitting:false
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadChats();
            },
            loadChats:function(){
                var vm = this;
                vm.isLoading = true;
                WebRequest2('GET', '/manage/sms-sender/ticket/'+this.ticket_id+'/messages?'+this.queryString({
                    page: this.current_page
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.chatList = data.data.reverse();
                        vm.$emit('messages_result', vm.pageData);
                        //console.log("Data", vm.chatList);
                    });
                });

            },
            add_message:function(){
                var vm = this;
                if(!this.message.trim())
                    return;
                var request = {
                    message: this.message
                };
                this.isSubmitting =  true;
                WebRequest2('POST', '/manage/sms-sender/ticket/'+this.ticket_id+'/message-add', JSON.stringify(request)).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{ 
                        console.log("Result", data);
                        vm.message = '';
                        vm.isSubmitting = false;
                        if(data.status == 1){
                            vm.current_page = 1;
                            vm.loadChats();
                        }
                    });
                });

            }

        },
        mounted:function(){ 
            this.loadChats();
        },
        updated:function(){

        }
    }
</script>
