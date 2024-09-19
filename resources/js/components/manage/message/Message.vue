<template>
    <div>
        <div>
            <a class="nav-link" data-toggle="dropdown" href="#" >
                <i class="far fa-comments text-primary"></i>
                <span v-if="!has_chat" class="badge badge-warning navbar-badge">!</span>
                <span v-else-if="notification_count" class="badge badge-danger navbar-badge" v-text="notification_count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <template v-if="has_chat">
                    <div class="p-1" @click.stop="">
                        <div class="input-group text-sm mb-1">
                            <span class="btn btn-default">
                                <small title="Show" class="fa fa-search text-primary"></small>
                            </span> 
                            <input type="text" class="form-control" placeholder="Search chats here..">
                        </div>
                    </div>
                    <div class="dropdown-divider" @click.stop=""></div>
                    <notification-container v-if="ai" :header="'Ask AI'" :data="ai"></notification-container>
                    <notification-container v-if="group" :header="'Group Messages'" :data="group"></notification-container>
                    <notification-container v-if="team" :header="'Team Messages'" :data="team"></notification-container>
                    <notification-container v-if="dm" :header="'Direct Messages'" :data="dm"></notification-container>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </template>
                <template v-else-if="isLoading">
                    <a href="#" class="dropdown-item" >
                        <!-- Message Start -->
                        <div class="media">
                            <code class="w-100 text-center"> -- Loading Chats -- </code>
                        </div>
                        <!-- Message End -->
                    </a> 
                </template>
                <template v-else>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <code class="w-100 text-center" v-text="error_message">  </code>
                        </div>
                        <!-- Message End -->
                    </a> 
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import NotificationContainerVue from './notification/NotificationContainer.vue';
    export default {
        props:[  ],
        components: {
            "notification-container":NotificationContainerVue
        },
        data: function () {
            return {
                has_chat:false,
                isLoading:false, 
                error_message:'Chat Not Available',
                dm:null,
                ai:null,
                group:null,
                team:null,
                notification_count:0,
                
            }
        },
        methods: { 
            clicktChatNotif:function(chatInfo){
                window.addChatMessage({});
            },
            loadNotification:function(){
                var vm = this;
                vm.isLoading = true;
                WebRequest2('GET', '/manage/message/notifications').then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        console.log("Chat Settings", data);
                        if(data.status == 1){
                            
                            if(data.result.client){
                                var result = data.result;
                                vm.has_chat = true;
                                vm.dm = result.dm;
                                vm.ai = result.ai;
                                vm.group = result.group;
                                vm.team = result.team;
                                if(result.notification_details){
                                    vm.notification_count = result.notification_details.total;
                                }
                                vm.loadPusherInfo();
                            }else if(data.result.message){
                               vm.error_message = "Chat Settings Invalidated.";
                               //vm.error_message = data.result.message;
                            }

                            
                        }else{
                            vm.has_chat = false;
                            if(data.message)
                                vm.error_message = data.message;
                        }
                    });
                });
                //vm.loadPusherInfo();
            },
            loadPusher:function(details){
                
                Pusher.logToConsole = true;

                var pusher = new Pusher('3ba4f1b9531904744a8e', {
                cluster: 'ap1'
                });

                var chat_channel = pusher.subscribe('chat-channel');
                chat_channel.bind('notification', function(data) {
                    console.log(data);
                    //app.messages.push(JSON.stringify(data));
                });
                chat_channel.bind('message', function(data) {
                    console.log(data);
                    //app.messages.push(JSON.stringify(data));
                });
            },
            loadPusherInfo:function(){

                var vm = this;
                
                //FOR MESSAGING PUSH NOTIF INFO
                WebRequest2('GET', '/manage/message/push-notif-info').then(resp=>{
                    resp.json().then(data=>{
                        console.log("NOTIF SETTINGS", data);
                        //if(data.is_active && data.name == 'PUSHER.COM')
                            //vm.loadPusher();
                    });
                });
                
            }
        },
        mounted:function(){     
            this.loadNotification();
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
