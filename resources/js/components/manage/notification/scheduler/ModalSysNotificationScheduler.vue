<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
               SCHEDULER NAME
            </template> 
            <template slot="body" >
                <div>
                    <input2 :input_style="'height:40px;'" :placeholder="'Notification Name'" v-model="schedule_info.name" :placeholder_description="'The name of the main control of your notification.'" />
                    <label  class="mt-4">TYPE:</label>
                    <select v-model="schedule_info.type" class="form-control">
                        <option value="sms" >SMS</option>
                        <option value="email">EMAIL</option>
                        <option value="notification" >NOTIFICATION</option>
                    </select>
                    <div>
                        <code v-if="schedule_info.type == 'sms'">Send SMS based on alloted schedules.</code>
                        <code v-else-if="schedule_info.type == 'email'">Send EMAIL based on alloted schedules.</code>
                        <code v-else-if="schedule_info.type == 'notification'">System notification such as task and reminders.</code>
                    </div>
                    <label class="mt-4">IS ACTIVE:</label>
                    <div class="mt-1">
                        <switch2 v-model="schedule_info.is_active"/>
                    </div>
                    
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" @click="save">SAVE</button> 
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
        props:[ "group_id", "branch_id" ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "input2":UserInput2Vue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                schedule_info:{
                    is_active:true,
                    type:'sms',
                    name:'',
                    id:0,
                    branch_id: this.branch_id
                }

           }
        },
        methods:{ 
            reset:function(){
                this.schedule_info = {
                    is_active:true,
                    type:'sms',
                    name:'',
                    id:0,
                    branch_id: this.branch_id
                }
            },
            show:function(id = 0){ 
                
                var vm = this;
                
                vm.reset();

                this.$refs.modal.show();

                vm.schedule_info.id = id;

                //this.$refs.modal.show_success("GG");

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            save:function(){
                
                var vm = this;
                
                var request = vm.schedule_info;

                this.$refs.swal_prompt.alert(
                    'question',
                    "Add Schedule", 
                    "Confirm" , 
                    "POST", 
                    "/api/group/"+this.group_id+"/sys-notification/schedulers/add", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        vm.$emit('data_updated');
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
