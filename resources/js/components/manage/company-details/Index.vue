<template>
    <div>
        <div class="row"> 
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        BUSINESS PROFILE
                    </div>
                    <div class="card-body pt-0">
                        <user-input2 v-model="business_name" :prepend_icon="'fas fa-building'" :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Name'" :placeholder_description="'Name of Business'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_address"  :prepend_icon="'ion ion-ios-location-outline'" :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Address'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_tin"  :prepend_icon="'fas fa-hashtag'" :disabled="false" :input_style="'height:35px;'" :placeholder="'Business TIN'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_mobile" :prepend_icon="'ion ion-iphone'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Mobile#'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_tel"  :prepend_icon="'ion ion-ios-telephone-outline'" :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Tel#'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_email" :prepend_icon="'ion ion-ios-email-outline'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Email'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_representative" :prepend_icon="'ion ion-person'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Business Representative'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                    </div>
                    <div class="card-header rounded-0">
                        SOCAIL LINKS
                    </div>
                    <div class="card-body pt-0">
                        <user-input2 v-model="business_facebook_link" :prepend_icon="'ion ion-social-facebook-outline text-primary'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Facebook'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_linkedin_link" :prepend_icon="'ion ion-social-linkedin text-info'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'LinkedIn'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_twitter_link" :prepend_icon="'ion ion-social-twitter text-cyan'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Twitter'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_snapchat_link" :prepend_icon="'ion ion-social-snapchat-outline text-warning'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Snapchat'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="business_youtube_link" :prepend_icon="'ion ion-social-youtube text-danger'"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Youtube'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                    </div>
                    <div class="card-header rounded-0">
                        BOOKING SITES
                    </div>
                    <div class="card-body pt-0">
                        <user-input2 v-model="booking_airbnb_link" :prepend_icon="' '"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Air Bnb'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="booking_link" :prepend_icon="' '"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Booking'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="booking_vrbo_link" :prepend_icon="' '"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Vrbo'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                        <user-input2 v-model="booking_expedia_link" :prepend_icon="' '"  :disabled="false" :input_style="'height:35px;'" :placeholder="'Expedia'" :placeholder_description="''" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'" ></user-input2>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-outline-primary" @click="updateProfile()">
                            SAVE
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        DESIGNS AND THEMES
                    </div>
                    <button class="btn btn-outline-primary btn-lg m-1" @click="$refs.modal_business_background.show()">
                        Background Images
                    </button>
                    <button class="btn btn-outline-primary btn-lg m-1" @click="$refs.modal_theme.show()">
                        Theme Color
                    </button>
                    <button class="btn btn-outline-primary btn-lg m-1" @click="$refs.modal_business_logos.show()">
                        Icon/Logo
                    </button>
                    <button class="btn btn-outline-primary btn-lg m-1" @click="$refs.modal_terms_conditions.show()">
                       Terms and Conditions
                    </button>
                    <button class="btn btn-outline-primary btn-lg m-1 disabled" @click="$refs.modal_about_us.show()">
                        About Us
                    </button>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt" ></swal>
        <modal-business-backgrounds ref="modal_business_background"></modal-business-backgrounds>
        <modal-business-logos ref="modal_business_logos"></modal-business-logos> 
        <modal-terms-conditions ref="modal_terms_conditions"></modal-terms-conditions>
        <modal-about-us ref="modal_about_us"></modal-about-us>
        <modal-theme :theme_info="theme_info" :branch_id="branch_id" :group_id="group_id"  ref="modal_theme" />
    </div>
</template>

<script>
    import UserInput2Vue from '../../common/UserInput2.vue'
    import ModalBusinessBackgroundsVue from './ModalBusinessBackgrounds.vue'
    import ModalBusinessLogosVue from './ModalBusinessLogos.vue'
    import ModalTermsandConditionVue from './ModalTermsandCondition.vue'
    import ModalAboutUs from './ModalAboutUs.vue'
    import ModalThemeVue from './ModalTheme.vue'
    export default {
        props:["theme_info", "group_id", "branch_id" ],
        components: { 
            "user-input2":UserInput2Vue,
            "modal-business-backgrounds": ModalBusinessBackgroundsVue,
            "modal-business-logos": ModalBusinessLogosVue,
            "modal-terms-conditions" : ModalTermsandConditionVue,
            'modal-about-us' : ModalAboutUs,
            'modal-theme' : ModalThemeVue
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
                
                booking_airbnb_link: '',
                booking_link:'',
                booking_vrbo_link:'',
                booking_expedia_link:''
            }
        },
        methods: { 
            updateProfile:function(){
                var vm = this;
                var req = {
                    business_name: vm.business_name,
                    business_address: vm.business_address,
                    business_tin: vm.business_tin,
                    business_mobile: vm.business_mobile,
                    business_tel: vm.business_tel,
                    business_email: vm.business_email,
                    business_representative: vm.business_representative,
                    business_facebook_link: vm.business_facebook_link,
                    business_linkedin_link: vm.business_linkedin_link,
                    business_twitter_link: vm.business_twitter_link,
                    business_snapchat_link: vm.business_snapchat_link,
                    business_youtube_link : vm.business_youtube_link,
                    booking_airbnb_link: vm.booking_airbnb_link,
                    booking_link : vm.booking_link,
                    booking_vrbo_link : vm.booking_vrbo_link,
                    booking_expedia_link : vm.booking_expedia_link
                };
                this.$refs.swal_prompt.alert(
                        'question',
                        "Would you like to update your business profile?", 
                        "Confirm" , 
                        "POST", 
                        "/manage/company-details/update-profile", 
                        JSON.stringify(req)
                    ).then(res=>{
                        if(res.isConfirmed){

                        }
                    });
            },
            loadDetails:function(){
                var vm = this;
                WebRequest2('GET', '/manage/company-details/get-profile').then(resp=>{
                    resp.json().then(data=>{
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

                        vm.booking_airbnb_link = data.booking_airbnb_link;
                        vm.booking_link = data.booking_link;
                        vm.booking_vrbo_link = data.booking_vrbo_link;
                        vm.booking_expedia_link = data.booking_expedia_link;
                    
                    });
                })
            }
        },
        mounted:function(){    
            this.loadDetails();
        },
        updated:function(){

        }
    }
</script>
