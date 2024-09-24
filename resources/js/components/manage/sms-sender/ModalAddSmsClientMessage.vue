<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Send Message
            </template> 
            <template slot="body" >
                <div class="mt-2"> 
                    <label>Select SMS Api Client</label>
                    <select2  
                        @selected="selectedItem" 
                        v-model="selItem" 
                        :modal_selector="true"
                        :placeholder="'Select SMS Sender'" 
                        :url="'/manage/sms-sender/list-selection'"  ></select2>
                    <user-input2 v-model="to_number" :input_style="'height:37px;'" :placeholder="'To Mobile Number'" :placeholder_description="'Send to mobile number'"></user-input2>
                    
                    <label class="mt-2">Message:</label>
                    <textarea v-model="message" class="form-control" style="height:80px" maxlength="150"></textarea>


                </div>
                     
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <button v-if="id == 0 && selItem.id > 0 && to_number && message" class="btn btn-outline-primary"  @click="send_message"><span class="fa fa-paper-plane"></span> SEND </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import Select2Vue from '../../common/Select2.vue';
    import UserInput2Vue from '../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {
            "select2":Select2Vue,
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {        
                promiseExec:null,
                selItem:{
                    id:0,
                    text:''
                },
                to_number:'',
                message:'',
                id:0
           }
        },
        methods:{ 
            selectedItem:function(val){

            },
            reset:function(){
                this.id = 0;
                this.message = '';
                this.to_number = '';
                this.selItem.id = 0;
                this.selItem.text = '';
            },
            send_message:function(){
                var vm = this;
                //'/manage/sms-sender/send-message/'+this.selItem.id
                var request = {
                    to_number: this.to_number,
                    message: this.message
                }
                this.$refs.swal_prompt.alert(
                    'question',
                    "Would you like to send a message?", 
                    "Send Now" , 
                    "POST", 
                    '/manage/sms-sender/send-message/'+this.selItem.id, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value){
                            if(res.value.status == 1)
                                vm.id = res.value.data.id;
                        }
                        vm.$emit('data_updated');
                    }
                });
            },
            show:function(){ 
                var vm = this;
                vm.reset();

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
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
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
