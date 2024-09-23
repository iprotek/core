<template>
    <div>
        <sms-client-api :group_id="group_id"></sms-client-api>
        <sms-client-message :group_id="group_id"></sms-client-message>
        <sms-client-received-message :group_id="group_id"></sms-client-received-message>
    </div>
</template>

<script>
    import SmsClientApiVue from './SmsClientApi.vue';
    import SmsClientMessagesVue from './SmsClientMessages.vue';
    import SmsClientReceivedMessagesVue from './SmsClientReceivedMessages.vue';
    export default {
        props:[ "group_id" ],
        components: {
            "sms-client-api":SmsClientApiVue,
            "sms-client-message":SmsClientMessagesVue,
            "sms-client-received-message":SmsClientReceivedMessagesVue
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
