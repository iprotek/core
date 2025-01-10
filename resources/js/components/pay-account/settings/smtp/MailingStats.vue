<template>
    <div class="row" v-if="hasLoaded">
        <div class="col-sm-6">
            <small>
                <div class="w-100 card">
                    <div class="card-header">Email Sending Status</div>
                    <div class="card-body">
                        <table class="w-100">
                            <tr>
                                <th class="text-right" style="width:90px;">Send Count:</th>
                                <td style="width:35px;" class="text-center text-primary" title="Total Limit Today" v-text="stat.sending_count"></td>
                                <td style="width:35px;" class="text-center text-secondary" title="Total Sending Attempts" v-text="stat.total_sent_today"></td>
                                <td style="width:35px;" class="text-center text-success" title="Total Successfully Sent" v-text="stat.total_sent_success_today"></td>
                                <td style="width:35px;" class="text-center text-danger" title="Total Failed" v-text="stat.total_failed_today"></td>
                                <td style="width:35px;" class="text-center text-warning" title="Sending emails on process" v-text="stat.total_sending_today"></td>
                                <td style="width:35px;" class="text-center text-warning" title="Emails Queued" v-text="stat.total_queued_today"></td>
                                <th class="text-right" title="Days to Send"> Days:</th>
                                <td colspan="6" class="pl-2"> Mon - Fri </td>
                                <th class="text-right" title="Sending Time">Time:</th>
                                <td colspan="6" class="pl-2">
                                    <small > 
                                        <label v-text="stat.start_send_time" class="mb-0"></label> ~ <label v-text="stat.end_send_time" class="mb-0"></label>
                                    </small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </small>
        </div>
        <div class="col-sm-6">
            <small>
                <div class="w-100 card">
                    <div class="card-header">Mailer Status</div>
                    <div class="card-body">
                        <table class="w-100">
                            <tr>
                                <th class="text-right">Actives: </th>
                                <td v-text="stat.active_mailers"></td>
                                <th class="text-right">Open: </th>
                                <td v-text="stat.open_mailers"></td>
                                <th class="text-right">Failed: </th>
                                <td v-text="stat.failed_mailers"></td> 
                                <th class="text-right">Processing: </th>
                                <td v-text="stat.processing_mailers"></td>
                                <th class="text-right">Closed: </th>
                                <td v-text="stat.closed_mailers"></td> 
                                <th class="text-right">Ready: </th>
                                <td v-text="stat.ready_mailers"></td>
                                <th class="text-right">Inactives: </th>
                                <td v-text="stat.inactive_mailers"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </small>
         </div>
    </div>
</template>

<script>
    export default {
        props:[ "group_id" ],
        components: { 
        },
        data: function () {
            return {
                hasLoaded:false,
                stat:{
                    email : "",
                    sending_count: 0,
                    total_sent_today: 0,
                    total_sent_success_today:0,
                    total_failed_today: 0 ,
                    total_sending_today : 0,
                    total_queued_today : 0,
                    active_mailers : 0,
                    processing_mailers : 0,
                    open_mailers : 0,
                    ready_mailers : 0,
                    closed_mailers : 0,
                    inactive_mailers : 2,
                    failed_mailers : 0,
                    start_send_time :'',
                    end_send_time : ""
                }
            }
        },
        methods: { 
            loadMailStat:function(){ 
                var vm = this;
                //setTimeout(()=>{
                    WebRequest2('GET', '/api/group/'+this.group_id+'/settings/smtp-connection/get-stat').then(resp=>{
                        
                        resp.json().then(data=>{
                            //console.log(data);
                            vm.stat = data;
                        });
                        vm.hasLoaded = true;
                    });
                //},2000);
            }
        },
        mounted:function(){ 
            this.loadMailStat();
        },
        updated:function(){

        }
    }
</script>
