<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Profile Settings</div>
                    <div class="card-body">  
                        <div class="row">
                            <div class="col-sm-6">
                                <user-input2 :value="email" :input_style="'height:40px;'" :placeholder="'Last Name'" :readonly="true" />
                                <user-input2 v-model="first_name" :input_style="'height:40px;'" :placeholder="'First Name'" />
                                <user-input2 v-model="middle_name" :input_style="'height:40px;'" :placeholder="'Middle Name'" />
                                <user-input2 v-model="last_name" :input_style="'height:40px;'" :placeholder="'Last Name'" />
                                <button class="btn btn-outline-primary mt-2" @click="save">SAVE</button>
                                <button class="btn btn-outline-warning mt-2" @click="$refs.modal_change_pass.show()">Change Password</button>
                            </div>
                            <div class="col-sm-6"> 
                                <div class="card">
                                    <div class="card-header">Personalize</div>
                                    <div class="card-body">
                                        <div class="my-1">
                                            <button class="btn btn-outline-primary btn-sm" @click="$refs.modal_theme.show()"> CUSTOMIZE MY THEME </button>
                                        </div>
                                        <file-uploads v-if="user_admin_id" :gallery_title="'My Profile Image Gallery'" :target_name="'user_admins'" :value="user_admin_id"></file-uploads>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-change-pass v-if="email" ref="modal_change_pass" :email="email" />
        <modal-theme ref="modal_theme" :branch_id="branch_id" :group_id="group_id" :theme_info="theme_info" />
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import FileUploadsVue from '../../common/FileUploads.vue';
    import UserInput2Vue from '../../common/UserInput2.vue';
    import ModalThemeVue from '../company-details/ModalTheme.vue';
    import ModalChangePasswordVue from './ModalChangePassword.vue';
    export default {
        props:[ "theme_info", "group_id",  "user_admin_id" ],
        components: { 
            "file-uploads":FileUploadsVue,
            "user-input2":UserInput2Vue,
            "modal-change-pass":ModalChangePasswordVue,
            "modal-theme":ModalThemeVue
        },
        watch: { 
        },
        data: function () {
            return {
                first_name:'',
                last_name:'',
                middle_name:'',
                email:''
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadInfo:function(){
                var vm = this;
                WebRequest2('GET', '/manage/my-details/info').then(resp=>{
                    resp.json().then(data=>{
                        vm.first_name = data.first_name;
                        vm.last_name = data.last_name;
                        vm.middle_name = data.middle_name;
                        vm.email = data.user_admin.email;
                    })
                });
            },
            save:function(){
                var vm = this; 
                var request = {
                    first_name: this.first_name,
                    middle_name: this.middle_name,
                    last_name: this.last_name
                }
                this.$refs.swal_prompt.alert(
                    'question',
                    "Update My info", 
                    "Confirm" , 
                    "PUT", 
                    "/manage/my-details/info", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            window.location.reload();
                        }
                        //vm.$emit('data_updated');
                    }
                }); 
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            }
        },
        mounted:function(){   
            this.loadInfo();  
        },
        updated:function(){

        }
    }
</script>
