<template>
    <div class="row">
        <div :class="is_default_setting? 'col-sm-4':'col-sm-3'">
            <role-menu :is_default_setting="is_default_setting" :app_account_id="app_account_id" :role_id="role_id" />
        </div>
        <div :class="is_default_setting? 'col-sm-8':'col-sm-9'">
            <div class="card">
                <table class="table m-0">
                    <thead class="sticky-top text-nowrap" style="background-color:white;  box-shadow: gray 0px 1px 0px;">
                        <tr>
                            <th style="width: 50px;"></th>
                            <th style="width: 50px;"></th>
                            <th>CONTROL ACCESS</th> 
                            <th v-if="is_default_setting" @click="$refs.save_role_default.submit() " :class=" is_default_setting ? 'btn btn-outline-primary btn-lg text-sm':'text-sm'" style="border-radius:0px;">
                                <div >  
                                    <web-submit ref="save_role_default" :action="updateRoleAccess" :icon_class="'fa fa-save'" :label="'SAVE ROLE DEFAULTS'" :timeout="3000" />
                                </div>
                            </th>
                            <th v-else  @click="$refs.save_role_default.submit()" :class="'btn btn-outline-primary btn-lg text-sm'" style="border-radius:0px;">
                                <div >  
                                    <web-submit ref="save_role_default" :action="updateRoleAccess" :icon_class="'fa fa-save'" :label="'SAVE USER DEFAULTS'" :timeout="3000" />
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!is_default_setting">
                            <td colspan="4">
                                <switch2 v-model="isDefault" @value_changed="loadAllowAccountDefaults(true)" /> <label class="mb-0"> IS DEFAULT? </label>
                            </td>
                        </tr>

                        <template  v-for="(control,rowIndex) in controlAccessList"  > 
                            <tr v-bind:key="'row-control-'+control.id+'-'+rowIndex">
                                <td class="text-center" style="min-width:10px;">
                                    <!-- <switch2 /> 
                                    <span class="fa fa-circle float-right text-success mt-1"></span>-->
                                </td>
                                <td colspan="3" class="pl-0">
                                    <label class="text-lg text-info mb-0" :title="'['+control.name+']'"> {{ control.title }} </label>  
                                    <small class="text-secondary"> {{control.description}} </small>
                                </td> 
                            </tr> 
                            <tr v-for="(access, AccessIndex) in control.accesses" v-bind:key="'row-access-'+control.id+'-'+rowIndex+'-'+access.id+'-'+AccessIndex">
                                <td></td>
                                <td class="text-center ml-2" >
                                    <switch2 v-model="access.is_allow" />
                                </td>
                                <td colspan="2" :title="'['+control.name+':'+access.name+']'">
                                    <b class="text-success"> {{ access.title }} </b>  
                                    <small class="text-secondary">{{ access.description }} </small>
                                </td> 
                            </tr>  
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import WebSubmitVue from '../../common/WebSubmit.vue';
    import RoleMenuAccessListVue from './RoleMenuAccessList.vue';
    export default {
        props:[ "is_default_setting", "role_id", "app_account_id", "branch_id"],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "role-menu":RoleMenuAccessListVue,
            "web-submit":WebSubmitVue
        },
        watch: { 
            role_id:function(newvalue){
                if(this.is_default_setting)
                    this.allowedRoleAccess();
                else{
                    this.loadAllowAccountDefaults(true, true);
                }
            },
            app_account_id(newVal){

            },
            is_default(val){
                this.isDefault = this.is_default == true;
            }
        },
        data: function () {
            return {
                controlAccessList:[],
                accessList:[],
                allowAccessList:[],
                isDefault:false
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadControlAccess:function(){
                var vm = this;
                vm.accessList = [];
                WebRequest2('GET', '/manage/xrac/control-access/list').then(resp=>{
                    resp.json().then(data=>{

                        data.forEach(control => { 
                            //Setting allow for v-model compliance
                            control.accesses.forEach((access)=>{
                                access.is_allow = false;
                                vm.accessList.push(access);
                            })
                        });

                        vm.controlAccessList = data;
                        vm.allowedRoleAccess();

                    });
                })
            },
            allowedRoleAccess:function(){
                var vm = this;
                if(vm.is_default_setting){
                    WebRequest2('GET', '/manage/xrac/control-access/allowed-default-role-list/'+this.role_id).then(resp=>{
                        resp.json().then(data=>{
                            vm.allowAccessList = data;
                            //SET FOR ALLOW
                            vm.accessList.forEach((access)=>{
                                var hasAccess = vm.allowAccessList.filter(a=>a.xcontrol_access_id == access.id)[0];
                                if(hasAccess){
                                    access.is_allow = true;
                                }else{
                                    access.is_allow = false;
                                }
                            });
                        })
                    })
                }
                else if(vm.role_id && vm.app_account_id && vm.branch_id){

                    //CHECK IF USER ROLE IS DEFAULT OR NOT
                    
                    WebRequest2('GET', '/manage/xrac/user-role/role-info/'+this.branch_id+'/'+this.app_account_id).then(resp=>{
                        resp.json().then(userData=>{

                            if(!userData){
                                vm.isDefault = true;
                                vm.loadAllowAccountDefaults();
                            }else{
                                vm.isDefault = userData.is_default;
                                if(userData.is_default === undefined){
                                    vm.isDefault = true;
                                }
                                vm.loadAllowAccountDefaults();
                            }
                        });
                    });
 

                }
            },
            loadAllowAccountDefaults:function(is_click = false, is_force = false){
                var vm = this;

                //CLICKING FOR AVOIDING RESET FROM CUSTOMIZATION
                console.log(is_click, !vm.isDefault, !is_force);
                if(!is_force){
                    if(is_click && !vm.isDefault) return;
                }
                
                console.log("Passed");

                WebRequest2('GET', '/manage/xrac/user-role/branch-access-list/'+this.branch_id+'?'+this.queryString({
                    role_id: vm.role_id,
                    app_account_id: vm.app_account_id,
                    is_default: vm.isDefault ? 1:0,
                    is_default_force: is_force ? 1 : 0
                })).then(resp=>{
                    resp.json().then(data=>{
                        vm.allowAccessList = data;
                        //SET FOR ALLOW
                        vm.accessList.forEach((access)=>{
                            var hasAccess = vm.allowAccessList.filter(a=>a.xcontrol_access_id == access.id)[0];
                            if(hasAccess){
                                access.is_allow = true;
                            }else{
                                access.is_allow = false;
                            }
                        });
                    })
                });
            },
            updateRoleAccess:function(){
                var vm = this;
                var allow_access_list = [];
                vm.accessList.forEach((access)=>{
                    if(access.is_allow){
                        allow_access_list.push(access.id);
                    }
                });
                console.log(allow_access_list);
                if(vm.is_default_setting){
                    return WebRequest2('POST', '/manage/xrac/control-access/update-default-role-access-list/'+this.role_id, JSON.stringify({allow_access_list:allow_access_list}) ).then(resp=>{
                        return resp.json().then(data=>{
                            return data;
                        })
                    })
                }

                //USER ROLE ACCESS
                var req = {
                    role_id: vm.role_id,
                    app_account_id: vm.app_account_id,
                    accessIds: allow_access_list,
                    is_default: vm.isDefault
                };
                
                return WebRequest2('POST', '/manage/xrac/user-role/branch-access-list/'+this.branch_id, JSON.stringify(req) ).then(resp=>{
                    return resp.json().then( data=>{
                        if(!resp.ok){
                            return;
                        }
                        return data; 
                    });
                });

            }

        },
        mounted:function(){
            this.loadControlAccess();
        },
        updated:function(){

        }
    }
</script>
