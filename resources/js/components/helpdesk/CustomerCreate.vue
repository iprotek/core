<template>
    <div >
        <div class="row mt-2 mb-4">
            <div class="col-md-12">
                <div v-if="id == 0" class="card">
                
                    <div class="card-header">CREATE TICKET</div>
                    <div class="card-body">
                        <div>
                            <b>Title(*)</b>
                            <input v-model="title" class="form-control" />
                        </div>
                        <div>
                            <b>Message(*)</b>
                            <textarea v-model="message" class="form-control" style="min-height:200px;"></textarea>
                        </div>
                        <div>
                            <b>Account No.(*)</b>
                            <input v-model="account_no" class="form-control" />
                        </div>
                        <div>
                            <b>Full Name(*)</b>
                            <input v-model="full_name" class="form-control" />
                        </div>
                        <div>
                            <b>Email(*)</b>
                            <input v-model="email" class="form-control" />
                        </div>
                        <div>
                            <b>Contact Number(*)</b>
                            <input v-model="contact_no" class="form-control" />
                        </div>
                        <div class="text-right p-2"> 
                            <button class="btn btn-default btn-lg mr-4" @click="cancelTicket">
                                  CANCEL
                            </button>
                            <button class="btn btn-outline-primary btn-lg" @click="createTicket">
                                <span class="fa fa-paper-plane"></span> SUBMIT
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="card">
                    <div class="card-body">
                        <h2> Please take note of your current ticket number: 
                            <div>
                                <span class="text-primary" v-text="ticketNumbering(id)"></span>
                            </div> 
                        </h2>
                        <div>
                            <button class="btn btn-outline-primary btn-lg" @click="newTicket()">
                                <span class="fa fa-paper-plane"></span> SUBMIT NEW TICKET
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal>
    </div>
</template>

<script>
    import SwalVue from '../common/Swal.vue';
    export default {
        props:[  ],
        components: { 
            "swal":SwalVue
        },
        data: function () {
            return {
                id:0,
                title:'',
                message:'',
                account_no:'',
                full_name:'',
                email:'',
                contact_no:''
            }
        },
        methods: { 
            newTicket:function(){
                this.id = 0;
                this.title = '';
                this.message = '';
                this.account_no = '';
                this.full_name = '';
                this.email = '';
                this.contact_no = '';
            },
            
            ticketNumbering:function(number){
                number = number+'';
                if(number.length > 7)
                    return number;


                number = "0000000"+number;
               return  number.substring(number.length - 7);
            },
            cancelTicket:function(){
                window.location.href="/";
            },
            createTicket:function(){
                var vm = this;
                var request = {
                    title: this.title,
                    message: this.message,
                    customer_account_no: this.account_no,
                    customer_name: this.full_name,
                    customer_email: this.email,
                    customer_contact_no: this.contact_no
                };

                this.$refs.swal_prompt.alert(
                    'question',
                    "Submit Now?", 
                    "Confirm" , 
                    "POST", 
                    '/helpdesk/create', 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){ 
                        var val = res.value; 
                        if(val.status == 1){
                            vm.id = val.data.id;
                        }
                    }
                });

            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
