<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
               <label v-if="id == 0"> SMS Client Add </label>
               <label v-else> SMS Client Edit </label>
            </template> 
            <template slot="body" >     
                <user-input2 v-model="name" :placeholder="'Custom name for API'" :input_style="'height:37px;'"></user-input2>
                <label class="mb-0">Priority:</label>
                <select class="form-control mb-2" v-model="priority">
                    <option :value="1">Priority 1</option>
                    <option :value="2">Priority 2</option>
                    <option :value="3">Priority 3</option>
                </select>
                <div class="card">
                    <div class="card-header">Credential Info (Required and Most Important)</div>
                    <div class="card-body pt-0">
                        <user-input2 v-model="api_name" :placeholder="'Name/Company'" :input_style="'height:37px;'"></user-input2>
                        <user-input2 v-model="api_username" :placeholder="'Username'" :input_style="'height:37px;'"></user-input2>
                        <user-input2 :type="'password'" v-model="api_password" :placeholder="'Password'" :input_style="'height:37px;'"></user-input2>
                        <user-input2 v-model="api_url" :placeholder="'API URL'" :input_style="'height:37px;'"></user-input2> 
                    </div>
                </div>
                <div>
                    <switch2 v-model="is_active"></switch2> is Active
                </div>
                <div v-if="is_active == false && inactive_reason">
                    <code v-text="inactive_reason"></code>
                </div>
                
                <div v-if="id">
                    <switch2 v-model="is_webhook_active"></switch2> Active webhook?
                </div>
                <div v-if="id">
                    <button-copy :base_color="'primary'" :button_title="'Click to Copy Webhook URL'" :text_to_copy="webhook_response_url" :base_icon="'fa fa-link'"  :copied_message="'Webhook copied!'" ></button-copy>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button v-if="id == 0" class="btn btn-outline-primary"  @click="add"><span class="fa fa-plus"></span> ADD </button> 
                    <button v-else class="btn btn-outline-warning"  @click="add"><span class="fa fa-edit"></span> UPDATE </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import ButtonCopyVue from '../../common/ButtonCopy.vue';
    import UserInput2Vue from '../../common/UserInput2.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue,
            "button-copy":ButtonCopyVue
        },
        data: function () {
            return {        
                id:0,
                promiseExec:null,
                name:'',
                api_name:'',
                api_username:'',
                api_password:'',
                api_url:'',
                is_active:true,
                inactive_reason:'',
                priority:1,
                webhook_response_url:'',
                is_webhook_active:''
           }
        },
        methods:{ 
            reset:function(){
                
                this.id = 0; 
                this.name = '';
                this.api_name = '';
                this.api_username= '';
                this.api_password= '';
                this.api_url= '';
                this.is_active= true;
                this.inactive_reason= '';
                this.priority= 1;
                this.webhook_response_url= '';
                this.is_webhook_active= ''
            },
            show:function(id = 0, is_reload=false){ 
                var vm = this;
                vm.reset();
                vm.id = id;
                if(is_reload == false)
                vm.$refs.modal.show();
                if(vm.id){
                    vm.loadInfo(id);
                }

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            loadInfo:function(id){
                var vm = this;
                WebRequest2('GET', '/manage/sms-sender/list/'+id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.id = data.id;
                        vm.name = data.name;
                        vm.api_name = data.api_name;
                        vm.api_username = data.api_username;
                        //vm.api_password = data.api_password;
                        vm.api_url = data.api_url;
                        vm.is_active = data.is_active;
                        vm.inactive_reason = data.inactive_reason;
                        vm.priority = data.priority;
                        vm.webhook_response_url = data.webhook_response_url;
                        vm.is_webhook_active = data.is_webhook_active;
                        vm.promiseExec(data);
                    });
                });
            },
            add:function(){
                var vm = this;
                var request = {
                    name: this.name,
                    api_name: this.api_name,
                    api_username: this.api_username,
                    api_password: this.api_password,
                    api_url: this.api_url,
                    is_active: this.is_active,
                    priority: this.priority,
                    is_webhook_active: this.is_webhook_active
                };
                if(vm.id == 0){
                    vm.$refs.swal_prompt.alert(
                        'question',
                        "Add API Client", 
                        "Confirm" , 
                        "POST", 
                        "/manage/sms-sender/add-client", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            if(res.value.status == 1){
                                vm.show( res.value.data.id, true);
                                vm.$emit('data_updated');
                            }
                        }
                    }); 
                }else{
                    vm.$refs.swal_prompt.alert(
                        'question',
                        "Update API Client", 
                        "Confirm" , 
                        "PUT", 
                        "/manage/sms-sender/update-client/"+this.id, 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            if(res.value.status == 1){
                                vm.$emit('data_updated');
                            }
                        }
                    }); 

                }
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
