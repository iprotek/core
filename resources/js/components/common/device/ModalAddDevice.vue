<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                <label v-text="device_id ? 'UPDATE DEVICE':'ADD DEVICE'"></label>
            </template> 
            <template slot="body" >
                <div>
                    <user-input2 v-model="device_info.name" :placeholder="'Descriptive Name'" :input_style="'height:35px;'" /> 
                    <label class="mt-2 mb-0">Description</label>
                    <textarea v-model="device_info.description" class="w-100 form-control mt-2" style="min-height:120px;"></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="mt-2">
                            <label class="mb-0">Type:</label>
                            <select v-model="device_info.type" class="form-control">
                                <option value="mikrotik">Mikrotik</option>
                                <option value="windows">Windows</option>
                                <option value="ssh">SSH</option>
                            </select>
                        </div>
                        <user-input2 v-model="device_info.host" :placeholder="'Host Name/IP'" :input_style="'height:35px;'" />
                        <user-input2 v-model="device_info.user" :placeholder="'User'" :input_style="'height:35px;'" />
                        <user-input2 v-model="device_info.port" :type="'number'" :placeholder="'Port'" :input_style="'height:35px;'" />
                        <user-input2 v-model="device_info.password" :type="'password'" :placeholder="'Password'" :input_style="'height:35px;'" />
                        <div class="my-1">
                            <switch2 v-model="device_info.is_active" /> IS ACTIVE
                        </div>
                        <div class="my-1">
                            <switch2 v-model="device_info.is_app_execute" /> IS APP EXECUTE
                            <div>
                                <small class="text-secondary">**This will triggered in use of Windows Application.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card mt-4">
                            <div class="card-header">
                                <label class="mb-1">Branches</label>
                            </div>
                            <div class="card-body"> 
                                <small class="text-secondary">
                                    *Branches where this device can be executed.
                                </small> 
                                <table class="table table-bordered">
                                    <tr v-for="(item,itemIndex) in branchList" v-bind:key="'branch-list-'+item.id+'-'+itemIndex">
                                        <td class="p-0 pt-1 pl-2 text-center" style="width:25px;">
                                            <icheck @update:checked="update_checked" :name="'item-check-branch-selection'" :value="item.id" :data_value="item.id" :checked="hasBranch(item.id)" />
                                        </td>
                                        <td v-text="item.name" class="pt-2"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-sm-6"> </div>
                    <div class="col-sm-6">
                        <div class="mt-1">
                            <div class="alert alert-danger"> 
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                <small> **Warning: Storing your device account in this website is safe but please be careful of your commands. </small>
                            </div> 
                        </div>
                        <switch2 v-model="is_check_before_saving" /> CHECK/VALIDATE device account before saving
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button class="btn btn-outline-primary" v-if="device_id" @click="save" >
                        <span class="fa fa-save"></span> SAVE
                    </button> 
                    <button class="btn btn-outline-primary" v-else @click="save">
                        <span class="fa fa-plus"></span> ADD
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../UserInput2.vue';
    import BoostrapSwitch2Vue from '../BoostrapSwitch2.vue';
    import iCheckVue from '../iCheck.vue';
    export default {
        props:[ "group_id", "set_branch_source", "set_branch_source_url" ],
        components: {
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue,
            "icheck":iCheckVue
        },
        watch: { 
        },
        data: function () {
            return { 
                device_id:0,
                is_check_before_saving:true,
                branch_source:'branches',
                branchList:[],
                branch_source_url:'/api/group/'+this.group_id+'/branch/list?is_all=yes',
                device_info:{
                    name:'',
                    description:'',
                    host:'',
                    user:'',
                    password:'',
                    port:0,
                    type:'mikrotik',
                    is_active:true,
                    is_app_execute:false,
                    branch_ids:[]
                },
                promiseExec:null
           }
        },
        methods:{ 
            update_checked:function(is_check, val){

                if(is_check){
                    this.device_info.branch_ids.push(val);
                }else{
                    this.device_info.branch_ids = this.device_info.branch_ids.filter(a=>a!=val);
                }
            },
            reset:function(){
                this.device_id = 0;
                this.device_info = {
                    name:'',
                    description:'',
                    host:'',
                    user:'',
                    password:'',
                    port:0,
                    type:'mikrotik',
                    is_active:true,
                    is_app_execute:false,
                    branch_ids:[]
                };
                this.is_check_before_saving = true;

            },
            hasBranch:function(branch_id){
               // console.log("Branches", this.device_info.branch_ids);
               return this.device_info.branch_ids.indexOf(branch_id) >= 0;
            },
            show:function(device_id = 0){ 
                var vm = this;
                vm.reset();
                vm.device_id = device_id;

                this.$refs.modal.show();
                if(vm.device_id){
                    this.getOne(vm.device_id);
                }

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            getOne:function(device_access_id){
                var vm = this;
                WebRequest2('GET', '/api/group/'+this.group_id+'/devices/get?device_access_id='+device_access_id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        vm.device_info ={
                            name: data.name,
                            description: data.description,
                            host: data.host,
                            user: data.user,
                            password:'',
                            port: data.port,
                            type: data.type,
                            is_active: data.is_active,
                            is_app_execute: data.is_app_execute,
                            branch_ids: data.branch_ids
                        };

                    });
                })
            },
            loadBranchList:function(){
                var vm = this;
                WebRequest2('GET', this.branch_source_url).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(vm.group_id,data);
                        vm.branchList = data;
                    });
                });
            },
            save:function(){
                var vm = this; 
                var request = vm.device_info;
                request.is_check_before_saving = vm.is_check_before_saving;
                request.device_access_id = vm.device_id;
                
                this.$refs.swal_prompt.alert(
                    'question',
                   this.device_id ? 'Update Device':"Add Device", 
                    "Confirm" , 
                     "POST", 
                    '/api/group/'+this.group_id+'/devices/'+(this.device_id ? 'save':'add'), 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            vm.device_id = res.value.data.id;
                            vm.$emit('data_updated');
                        }
                    }
                }); 
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },

            remove:function(device_access_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question',
                    "Remove this device?", 
                    "Confirm" , 
                     "POST", 
                    '/api/group/'+this.group_id+'/devices/delete', 
                    JSON.stringify({
                        device_access_id: device_access_id
                    })
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            vm.$emit('data_updated');
                        }
                    }
                }); 
            }

        },
        mounted:function(){  
            if(this.set_branch_source){
                this.branch_source = this.set_branch_source;
            }
            if(this.set_branch_source_url){
                this.branch_source_url = this.set_branch_source_url;
            }


            this.loadBranchList();    
        },
        updated:function(){

        }
    }
</script>
