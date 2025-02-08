<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="70">
            <template slot="header" >
                ADD/UPDATE DEVICE
            </template> 
            <template slot="body" >
                <div>
                    <user-input2 :placeholder="'Name'" :input_style="'height:35px;'" /> 
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="mt-2">
                            <label class="mb-0">Type:</label>
                            <select class="form-control">
                                <option value="mikrotik">Mikrotik</option>
                                <option value="mikrotik">Windows</option>
                                <option value="mikrotik">SSH</option>
                            </select>
                        </div>
                        <user-input2 :placeholder="'Host'" :input_style="'height:35px;'" />
                        <user-input2 :placeholder="'User'" :input_style="'height:35px;'" />
                        <user-input2 :placeholder="'Port'" :input_style="'height:35px;'" />
                        <user-input2 :type="'password'" :placeholder="'Password'" :input_style="'height:35px;'" />
                        <div class="my-1">
                            <switch2 /> IS ACTIVE
                        </div>
                        <div class="my-1">
                            <switch2 /> IS APP EXECUTE
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
                                            <icheck :data_value="item.id" />
                                        </td>
                                        <td v-text="item.name" class="pt-2"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-1">
                    <switch2 v-model="is_check_before_saving" /> CHECK BEFORE SAVING
                </div>
                <div>
                    <div class="alert alert-danger"> 
                        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                        <small> **Warning: Storing your account in this website is safe but please be careful of your commands. </small>
                    </div> 
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
                is_check_before_saving:true,
                branch_source:'branches',
                branchList:[],
                branch_source_url:'/api/group/'+this.group_id+'/branches/list?is_all=yes',
                device_info:{
                    name:'',
                    host:'',
                    user:'',
                    password:'',
                    port:0,
                    type:'mikrotik',
                    is_active:'',
                    branch_ids:[]
                },
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
            loadBranchList:function(){
                var vm = this;
                WebRequest2('GET', this.branch_source_url).then(resp=>{
                    resp.json().then(data=>{
                        console.log(vm.group_id,data);
                        vm.branchList = data;
                    });
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
