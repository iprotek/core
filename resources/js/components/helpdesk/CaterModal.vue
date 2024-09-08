<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Name Details for Support
            </template> 
            <template slot="body" >     
                <user-input2 v-model="account_no" :placeholder="'Account No#:'" :input_style="'height:40px;'" :placeholder_description="'Numeric value for Account number.'"></user-input2>
                <user-input2 v-model="name" :placeholder="'Full name of Support'" :input_style="'height:40px'" :placeholder_description="'Name of the support will cater the ticket.'"></user-input2>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" > CATER NOW </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../common/UserInput2.vue';
    
    export default {
        props:[  ],
        components: {
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {        
                promiseExec:null,
                account_no:'',
                name:''
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 

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
