<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Verify Email
            </template> 
            <template slot="body" >     
                <div class="input-group text-lg input-group-lg mb-1">
                    <input type="text" class="form-control"/>
                    <span class="input-group-text btn btn-info"><small class="fa fa-arrow-up"></small></span>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div> 
</template>

<script>
    export default {
        props:[  ],
        components: {   
        },
        data: function () {
            return {        
                promiseExec:null
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
