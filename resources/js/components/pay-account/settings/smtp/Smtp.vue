<template>
    <div> 
        <div class="card">
            <div class="card-header">MY SMTP Senders</div>
            <div class="card-body pt-0">
                <div class="row"> 
                    <div class="col-md-12">
                        <div class="input-group mt-4" >
                            <span class="input-group-text btn btn-success" title="Add Email"  @click="$refs.modal_smtp.show()" > 
                                <span class="fa fa-plus"></span> 
                            </span> 
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                                <input class="form-control" placeholder="Seach any keywords"  />
                            <button class="input-group-text">Find</button>
                        </div>
                    </div>
                </div>
                <small>
                    <table class="w-100 custom-red-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reply To</th>
                                <th>Transport</th>
                                <th>Host</th>
                                <th>Port</th>
                                <th>Encryption</th>
                                <th>Username</th>
                                <th>Is Active</th> 
                                <th>Sent Today</th> 
                                <th>Sent Total</th> 
                                <th>Send Today Limit</th> 
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="isLoad">
                                <th colspan="12" class="text-center"> ~~ Loading SMTP ~~ </th>
                            </tr>
                            <tr v-else-if="smtpList.length == 0">
                                <th colspan="12" class="text-center"> ~~ No SMTP Found ~~ </th>
                            </tr>
                            <tr v-for="(item, itemIndex) in smtpList" v-bind:key="'smtp-item-'+item.id+'-'+itemIndex">
                                <!--<th colspan="10" class="text-center"> ~~ Item ~~ </th>-->
                                <td v-text="item.name"></td>
                                <td v-text="item.reply_to"></td>
                                <td v-text="item.transport"></td>
                                <td v-text="item.host"></td>
                                <td v-text="item.port"></td>
                                <td v-text="item.encryption"></td>
                                <td v-text="item.username"></td>
                                <td>
                                    <small v-if="item.is_active == 1" class="text-primary ">Active</small>
                                    <small v-else class="text-secondary ">Inactive</small>
                                </td>
                                <td>
                                    <small v-text="item.smtp_sent_today"></small>
                                </td>
                                <td>
                                    <small v-text="item.smtp_sent_total"></small>
                                </td>
                                <td>
                                    <small v-text="item.smtp_send_today_limit"></small>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_smtp.show(item.id)">
                                        <span class="fa fa-edit"></span>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" @click="$refs.modal_smtp.remove(item.id)">
                                        <span class="fa fa-times"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </small>
                <page-footer v-model="pageFooterData"></page-footer>
            </div>
        </div>
        <swal ref="swal_prompt"></swal>
        <modal-smtp :group_id="group_id" ref="modal_smtp" @modal_updated="loadSmtpList"></modal-smtp>
    </div>
</template>

<script>
    import PageFooterVue from '../../../common/PageFooter.vue';
    import ModalSmtpVue from './ModalSmtp.vue'; 
    export default {
        props:[ "group_id" ],
        components: { 
            "modal-smtp":ModalSmtpVue,
            "page-footer":PageFooterVue
        },
        data: function () {
            return { 
                search:'',
                current_page:'',
                pageFooterData:null,
                smtpList:[],
                isLoad:false
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadSmtpList:function(){
                var vm = this;
                vm.smtpList = [];
                vm.isLoad = true;
                WebRequest2('GET', '/api/group/'+this.group_id+'/settings/smtp-connection/list?'+this.queryString({
                    search: vm.search,
                    page:vm.current_page,
                    items_per_page:10
                })).then(resp=>{
                    resp.json().then(data=>{
                        vm.pageFooterData = data;
                        vm.isLoad = false;
                        vm.smtpList = data.data;
                        console.log(data);
                    });
                });
            }
        },
        mounted:function(){
            this.loadSmtpList();
        },
        updated:function(){

        }
    }
</script>
