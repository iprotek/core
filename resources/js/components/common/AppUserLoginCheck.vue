<template> 
    <div>
        <modal-view ref="login_prompt_modal">
            <template slot="header" > LOGIN PROMPT </template> 
            <template slot="body"> 
                <login-prompt v-if="login_show" ref="login_prompt" :text_info="text_info" @login_success_result="login_success_result"></login-prompt>
                <register-prompt v-if="register_show" ref="register_prompt"></register-prompt>
                <br/><br/>
                <a style="color:blue; cursor:pointer;" v-if="login_show" @click="registerShow()" v-text="'Register >>'"></a>
                <a style="color:blue; cursor:pointer;" v-if="register_show" @click="loginShow()"  v-text="'<< LOGIN'"></a>
            </template>
            <template slot="footer">
                <button type="button" class="btn btn-default mr-4" data-dismiss="modal" @click="$refs.login_prompt_modal.dismiss()">Close</button>
            </template>
        </modal-view> 
    </div> 
</template>
<script>
    import LoginPrompt from './LoginPrompt.vue';
    import RegisterPrompt from './RegisterPrompt.vue';
    
    export default {
        props:[],
        components: {   
            "login-prompt" : LoginPrompt,
            "register-prompt" : RegisterPrompt
        },
        data: function () {
            return {  
                login_show:false,
                register_show:false,
                text_info:"",
                __callback : null
            }
        },
        methods: {
            loginShow:function(val = null){
                var vm = this;
                vm.register_show=false;
                vm.login_show=true;
                if(val){
                    setTimeout(function(){
                        vm.$refs.login_prompt.email = val.data.email;
                        vm.$refs.login_prompt.errors = {
                            email: [val.data.info]
                        };
                    },100);
                }
            },
            registerShow:function(){
                var vm = this;
                vm.register_show=true;
                vm.login_show=false;
                //console.log("Register Prompt",this.$refs.register_prompt);
                setTimeout(function(){
                    vm.$refs.register_prompt.registerPromise().then(result=>{
                        //console.log("Register Promise",result);
                        if(result.isConfirmed){
                            vm.loginShow(result.value);
                        }
                    });
                }, 100);
            },
            login_success_result:function(data){
                this.$refs.login_prompt_modal.dismiss();
                if(this.__callback){
                    this.__callback(data);
                }
            },
            check:function(text_info="",__callback=null){
                
                var vm = this;
                vm.__callback = __callback;
                vm.text_info = text_info;
                //this.$refs.login_prompt_modal.dismiss();
                vm.login_show = false;
                vm.register_show = false;
               return WebRequest2('GET', '/apps/app-user-check').then(response=>{
                    if(response.ok){
                       return response.json().then(data=>{
                            vm.login_show = (data.status == 0);
                            if(data.status == 0){
                                this.$refs.login_prompt_modal.show();
                            }
                            return data;
                       });
                    }
                });
            },
            load_languages(){ 
                //console.log(vm.languages);
            },
            load_table(){ 
                //vm.dataTable = new DataTable('#table-'+vm._uid, vm.json);
            },
            reload_table(){ 
                //$('#table-'+vm._uid).DataTable(vm.json);
            }

        },
        mounted:function(){     
            var vm = this;
            //if(vm.application.rate){
              //  vm.rate = vm.application.rate;
            //}
        }
    }

</script>
