<template>
    <label @click="link_click" onclick="ForgotPasswordClick()">
        &nbsp;&nbsp;&nbsp;<span class="text-primary" style="cursor:pointer;" >Forgot Password</span>
        <modal-forgot-password ref="modal_forgot_password"></modal-forgot-password>
    </label>
</template>

<script>
    import ModalForgotPasswordVue from './ModalForgotPassword.vue'
    export default {
        props:[  ],
        components: { 
            "modal-forgot-password":ModalForgotPasswordVue
        },
        data: function () {
            return {    
            }
        },
        methods: { 
            link_click:function(){
                this.$refs.modal_forgot_password.show();
            }

        },
        mounted:function(){     
            window.ForgotPasswordClick = this.link_click;
        },
        updated:function(){

        }
    }
</script>
