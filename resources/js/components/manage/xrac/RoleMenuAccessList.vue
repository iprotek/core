<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        MENU ACCESSIBILITY
                    </div>
                    <div class="card-body p-0">
                        <table class="table">
                            <tr v-for="(item,index) in menuItems" v-bind:key="'menu-item-'+item.id+'-'+index">
                                <th class="py-0 pr-0" style="width:100px;">
                                    <switch2 v-model="item.allowed" />
                                </th>
                                <th class="py-0 pl-1 text-nowrap">
                                    <span :class="'pr-0 fas '+item.icon"></span>
                                    <label class="mb-0 pl-1" v-text="item.menu_text"></label>
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-outline-primary btn-sm" @click="$refs.web_submit.submit()">
                            <web-submit ref="web_submit" :action="saveRole" :icon_class="'fa fa-save'" :label="'SAVE MENU DEFAULTS'"  :timeout="3000" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import WebSubmitVue from '../../common/WebSubmit.vue';
    export default {
        props:[ "role_id", "app_account_id", "is_default_setting"  ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "web-submit":WebSubmitVue
        },
        watch: { 
            role_id(newVal){
                //console.log("NewRole", newVal);
                this.loadRoleDefault();
            }
        },
        data: function () {
            return {
                menuItems:[]
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadMenus:function(){
                var vm = this;
                return WebRequest2('GET','/manage/xrac/xrole/menus').then(resp=>{
                    return resp.json().then(data=>{
                        data.forEach((item)=>{
                            item.allowed = false;
                        });
                        vm.menuItems = data;
                        vm.loadRoleDefault();
                    });
                });
            },
            loadRoleDefault:function(){
                var vm = this;
                WebRequest2('GET', '/manage/xrac/xrole/role-menus/'+this.role_id).then(resp=>{
                    resp.json().then(data=>{
                        //console.log("Role Menus",data);
                        data.forEach((item,itemIndex)=>{
                            var menu = vm.menuItems.filter(a=>a.id == item.id)[0];
                            if(menu){
                                menu.allowed = item.allowed; 
                            }
                        }); 
                    })
                })
            },
            saveRole:function(){
                var vm = this;
                var is_setting = this.is_default_setting;
                var menuIds = [];
                vm.menuItems.filter(a=>a.allowed).forEach((item)=>{
                    menuIds.push(item.id);
                });

                console.log(menuIds);

                //TODO:: ROLE DEFAULT
                if(is_setting){
                    return  WebRequest2("POST", "http://localhost:9014/manage/xrac/xrole/update-role-menu/"+vm.role_id, {menu_ids:menuIds}).then(resp=>{
                        return resp.json().then(data=>{
                            //console.log(data);
                            if(!resp.ok){
                                return;
                            }
                            return data;
                        });
                    });





                }
                //TODO:: USER ROLE DEFAULT
            

            }

        },
        mounted:function(){     
            this.loadMenus();
        },
        updated:function(){

        }
    }
</script>
