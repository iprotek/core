<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        MENU ACCESSIBILITY
                    </div>
                    <div class="card-body px-0">
                        <table class="table">
                            <tr v-for="(item,index) in menuItems" v-bind:key="'menu-item-'+item.id+'-'+index">
                                <th class="py-0 pr-0" style="width:100px;">
                                    <switch2 />
                                </th>
                                <th class="py-0 pl-1 text-nowrap">
                                    <span :class="'pr-0 fas '+item.icon"></span>
                                    <label class="mb-0 pl-1" v-text="item.menu_text"></label>
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-outline-primary btn-sm">
                            SAVE MENU DEFAULTS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    export default {
        props:[ "role_id", "app_account_id", "is_default_setting"  ],
        components: {
            "switch2":BoostrapSwitch2Vue
        },
        watch: { 
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
                        vm.menuItems = data;
                    });
                });
            }

        },
        mounted:function(){     
            this.loadMenus();
        },
        updated:function(){

        }
    }
</script>
