<template> 
    <div>
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-th-large"></i>
            <span class="badge badge-warning navbar-badge" > ? </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header" > APPLICATIONS </span>
            <div class="dropdown-divider"></div>
            <!--
                <a href="#" class="dropdown-item dropdown-footer">
                    See All Notifications
                </a>
            -->
            <a class="dropdown-item py-0 pb-1 text-center text-sm text-danger">
                <small>
                    <span > NO APPS AVAILABLE </span>
                </small>
            </a>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item py-0 pb-1 text-center text-sm text-black">
                <small>
                    <i class="fas fa-cog"></i> <span > Modules </span>
                </small>
            </a>
            <div class="dropdown-divider"></div>
            <!-- LIST OF MODULES -->
             <!-- Messaging( Administrator ONLY ) -->
             <!-- Tracking( Administrator ONLY ) -->
             <!-- Sms-sender( Administrator ONLY ) -->
             <!-- MGMT( Administrator ONLY ) -->
                <div class="text-center p-1">
                    <a class="btn btn-app m-0 my-1">
                        <span class="badge bg-info mr-2">12</span>
                        <i class="fas fa-envelope"></i> Messaging
                    </a>
                    <a class="btn btn-app m-0 my-1">
                        <span class="badge bg-info mr-2">12</span>
                        <i class="fas fa-map-pin"></i> Tracking
                    </a>
                    <a class="btn btn-app m-0 my-1">
                        <span class="badge bg-info mr-2">12</span>
                        <i class="fas fa-th"></i> APPS
                    </a>
                    <a class="btn btn-app m-0 my-1 bg-primary">
                        <span class="badge bg-info mr-2">12</span>
                        <i class="fas fa-users"></i> MGMT
                    </a>
                </div>
            <div class="dropdown-divider"></div>
            
            <a href="/manage/apps/list" class="dropdown-item py-0 pb-1 text-center text-sm text-primary">
                <small>
                    <i class="fas fa-plus"></i> <span > Add App </span>
                </small>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props:[  ],
        components: { 
        },
        watch:{
            
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

        },
        mounted:function(){ 
        },
        updated:function(){

        }
    }
</script>
