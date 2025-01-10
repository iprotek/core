<template>
    <div >
        <div :class="'row '+(is_right === true ? 'justify-content-start':'justify-content-end')">
            <div :class="  can_set_default === true && can_log_out === true ? 'col-sm-7':'col-md-6'">
                <div class="card"> 
                    <div class="card-body"> 
                        <div class="row">
                            <small><b>Workspace:</b></small>
                            <div v-if="show_owned_group === true">
                                <b class="text-primary" v-text="group_name"></b>
                                <button class="btn btn-outline-primary btn-sm">
                                    <span class="fa fa-cog"></span>
                                </button>
                            </div>
                            <div v-else>
                                <div class="input-group w-100">
                                    <label class="input-group-text p-0">
                                        <select2  v-if="groupList" v-model="item_group" @selected="itemSelected" :placeholder="' -- SELECT WORKSPACE -- '" :select_data="groupList" ></select2>
                                    </label>
                                    <button v-if="can_set_default === true" class="btn btn-outline-primary btn-sm" @click="setDefaultWorkspace">
                                        Set Default Workspace
                                    </button>
                                    <button v-if="can_log_out === true" class="btn btn-outline-danger btn-sm" @click="logoutApp">
                                        <span class="fa fa-user-times"></span> App log-out
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_branch === true">
                            <branch-selector @selected="branchSelected" v-if="group_id" :group_id="group_id" :modal_selector="modal_selector"></branch-selector>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import BranchSelectorVue from './BranchSelector.vue';
    import Select2Vue from '../common/Select2.vue';
    export default {
        props:[ "show_owned_group","is_right","can_set_default", "can_log_out","modal_selector","show_branch" ],
        components: { 
            "select2" : Select2Vue,
            "branch-selector":BranchSelectorVue
        },
        data: function () {
            return {    
                item_group:{
                    id:0,
                    text:''
                },
                groupList:null,
                group_name:"",
                group_id:0
            }
        },
        methods: { 
            branchSelected:function(branch){
                var item = this.item_group;
                item.branch_id = branch.id;
                this.$emit('selected', item);
            },
            setDefaultWorkspace:function(){
                var request = {
                    proxy_group_id: this.item_group.id
                } 
                console.log(request);
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to set this as default workspace?", 
                    "Confirm" , 
                    "PUT", 
                    "/manage/settings/set-default-workspace", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            //res.value.data.id;
                            //vm.$refs.modal.dismiss();
                            //vm.billing_account_id = res.value.data.id;
                            //setTimeout(function(){
                            //    vm.$emit('modal_updated');
                            //}, 500);
                        }
                    }
                }); 

            },
            logoutApp:function(){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Would you like to disconnect your app?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/settings/app-logout", 
                    "{}"
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){  
                            setTimeout(function(){
                                //vm.$emit('modal_updated');
                                window.location.href="/manage";
                            }, 500);
                        }
                    }
                }); 
            },
            itemSelected:function(val){
                //console.log('SELECTED',val);
                var vm = this;
                vm.group_id = 0;
                if(!vm.show_branch){
                    this.$emit('selected', val);
                }
                else{
                    setTimeout(function(){
                        vm.group_id = val.id;
                    },50);
                    //Temporary
                    //this.$emit('selected', val);
                }
            },
            loadGroupSelection:function(){
                var vm = this;
                WebRequest2('GET', '/pay-account').then(resp=>{
                    resp.json().then(data=>{ 
                        if(data){
                            //console.log(data);

                            var listGroup = [];
                            var groups = data.user_info.groups;
                            groups.forEach((item)=>{
                                if(item.owner){
                                    if(item.owner.group_name) 
                                        item.text = item.owner.group_name+' ('+item.owner.email+' )'; 
                                    else
                                        item.text =  item.owner.name+" ( "+item.owner.email+" )";
                                    listGroup.push(item);
                                }
                            }); 
                            
                            if(vm.show_owned_group === true){
                                var user_info = data.user_info;
                                if(user_info && user_info.group_name){
                                    vm.group_name = user_info.group_name+' ( '+user_info.email+' )';
                                }else{
                                    vm.group_name = 'Personal ( '+user_info.email+' )';
                                }
                                var selItem = listGroup.filter(a=>a.id == data.default_group_id)[0]; 
                                if(selItem){
                                    vm.item_group = selItem;
                                    vm.itemSelected(vm.item_group);
                                }
                                return;
                            }

                            var selItem = listGroup.filter(a=>a.id == data.default_group_id)[0]; 
                            if(selItem){
                                vm.item_group = selItem;
                                vm.itemSelected(vm.item_group);
                            }
                            vm.groupList = listGroup;
                        }
                    })
                });
            }
        },
        mounted:function(){
            this.loadGroupSelection();     
        },
        updated:function(){

        }
    }
</script>
