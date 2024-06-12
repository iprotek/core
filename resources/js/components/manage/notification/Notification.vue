<template>
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" v-text=" (summary.isLoadSummary ? ':': summary.total)"> </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header" v-if="summary.total > 0" v-text=" (summary.isLoadSummary ? ' Loading Notification.. ': (summary.total +' Notifications'))"> </span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> 3 new messages
                <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> 8 friend requests
                <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="fas fa-file mr-2"></i> 3 new reports
                <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="/manage/sys-notification" class="dropdown-item">
                <i class="fas fa-upload mr-2"></i> 1 System Updates
                <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <!--
                <a href="#" class="dropdown-item dropdown-footer">
                    See All Notifications
                </a>
            -->
            <div class="dropdown-divider"></div>
            <a href="#" @click="clickUpdate" class="dropdown-item dropdown-footer">
                <span class="fa fa-spinner fa-pulse"></span> Check System Updates
            </a>
        </div>
    </li>
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
                }
            }
        },
        methods: { 
            clickUpdate:function(evt){
                //Actions Here
                this.checkSystemUpdates();
                evt.stopPropagation();
            },
            checkSystemUpdates:function(){
                console.log("Checking system updates");
            },
            loadSystemSummary:function(){
                var vm = this;
                this.summary.isLoadSummary = true;
                WebRequest2("GET", "/manage/sys-notification/system-updates-summary" ).then(resp=>{
                    this.isLoadSummary = false;
                    resp.json().then(data=>{
                        console.log(data);
                        vm.summary.summaryList = data.summary;
                        vm.summary.total = data.total;
                    });

                })
            }
        },
        mounted:function(){
            this.loadSystemSummary();
        },
        updated:function(){

        }
    }
</script>
