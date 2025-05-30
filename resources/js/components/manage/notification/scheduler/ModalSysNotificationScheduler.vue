<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                SYS NOTIFICATION SCHEDULER TYPE
            </template> 
            <template slot="body" >
                <div>
                    <input2 :input_style="'height:40px;'" :placeholder="'Notification Name'" v-model="name" :placeholder_description="'The name of the main control of your notification.'" />
                    <label>TYPE:</label>
                    <select v-model="type" class="form-control">
                        <option value="sms" >SMS</option>
                        <option value="email">EMAIL</option>
                        <option value="notification" >NOTIFICATION</option>
                    </select>
                    <div>
                        <code v-if="type == 'sms'">Send SMS based on alloted schedules.</code>
                        <code v-else-if="type == 'email'">Send EMAIL based on alloted schedules.</code>
                        <code v-else-if="type == 'notification'">System notification such as task and reminders.</code>
                    </div>
                    <label>IS ACTIVE:</label>
                    <div class="mt-1">
                        <switch2 v-model="is_active"/>
                    </div>
                    
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary">SAVE</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import BoostrapSwitch2Vue from '../../../common/BoostrapSwitch2.vue';
    import UserInput2Vue from '../../../common/UserInput2.vue';

    export default {
        props:[  ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "input2":UserInput2Vue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                is_active:true,
                type:'sms',
                name:''
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                var vm = this;

                this.$refs.modal.show();
                //this.$refs.modal.show_success("GG");

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
