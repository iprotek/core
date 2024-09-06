<template> 
    <div>
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" v-if="summary.isLoadSummary == true || summary.total > 0" v-text=" (summary.isLoadSummary == true ? '?': summary.total)"> </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header" v-text=" (summary.isLoadSummary == true ? ' Loading Notification.. ': (summary.total +' Notifications'))"> </span>
            
            <div v-for="(summary, summaryIndex) in summary.summaryList" v-bind:key="'summary-item-'+summaryIndex">
                <div v-if="summary.type == 'message'">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> <span v-text="summary.count+' new messages'"> </span>
                        <span class="float-right text-muted text-sm" v-text="summary.diff"></span>
                    </a>
                </div>
                <div v-else-if="summary.type == 'friend-request'">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> <span v-text="summary.count+' friend requests'"> </span>
                        <span class="float-right text-muted text-sm"  v-text="summary.diff"></span>
                    </a>
                </div>
                <div v-else-if="summary.type == 'report'">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> <span v-text="summary.count+' new reports'">  </span>
                        <span class="float-right text-muted text-sm"  v-text="summary.diff"></span>
                    </a>
                </div>
                <div  v-else-if="summary.type == 'git'">
                    <div class="dropdown-divider"></div>
                    <a href="/manage/sys-notification" class="dropdown-item">
                        <i class="fas fa-upload mr-2"></i> <span v-text="summary.count+' System Updates'"> </span>
                        <span class="float-right text-muted text-sm"  v-text="summary.diff"></span>
                    </a>
                </div>
                <div v-else>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> <span  v-text="summary.count+' new '+summary.name">  </span>
                        <span class="float-right text-muted text-sm"  v-text="summary.diff"></span>
                    </a>
                </div>
            </div>



            <div class="dropdown-divider"></div>
            <!--
                <a href="#" class="dropdown-item dropdown-footer">
                    See All Notifications
                </a>
            -->
            <div class="dropdown-divider"></div>
            <a href="#" @click="clickUpdate" class="dropdown-item dropdown-footer">
                <span :class="'fa fa-spinner '+(updates.isCheck ? 'fa-pulse':'')"></span> <span v-text="updates.message"></span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props:[  ],
        components: { 
        },
        data: function () {
            return {    
                summary:{
                    isLoadSummary:false,
                    summaryList:[],
                    total:0,
                },
                updates:{
                    isCheck:false,
                    message:'Check System Updates'
                }
            }
        },
        methods: { 
            clickUpdate:function(evt){
                //Actions Here
                if(this.updates.isCheck == false){
                    this.checkSystemUpdates(); 
                }

                evt.stopPropagation();
            }, 
            loadSystemSummary:function(){
                var vm = this;
                vm.summary.isLoadSummary = true;
                vm.summary.summaryList = [];
                setTimeout(()=>{
                    WebRequest2("GET", "/manage/sys-notification/system-updates-summary" ).then(resp=>{
                        vm.summary.isLoadSummary = false;
                        resp.json().then(data=>{
                            //console.log(data);
                            vm.summary.summaryList = data.summary;
                            vm.summary.total = data.total;
                        });

                    });
                }, 200);
            },
            checkSystemUpdates:function(){
                var vm = this;
                vm.updates.isCheck = true;
                vm.updates.message = "Checking Updates..";
                //setTimeout(()=>{
                    WebRequest2('POST','/manage/sys-notification/check-system-updates', '{}').then(resp=>{
                        vm.updates.isCheck = false;
                        resp.json().then(data=>{
                            //console.log(data);
                            if(data.status == 1){
                                vm.loadSystemSummary();
                            }
                            
                        })
                    });
                //}, 1000);
            }

        },
        mounted:function(){
            this.loadSystemSummary();
        },
        updated:function(){

        }
    }
</script>
