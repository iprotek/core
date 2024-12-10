<template>
    <div>
        
        <div v-if="show_chat == false" class="row justify-content-end">
            <div  class="col-sm-2 text-center">
                <span @click="show_chat=true" class="ion ion-chatbubble-working text-white" style="font-size:80px; text-shadow: 0px 0px 4px black; cursor:pointer;"></span>
            </div>
        </div>

        <div v-else style="pointer-events:none;">
            <div class="row justify-content-center align-items-center " style="pointer-events:none;">
                <div class="col-md-4" style="pointer-events:all;">
                    <guest-chat-box @modal_close="show_chat=false " />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ChatBoxVue from './GuestChatBox.vue';
    export default {
        props:[  ],
        components: { 
            "guest-chat-box":ChatBoxVue,
            
        },
        data: function () {
            return {  
                show_chat:false
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
