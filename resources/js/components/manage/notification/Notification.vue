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
            <a href="#" class="m-0 px-3 py-2">
                <i class="fas fa-upload mr-2"></i> 1 System Updates
                <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div> 
            <a class="dropdown-item" @click="clickUpdate">
                <i class="fas fa-refresh mr-2"></i> Check Updates
                <span class="float-right text-muted text-sm">
                    <i class="fas fa-eye"></i>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">
                See All Notifications
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

                evt.stopPropagation();
            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
