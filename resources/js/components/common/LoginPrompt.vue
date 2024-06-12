<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>APP USER LOGIN</h4>
                <label v-text="text_info" class="text-info"></label>
                <div class="form-group">
                    <label>Email / Account No.:</label>
                    <input class="form-control" type="text" v-model="email" @keyup.enter="login_app_user"/>
                    <validation :errors="errors" :field="'email'" />
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input class="form-control" type="password" v-model="password" @keyup.enter="login_app_user"/>
                    <validation :errors="errors" :field="'password'" />
                </div>
                <div class="form-group">
                    <input type="checkbox" :checked="remember"/> Remember me.
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" @click="login_app_user">Login</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
//import Validation from './Validation.vue';
    export default {
        props:["text_info"],
        components: {
                //Validation    
        },
        data: function () {
            return {   
                email:"",
                password: "",
                remember:false,
                errors:[]
            }
        },
        methods: {

            login_success_result:function(data){
                this.$emit('login_success_result', data);
            },
            login_app_user:function(){
                var req = {
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                }
                var vm = this;
                WebRequest2('POST','/apps/login-app-user',JSON.stringify(req)).then(resp=>{
                    if(resp.ok){
                        resp.json().then(data=>{
                            //console.log(data);
                            if(data.status == 1){
                                vm.login_success_result(data);
                            }
                        });
                    }else{
                        resp.json().then(data=>{
                            vm.errors = data;
                        })
                    }
                }).catch(resp=>{    
                    console.log(resp);
                });
            }

        },
        mounted:function(){     
            var vm = this; 
        },
        updated:function(){

        }
    }

</script>
