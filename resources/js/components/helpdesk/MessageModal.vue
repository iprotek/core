<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Message
            </template> 
            <template slot="body" >
                <textarea v-model="message" class="w-100 form-control" style="min-heigth: 250px;"></textarea> 
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <button type="button" class="btn btn-outline-primary" @click="submit_message">
                        <span class="fa fa-paper-plane"></span> Submit    
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import SwalVue from '../common/Swal.vue';
    export default {
        props:[ "data" ],
        components: {   
            "swal":SwalVue
        },
        data: function () {
            return {        
                promiseExec:null,
                message:''
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                var vm = this;
                

                this.$refs.modal.show();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            submit_message:function(){
                var vm = this;
                var request = {
                    action:'message',
                    message: this.message
                };
                this.$refs.swal_prompt.alert(
                    'question',
                    "Send", 
                    "Confirm" , 
                    "POST", 
                    this.data.response_url, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        var val = res.value;
                        if(val.status == 1){
                            setTimeout(()=>{
                                window.location.reload();
                            }, 2000);
                        }
                    }
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
