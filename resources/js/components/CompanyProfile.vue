<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :extended_width="true">
            <template slot="header" >
                Chat Support
            </template> 
            <template slot="body" >     
                <div class="row"> 
                    <h5>
                    <code>** We are very sorry, we are still working on chat support system. As of the moment you may use details below as your contact reference.</code>
                    </h5>
                    <div class="card-header">
                        BUSINESS PROFILE
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-12">
                                <user-input2 v-model="business_name" :prepend_icon="'fas fa-building'" :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Name'" :placeholder_description="'This is the name of the legal company.'"  :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <user-input2 v-model="business_address"  :prepend_icon="'ion ion-ios-location-outline'" :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Address'" :placeholder_description="'The exact address where the company located.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <user-input2 v-model="business_tin"  :prepend_icon="'fas fa-hashtag'" :readonly="true" :input_style="'height:38px;'" :placeholder="'Business TIN'" :placeholder_description="'Tax Identification Number of the company.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                            <div class="col-sm-6">
                                <user-input2 v-model="business_mobile" :prepend_icon="'ion ion-iphone'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Mobile#'" :placeholder_description="'You can directly use this number to call sales representative.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <user-input2 v-model="business_tel"  :prepend_icon="'ion ion-ios-telephone-outline'" :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Tel#'" :placeholder_description="'You can this telephone to call the store.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                            <div class="col-sm-6">
                                <user-input2 v-model="business_email" :prepend_icon="'ion ion-ios-email-outline'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Email'" :placeholder_description="'You can use this email to send your intents.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <user-input2 v-model="business_representative" :prepend_icon="'ion ion-person'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Business Representative'" :placeholder_description="'The name of the sales you can ask.'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                            </div>
                        </div>
                        </div>
                    <div class="card-header">
                        SOCAIL LINKS
                    </div>
                    <div class="card-body pt-0">
                        <user-input2 v-if="business_facebook_link" v-model="business_facebook_link" :prepend_icon="'ion ion-social-facebook-outline text-primary'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Facebook'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-if="business_linkedin_link" v-model="business_linkedin_link" :prepend_icon="'ion ion-social-linkedin text-info'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'LinkedIn'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-if="business_twitter_link" v-model="business_twitter_link" :prepend_icon="'ion ion-social-twitter text-cyan'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Twitter'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-if="business_snapchat_link" v-model="business_snapchat_link" :prepend_icon="'ion ion-social-snapchat-outline text-warning'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Snapchat'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-if="business_youtube_link" v-model="business_youtube_link" :prepend_icon="'ion ion-social-youtube text-danger'"  :readonly="true" :input_style="'height:38px;'" :placeholder="'Youtube'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    import UserInput2Vue from '../../../../vendor/iprotek/core/resources/js/components/common/UserInput2.vue';

    export default {
        props:[  ],
        components: {   
            "user-input2":UserInput2Vue
        },
        data: function () {
            return {        
                business_name:'',
                business_address:'',
                business_tin:'',
                business_mobile:'',
                business_tel:'',
                business_email:'',
                business_representative:'',
                business_facebook_link:'',
                business_linkedin_link:'',
                business_twitter_link:'',
                business_snapchat_link:'',
                business_youtube_link:'',
                data_info:''
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                this.$refs.modal.show();
                var vm = this;
                WebRequest2('GET', '/get-profile-data').then(resp=>{
                    resp.json().then(data=>{
                        //console.log("PROFILE", data);
                        //vm.data_info = JSON.stringify(data);
                        vm.business_name = data.business_name;
                        vm.business_address = data.business_address;
                        vm.business_tin = data.business_tin;
                        vm.business_mobile = data.business_mobile;
                        vm.business_tel = data.business_tel;
                        vm.business_email = data.business_email;
                        vm.business_representative = data.business_representative;
                        vm.business_facebook_link = data.business_facebook_link;
                        vm.business_linkedin_link = data.business_linkedin_link;
                        vm.business_twitter_link = data.business_twitter_link;
                        vm.business_snapchat_link = data.business_snapchat_link;
                        vm.business_youtube_link = data.business_youtube_link;
                    });
                })
            },

        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
