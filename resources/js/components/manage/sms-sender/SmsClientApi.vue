<template>
    <div>
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        SMS Api Client
                    </div> 
                    <div class="card-body">
                        <button class="btn btn-outline-primary mb-2" @click="$refs.modal_add_edit_sms.show()"> <span class="fa fa-plus"></span> ADD API</button>
                        <table class="table custom-red-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Custom Name</th> 
                                    <th style="width:80px;">Priority</th>
                                    <th>API/COMPANY NAME</th>
                                    <th>STATUS</th>
                                    <th>TYPE</th>
                                    <th>IS DEFAULT</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading == true">
                                    <td class="text-center" colspan="7">
                                        LOADIING CLIENT APIs
                                    </td>
                                </tr>
                                <tr v-else-if="clientItems.length == 0">
                                    <td class="text-center" colspan="7">
                                        <code> No CLIENT APIs </code>
                                    </td>
                                </tr>
                                <tr v-for="(clientItem, clientIndex) in clientItems" v-bind:key="'client-'+clientItem.id+'-'+clientIndex">
                                    <th v-text="clientItem.id"></th>
                                    <td v-text="clientItem.name"></td>
                                    <td v-text="clientItem.priority"></td>
                                    <td v-text="clientItem.api_name"></td>
                                    <td >
                                        <label class="text-success" v-if="clientItem.is_active">ACTIVE</label>
                                        <code v-else>Inactive</code>
                                        <label class="fa fa-warning text-danger" v-if="!clientItem.is_webhook_active" title="Webhook is inactive"></label>
                                        <div>
                                            <small class="text-secondary" v-text="clientItem.last_sending_at"></small>
                                        </div>
                                    </td>
                                    <td v-text="clientItem.type"></td>
                                    <td  >
                                        <small class="text-primary" v-if="clientItem.is_default">IS DEFAULT</small>

                                    </td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_add_edit_sms.show(clientItem.id)">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <page-footer v-model="pageData" @page_changed="page_changed"></page-footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-add-edit-sms ref="modal_add_edit_sms" @data_updated="loadClientItems()" :group_id="group_id"></modal-add-edit-sms>
    </div>
</template>

<script>
    import PageFooterVue from '../../common/PageFooter.vue';
    import ModalAddEditSmsClientApiVue from './ModalAddEditSmsClientApi.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue,
            "modal-add-edit-sms":ModalAddEditSmsClientApiVue
        },
        data: function () {
            return {
                isLoading:false,
                pageData:null,
                current_page:1,
                clientItems:[],
                search:''
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
            },
            loadClientItems:function(){
                var vm = this;
                this.isLoading = true;
                this.clientItems = [];
                WebRequest2('GET', '/manage/sms-sender/list?'+this.queryString({search:this.search, page: this.current_page, items_per_page: 10})).then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.clientItems = data.data;
                    });
                });
            }

        },
        mounted:function(){
            this.loadClientItems();
        },
        updated:function(){

        }
    }
</script>
