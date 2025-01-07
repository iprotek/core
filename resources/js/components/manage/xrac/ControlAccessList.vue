<template>
    <div class="row">
        <div class="col-sm-4">
            <role-menu :is_default_setting="true" :app_account_id="app_account_id" :role_id="role_id" />
        </div>
        <div class="col-sm-8">
            <div class="card">
                <table class="table m-0">
                    <thead class="sticky-top text-nowrap" style="background-color:white;  box-shadow: gray 0px 1px 0px;">
                        <tr>
                            <th style="width: 50px;"></th>
                            <th style="width: 50px;"></th>
                            <th>CONTROL ACCESS</th> 
                            <th :class=" is_default_setting ? 'btn btn-outline-primary btn-lg text-sm':'text-sm'" style="border-radius:0px;">
                                <div v-if="is_default_setting"> 
                                    <span class="fa fa-save"></span> SAVE ROLE DEFAULTS 
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template  v-for="(control,rowIndex) in controlAccessList"  > 
                            <tr v-bind:key="'row-control-'+control.id+'-'+rowIndex">
                                <td class="text-center" style="min-width:30px;">
                                    <switch2 />
                                </td>
                                <td colspan="3">
                                    <label class="text-info mb-0"> <small>[ {{control.name}} ]</small> {{ control.title }} </label>  
                                    <small class="text-secondary"> {{control.description}} </small>
                                </td> 
                            </tr> 
                            <tr v-for="(access, AccessIndex) in control.accesses" v-bind:key="'row-access-'+control.id+'-'+rowIndex+'-'+access.id+'-'+AccessIndex">
                                <td></td>
                                <td class="text-center ml-2" >
                                    <switch2 />
                                </td>
                                <td colspan="2">
                                    <b class="text-success"> <small>[ {{access.name}} ]</small> {{ access.title }} </b>  
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
    import RoleMenuAccessListVue from './RoleMenuAccessList.vue';
    export default {
        props:[ "is_default_setting", "role_id", "app_account_id" ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "role-menu":RoleMenuAccessListVue
        },
        watch: { 
        },
        data: function () {
            return {
                controlAccessList:[]   
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
                WebRequest2('GET', '/manage/xrac/control-access/list').then(resp=>{
                    resp.json().then(data=>{
                        vm.controlAccessList = data;
                    });
                })
            }

        },
        mounted:function(){
            this.loadControlAccess();
        },
        updated:function(){

        }
    }
</script>
