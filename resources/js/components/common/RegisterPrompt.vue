<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>REGISTER APP USER</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label> Email: (<span class="text-red">*</span>) </label>
                    <input v-model="email" type="email" class="form-control"/>
                    <validation :errors="errors" :field="'email'"></validation>
                </div>
                <div class="form-group">
                    <label>Password: (<span class="text-red">*</span>) </label>
                    <input v-model="password" type="password" class="form-control"/>
                    <validation :errors="errors" :field="'password'"></validation>
                </div>
                <div class="form-group">
                    <label>V. Password: (<span class="text-red">*</span>) </label>
                    <input v-model="password_confirmation" type="password"  class="form-control"/>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label> First Name: (<span class="text-red">*</span>) </label>
                    <input v-model="first_name" type="text"  class="form-control"/>
                    <validation :errors="errors" :field="'first_name'"></validation>
                </div>
                <div class="form-group">
                    <label> Last Name: (<span class="text-red">*</span>)</label>
                    <input v-model="last_name" type="text"  class="form-control"/>
                    <validation :errors="errors" :field="'last_name'"></validation>
                </div>
                <div class="form-group">
                    <label> Contact No: </label>
                    <input v-model="contact_no" type="text"  class="form-control"/>
                </div>
                <div class="form-group">
                    <label> Company: </label>
                    <input v-model="company" type="text" class="form-control"/>
                </div>
                <div class="form-group">
                    <label> Address: </label>
                    <input v-model="address" type="text" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-primary mb-1" @click="register">Register</button>
                <br/>
                <button class="btn btn-success">Register using Google</button>
            </div> 
        </div>
        <swal-prompt ref="swal_prompt"></swal-prompt>
    </div>
</template>
<script>
    import SwalPrompt from './Swal.vue';
    import Validation from './Validation.vue';
    export default {
        props:[],
        components: {    
            "swal-prompt": SwalPrompt,
            "validation": Validation
        },
        data: function () {
            return {   
                email:"",
                password:"",
                password_confirmation:"",
                first_name:"",
                last_name:"",
                contact_no:"",
                company:"",
                address:"",
                errors:[],
                RegisterPromise:null,
                RegisterResolve:null,
                RegisterReject:null
            }
        },
        methods: { 
            register:function(){
                var req = {
                    email : this.email,
                    password : this.password,
                    password_confirmation: this.password_confirmation,
                    first_name : this.first_name,
                    last_name : this.last_name,
                    contact_no : this.contact_no,
                    company : this.company,
                    address : this.address
                } 
                var vm = this;
                vm.errors = [];
                return this.$refs.swal_prompt.alert('question', 'Register App User' ,'Register', 'POST', '/apps/app-user-register', req, errors=>{
                    //console.log("Error", errors);
                    vm.errors = errors;
                }).then(resp=>{
                    vm.RegisterResolve(resp);
                    return resp; //console.log("Response", resp);
                });

            },
            registerPromise(){
                var vm = this;
                return vm.RegisterPromise = new Promise((resolve, reject)=>{
                    vm.RegisterResolve = resolve;
                    vm.RegisterReject = reject;
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