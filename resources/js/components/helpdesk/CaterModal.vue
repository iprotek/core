<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Name Details for Support
            </template> 
            <template slot="body" >     
                <user-input2 v-model="account_no" :placeholder="'Account No#:'" :input_style="'height:40px;'" :placeholder_description="'Support Account Number.'"></user-input2>
                <user-input2 v-model="name" :placeholder="'Full name of Support'" :input_style="'height:40px'" :placeholder_description="'Name of the support will cater the ticket.'"></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" @click="caterTicket()"> CATER NOW </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import SwalVue from '../common/Swal.vue';
    import UserInput2Vue from '../common/UserInput2.vue';
    
    export default {
        props:[ "data" ],
        components: {
            "user-input2":UserInput2Vue,
            "swal":SwalVue
        },
        data: function () {
            return {        
                promiseExec:null,
                account_no:'',
                name:''
           }
        },
        methods:{ 
            caterTicket:function(){
                var  vm = this;
                var request = {
                    action:'cater',
                    account_no: this.account_no,
                    name: this.name
                };
                this.$refs.swal_prompt.alert(
                    'question',
                    "Cater Now?", 
                    "Confirm" , 
                    "POST", 
                    vm.data.response_url, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){ 
                        var val = res.value; 
                        //if(val && val.status == 1){ 
                        //}
                        setTimeout(()=>{
                            window.location.reload();
                        }, 2000);
                    }
                });
            },
            reset:function(){

            },
            show:function(){ 
                var vm = this;
                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            add:function(){
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
