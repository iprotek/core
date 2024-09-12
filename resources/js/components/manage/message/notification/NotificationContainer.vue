<template>
    <div>
        <div v-if="data">
            <div class="media px-2" @click.stop="">
                <div class="media-body">
                    <b v-text="header"></b> 
                    <small class="mx-1 badge badge-danger" v-if="data && data.total" v-text="data.total"></small>
                </div>
            </div>
            <div class="dropdown-divider mt-1"></div>
            <div v-for="(chatItem, chatIndex) in users" v-bind:key="'chat-user-'+chatItem.id+'-'+_uid+'-'+chatIndex">
                <a href="#"  class="dropdown-item" @click="clicktChatNotif(chatItem)"> 
                    <div class="media">
                        <img src="/images/temp-image.png" alt="User Avatar" class="img-size-50 mr-3 img-circle"/>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                            <small>
                                <b v-if="chatItem.is_self" class="text-primary"> <i>(Self)</i>   </b> 
                                <b> {{ chatItem.name }} </b>
                            </small>
                            <span class="float-right mx-1 badge badge-danger" v-if="chatItem.count_unseen" v-text="chatItem.count_unseen"></span>
                            </h3>
                            <small>
                            <p class="text-sm" v-if="chatItem.last_message_info && chatItem.last_message_info.message && chatItem.last_message_info.chat_type == 'text'" >
                                <small v-if="chatItem.last_message_info.is_self" class="text-secondary" v-text="'(You) '+limitString(chatItem.last_message_info.message)"></small>
                                <small v-else class="text-primary" v-text="'(Response) '+limitString(chatItem.last_message_info.message)"></small>
                            </p>
                            <p  v-else>
                                <small> <i>Not chat Available.</i> </small>
                            </p></small>
                            <p class="text-sm text-muted" v-if="chatItem.last_message_diff"><i class="far fa-clock mr-1"></i> {{ chatItem.last_message_diff }}</p>
                        </div>
                    </div> 
                </a>
                <div class="dropdown-divider"></div>
            </div>
            <div @click.stop="" class="text-center">
                <div v-if="users.length == 0"  >
                    <code> -- No Chat Available -- </code>
                    <div class="dropdown-divider"></div>
                </div>
                <div v-else-if="data.other_chat_count > 0">
                    <small class="text-center">
                        <small> <i v-text="'and '+data.other_chat_count+' Others '"></i> </small>
                    </small>
                    <div class="dropdown-divider"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "header", "data" ],
        components: { 
        },
        data: function () {
            return { 
                users:[]   
            }
        },
        methods: { 
            clicktChatNotif:function(chatInfo){
                window.addChatMessage(chatInfo);
            },
            limitString:function(text){
                if(text && text.length > 20)
                    return text.substring(0, 18)+'...';
                return text;
            }

        },
        mounted:function(){     
            //console.log("DATA", this.data);
            if(this.data && this.data.result){
                this.users = this.data.result;
            }
        },
        updated:function(){

        }
    }
</script>
