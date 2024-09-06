<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="60">
            <template slot="header" >
                <h3 v-if="id == 0">
                    SUBMIT HELPDESK TICKET
                </h3>
                <h3 v-else>
                    HELPDESK INFO
                </h3>
            </template> 
            <template slot="body" >     
                <div class="text-right mt-2">
                    <button class="btn btn-outline-primary">
                        CHATS (0) <span class="fa fa-arrow-right"></span>
                    </button>
                </div>
                <div>
                    <user-input2 v-model="title" :input_style="'height:40px;'" :placeholder="'Title'" :placeholder_description="'Descriptive Title for helpdesk'"></user-input2>
                    
                    <small class="text-secondary mt-2">Ticket Type:</small>
                    <select class="form-control" v-model="ticket_type">
                        <option value="customer">Customer</option>
                        <option value="system-support">System Support</option>
                    </select>
                    <div class="card mt-2" v-if="ticket_type == 'customer'"> 
                        <div class="card-body pt-0">
                            <div class="row mt-2">
                                <div class="col-sm-12"> <h4 class="mb-0"> Customer Info </h4> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <user-input2 v-model="customer_account_number" :input_style="'height:40px;'" :placeholder="'Account Number'" :placeholder_description="'Billing account number of the customer'"></user-input2>
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
                        <summernote v-model="description" :height="'300px'" :placeholder="'Descriptive Details'"  :is_local="false" :is_image_upload="true"  ></summernote>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
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
                description:'',
                ticket_type:'system-support',
                customer_account_number:'',
                customer_name:'',
                customer_email:'',
                customer_contact_no:''
           }
        },
        methods:{ 
            reset:function(){
                this.id = 0;
                this.title = '';
                this.description = '';
            },
            show:function(id = 0){ 
                this.reset();
                this.id = id;
                this.$refs.modal.show();
            },

        },
        mounted:function(){      
            window.add_edit_ticket_modal = this;
        },
        updated:function(){

        }
    }
</script>
