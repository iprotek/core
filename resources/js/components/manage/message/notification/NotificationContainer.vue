<template>
    <div>
        <div v-if="data">
            <div class="media px-2" @click.stop="">
                <div class="media-body">
                    <b v-text="header"></b> 
                    <small class="mx-1 badge badge-danger">3</small>
                </div>
            </div>
            <div class="dropdown-divider mt-1"></div>
            <div v-for="(chatItem, chatIndex) in users" v-bind:key="'chat-user-'+chatItem.id+'-'+_uid+'-'+chatIndex">
                <a href="#"  class="dropdown-item" @click="clicktChatNotif(chatItem)"> 
                    <div class="media">
                        <img src="/images/temp-image.png" alt="User Avatar" class="img-size-50 mr-3 img-circle"/>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                            {{ chatItem.name }}
                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
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
                <div v-else>
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
                window.addChatMessage({});
            },

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
