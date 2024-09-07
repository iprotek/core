<template>
    <div>
        <div>
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="ion ion-help-buoy text-warning"></i>
                <span v-if="has_chat" class="badge badge-warning navbar-badge">!</span>
                <span v-else class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <template v-if="has_chat">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                        <img alt="User Avatar" class="img-size-50 mr-3 img-circle"/>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                            Brad Diesel
                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                        <img alt="User Avatar" class="img-size-50 img-circle mr-3"/>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                            John Pierce
                            <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                        <img alt="User Avatar" class="img-size-50 img-circle mr-3"/>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                            Nora Silvester
                            <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </template>
                <template v-else>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <code class="w-100 text-center"> -- No ticket at this moment -- </code>
                        </div>
                        <!-- Message End -->
                    </a> 
                </template>
                <div class="dropdown-divider"></div>
                <a  class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">
                            <small  class="text-sm text-secondary">
                                <small>Send a ticket for support of this system problem.</small>
                            </small>
                            <div>
                                <button class="btn btn-outline-primary btn-sm" @click="clickAddEditTicketModal()" > Submit New Ticket </button>
                            </div>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer text-primary" style="border:1px solid blue;">See All Tickets</a>
            </div>
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
                has_chat:false
            }
        },
        methods: { 
            clickAddEditTicketModal:function(id = 0){
                window.add_edit_ticket_modal.show(id);
            },
            loadPushNotifSettings:function(){
                /*
                WebRequest2('GET', '/manage/sms-sender/push-notif-info').then(resp=>{
                    resp.json().then(data=>{
                        console.log("NOTIF SETTINGS", data);
                    });
                });
                */
            }
        },
        mounted:function(){     
            //this.loadPushNotifSettings();
            /*
            Pusher.logToConsole = true;

            var pusher = new Pusher('3ba4f1b9531904744a8e', {
            cluster: 'ap1'
            });

            var channel = pusher.subscribe('chat-channel');
            channel.bind('my-event', function(data) {
                console.log(data);
                //app.messages.push(JSON.stringify(data));
            });
            */
        },
        updated:function(){

        }
    }
</script>
