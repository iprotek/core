<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Updates</div> 
                    <div class="card-body"> 
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:90px;">Ref#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr v-if="isLoading">
                                    <th colspan="3" class="text-center"> -- Loading Sytem Updates -- </th>
                                </tr>
                                <tr v-else-if="pageList.length == 0">
                                    <th colspan="3" class="text-center"> -- You are currently up to date. -- </th>
                                </tr>
                                <tr v-for="(item,itemIndex) in pageList" v-bind:key="'update-item-'+item.id+'-'+itemIndex">
                                    <th >
                                        <code v-text="item.id"></code>
                                    </th>
                                    <th>
                                        <label v-text="item.summary"></label>
                                    </th>
                                    <th>
                                        <label v-text="item.description"></label>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <button class="btn btn-outline-primary" v-if="pageList.length == 0" @click="clickCheckUpdates()"> Check Updates </button>
                        <button class="btn btn-outline-primary" v-else @click="clickApplyUpdates()"> Update Now </button>
                        <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import PageFooterVue from '../../common/PageFooter.vue'; 
    export default {
        props:[  ],
        components: { 
            "page-footer":PageFooterVue
        },
        data: function () {
            return {    
                pageFooterData:null,
                pageList:[],
                current_page:1,
                isLoading:false
            }
        },
        methods: { 
            clickCheckUpdates:function(){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Check Updates?", 
                    "Confirm" , 
                    "GET", 
                    "/manage/sys-notification/check-system-updates"
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            vm.loadUpdates();
                        }
                    }
                }); 
            },
            clickApplyUpdates:function(){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Update Now?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/sys-notification/apply-system-updates",
                    "{}"
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            console.log(res.value);
                            vm.loadUpdates();
                        }
                    }
                }); 
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadUpdates();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadUpdates:function(){
                var vm = this;
                var qString = this.queryString({
                    status:'pending',
                    page:vm.current_page
                });
                vm.isLoading = true;
                vm.pageList = [];
                WebRequest2('GET','/manage/sys-notification/system-updates?'+qString).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageFooterData = data;
                        vm.pageList = data.data;
                    });
                })
            },

        },
        mounted:function(){     
            this.loadUpdates();
        },
        updated:function(){

        }
    }
</script>
