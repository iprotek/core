<template>
    <div>
        <modal-view ref="modal" :body_class="'pt-0'">
            <template slot="header" >
                Verify Email
            </template> 
            <template slot="body" >
                <div class="my-2">
                    <user-input2 v-model="email" :placeholder="'Email'" :input_style="'height:40px;'" :placeholder_description="'Will receive the verification code.'" :readonly="true" />
                    <div class="my-4">
                        <div class="input-group text-lg input-group-lg">
                            <input v-model="code" type="text" class="form-control" placeholder="Verification Code here.."/>
                            <span :class="'input-group-text btn btn-primary '+(code?'':'disabled')">
                            <!--    Verify Now! -->
                                <web-submit :action="submitCode" :label="'Verify Now!'" />
                            </span>
                        </div>
                        <validation :errors="errors" :field="'code'"></validation>
                    </div>
                    <button @click="requestCode" type="button" :class="'btn btn-outline-primary '+( time_count || attempt_count>=3 ? 'disabled':'')" >
                        Request Verification Code <span v-if="time_count && time_count < 120"> ( {{ 120 - time_count}} ) </span>
                    </button>
                    <p class="text-secondary">
                        <small>
                            *The intention of this verification is allowing the support to verify the actual guest and do certain request without requiring guest to login.
                            This portal is useful to hande multiple system requests.
                        </small>
                    </p>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div> 
</template>

<script>
    import UserInput2Vue from '../../common/UserInput2.vue';
    import ValidationVue from '../../common/Validation.vue';
    import WebSubmitVue from '../../common/WebSubmit.vue';
    export default {
        props:[  ],
        components: {  
            "user-input2":UserInput2Vue,
            "web-submit":WebSubmitVue,
            "validation":ValidationVue
        },
        data: function () {
            return {        
                promiseExec:null,
                code:'',
                email:'',
                time_count:0,
                errors:[],
                is_counting:false,
                attempt_count:0
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                var vm = this;

                this.$refs.modal.show();

                vm.loadGuestCountdown();

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            loadGuestCountdown:function(){
                var vm = this;
                WebRequest2("GET", "/guest-chat/verification-code/get-countdown").then(resp=>{
                    resp.json().then(data=>{
                        console.log("VERIFY",data)
                        if(!resp.ok){
                            vm.errors = data;
                            return data;
                        }
                        vm.email = data.email;
                        vm.time_count = data.seconds;
                        vm.attempt_count = data.guest_chat_verify_attempts;
                        if(vm.time_count){
                            if(!vm.is_counting){
                                vm.is_counting = true;
                                vm.startCount();
                            }
                        }

                        //console.log(data);
                    })
                })
            },
            startCount:function(){
                var vm = this;
                if(!vm.time_count || vm.time_count < 0 || vm.time_count >= 120){
                    vm.time_count = 0;
                    vm.is_counting = false;
                    return;
                } 
                setTimeout(()=>{ 
                    vm.time_count++;
                    vm.startCount();
                }, 1000);
            },
            requestCode:function(){
                var vm = this;
                console.log("Clicked");
                WebRequest2('POST', '/guest-chat/verification-code/request', '{}').then(resp=>{
                    resp.json().then(data=>{
                        
                        if(data.status == 1)
                            vm.loadGuestCountdown();

                    });
                });
            },
            submitCode:function(){
                var vm = this;
                vm.errors = [];
                return WebRequest2("POST", "/guest-chat/verification-code/submit-code/"+vm.code,'{}').then(resp=>{
                    return resp.json().then(data=>{
                        if(!resp.ok){
                            vm.errors = data;
                            return data;
                        }
                        //FOR MODAL DISMISSAL AND EMIT VERIFIED
                        setTimeout(()=>{
                            vm.$emit('reload_chat_info');
                            vm.$refs.modal.dismiss();
                        }, 2000);
                        
                        return data;
                    });
                })
            },
            add:function(){
                var vm = this;
                /*
                    this.$refs.swal_prompt.alert(
                        'question',
                        "Add Event", 
                        "Confirm" , 
                        "POST", 
                        "/manage/dashboard/resort-events/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            vm.$emit('data_updated');
                        }
                    });
                */
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
