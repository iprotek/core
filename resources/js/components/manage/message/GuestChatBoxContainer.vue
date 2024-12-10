<template>
    <div>
        
        <div  class="row justify-content-end">
            <div  class="col-sm-2 text-center">
                <span @click="window.ShowChatDialog()" class="ion ion-chatbubble-working text-white" style="font-size:80px; text-shadow: 0px 0px 4px black; cursor:pointer;"></span>
            </div>
        </div>

        <div style="pointer-events:none;">
            <div class="row justify-content-center align-items-center " style="pointer-events:none;">
                <div class="col-md-4" style="pointer-events:all;">
                    <guest-chat-box />
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
            "guest-chat-box":ChatBoxVue
        },
        data: function () {
            return {  
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
