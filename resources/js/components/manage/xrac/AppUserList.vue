<template>
    <div>
        <div class="card">
            <div class="card-header">
                <label> USER </label>
            </div> 
            <div class="input-group text-sm">
                <span class="btn btn-default">
                    <small title="Show" class="fa fa-search text-primary"></small>
                </span>
                <input v-model="search" type="text" class="form-control" placeholder="Search user.." />
            </div> 
            <table class="table m-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in dataList" v-bind:key="'item-'+item.id+'-'+index">
                        <td class="text-center">
                            <input type="radio" :value="item.id" name="account-name" />
                        </td>
                        <td colspan="2"><a v-text="item.name" :title="item.is_blocked? 'InActive':'Active'" :class="item.is_blocked? 'text-secondary':'text-primary'"></a>
                            <div>
                                <small class="text-secondary" v-text="item.email"></small>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <page-footer v-model="pageData" />
                        </td>
                    </tr>
                </tfoot>
            </table>
            <!--
            <button class="btn btn-outline-primary m-2"> 
                SAVE & SYNC USER XRAC
            </button>-->
        </div>
    </div>
</template>

<script>
    import PageFooterVue from '../../common/PageFooter.vue';
    export default {
        props:[  ],
        components: { 
            "page-footer":PageFooterVue,
        },
        data: function () {
            return {
                pageData:null,
                dataList:[],
                current_page:'',
                isLoading:false,
                search:'',
                current_page:1
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadAppUserAccounts:function(){
                var vm = this;
                vm.dataList = [];
                vm.isLoading = true;
                WebRequest2('GET', '/manage/xrac/xrole/account-list?'+this.queryString({
                    search: vm.search,
                    page: vm.current_page,
                    items_per_page:10
                })).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        console.log(data);
                        vm.pageData = data;
                        vm.dataList = data.data;
                    });

                })
            }

        },
        mounted:function(){   
            this.loadAppUserAccounts();  
        },
        updated:function(){

        }
    }
</script>
