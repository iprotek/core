<template>
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">14</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">14 Notifications</span>
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
            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
