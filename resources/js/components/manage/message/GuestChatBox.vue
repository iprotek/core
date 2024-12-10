<template>
    <div>
        <div class="card card-primary card-outline direct-chat direct-chat-primary " style="min-width: 300px;">
            <div class="card-header"><img data-card-widget="" src="/iprotek/images/temp-image.png" alt="Message User Image" class="direct-chat-img" style="width: 30px; height: 30px;"> 
            &nbsp;
            <h3 data-card-widget="" class="card-title py-1 ml-1 text-primary">
                Live Chat Support
            </h3> 
            <div class="card-tools"> 
                <button title="Company Details" type="button" @click="$refs.comp_profile.show()" class="btn btn-tool text-primary" style="border:2px solid gray; padding:1.8px 6px; border-radius:50%;"><i class="fas fa-question"></i></button>
                <button type="button" data-card-widget="collapse" class="btn btn-tool">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" @click="$emit('modal_close')">
                        <i class="fas fa-times text-danger"></i></button>
                </div>
            </div> 
            <div class="card-body" style="display: block;">
                <div id="chat-container-17" class="direct-chat-messages">
                    <div></div> 
                </div>
            </div> 
            <div class="card-footer" style="display: block;">
                <div class="input-group">
                    <span v-if="has_upload" class="input-group-text p-2 px-3">
                        <span class="fa fa-paperclip text-lg text-primary" style="cursor: pointer;"></span>
                    </span> 
                    <input id="chat-input-text-17" type="text" name="message" placeholder="Type Message ..." class="form-control"> 
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary ">Send</button>
                    </span>
                </div>
            </div>
        </div>
        <comp-profile ref="comp_profile"></comp-profile>
    </div>
</template>

<script>
    import CompanyProfileVue from '../../CompanyProfile.vue'

    export default {
        props:[  ],
        components: { 
            "comp-profile":CompanyProfileVue
        },
        data: function () {
            return { 
                has_upload:false
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
