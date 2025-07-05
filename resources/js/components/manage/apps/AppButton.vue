<template>
    <a :href="getUrl()" v-if="app_info" :class="'btn btn-app mx-1 mb-1 '+(app_info.name == current_app_name ? 'bg-gray' : '') ">
        <div class="badge mr-1">
            <small v-if="app_info.notifications.info" class="bg-info p-1 rounded-2" v-text="app_info.notifications.info"></small>
            <small v-if="app_info.notifications.warning" class="bg-warning p-1 rounded-2" v-text="app_info.notifications.warning"></small>
            <small v-if="app_info.notifications.danger" class="bg-danger p-1 rounded-2" v-text="app_info.notifications.danger"></small>
            <small v-if="app_info.notifications.success" class="bg-success p-1 rounded-2" v-text="app_info.notifications.success"></small>
        </div>
        <i :class="button_icon"></i> {{  title }}
    </a>
</template>

<script>
    export default {
        props:[ "group_id", "pay_account_id", "app_info","current_app_name" ],
        $emits:[],
        watch: { 
        },
        components: { 
        },
        data: function () {
            return {
                title: 'Button',
                button_icon:"fas fa-envelope",
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

            getUrl:function(){ 
                if(this.app_info && this.app_info.public_url){
                    return (this.app_info.is_https ? 'https://':'http://')+ ( this.app_info.is_default ? this.app_info.www : this.app_info.public_url )+'/manage?account_id='+this.pay_account_id;
                }
                return '#';
            }

        },
        mounted:function(){ 
            //console.log("AppButton mounted", this.app_info);
            if(this.app_info){
                if(this.app_info.defaults && this.app_info.defaults.title){
                    this.title = this.app_info.defaults.title;
                    if(this.app_info.defaults.icon_class)
                        this.button_icon = this.app_info.defaults.icon_class;
                }
            }
        },
        updated:function(){

        }
    }
</script>
