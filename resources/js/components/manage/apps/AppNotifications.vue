<template> 
    <div>
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-th-large"></i>
            <span class="badge badge-warning navbar-badge" > ? </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a class="dropdown-item py-0 text-center text-sm text-black">
                <small>
                    <i class="fas fa-cog"></i> <span > Modules </span>
                </small>
            </a>
            <div class="dropdown-divider"></div>
            <!-- LIST OF MODULES -->
             <!-- Messaging( Administrator ONLY ) -->
             <!-- Tracking( Administrator ONLY ) -->
             <!-- Sms-sender( Administrator ONLY ) -->
             <!-- MGMT( Administrator ONLY ) -->
                <div class="text-center px-1 pt-1 pb-0">
                    <span v-for="free_app in free_apps" v-bind:key="'free-app'+free_app.id">
                        <app-button v-bind:app_info="free_app" v-bind:group_id="group_id" :current_app_name="current_app_name" ></app-button>
                    </span>
                </div>
            <div class="dropdown-divider"></div>
            <span class="dropdown-item dropdown-header p-1 text-black text-bold" > <small>APPLICATIONS</small> </span>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item py-0 pb-1 text-center text-sm text-danger">
                <small v-if="!isLoading && other_apps.length == 0">
                    <span > NO APPS AVAILABLE </span>
                </small>
            </a>
            <div v-if="current_app_name" class="text-center px-1 pt-1 pb-0">
                <span v-for="other_app in other_apps" v-bind:key="'other-app'+other_app.id">
                    <app-button v-bind:app_info="other_app" v-bind:group_id="group_id"  :current_app_name="current_app_name"></app-button>
                </span>
            </div>
            <div class="dropdown-divider"></div>
            
            <a href="#" class="dropdown-item py-0 pb-1 text-center text-sm text-primary">
                <small>
                    <i class="fas fa-plus"></i> <span > Add App </span>
                </small>
            </a>
        </div>
    </div>
</template>

<script>
    import AppButton from './AppButton.vue';
    export default {
        props:[ "group_id","app_name","current_app_name" ],
        components: {
            "app-button": AppButton
        },
        watch:{
            
        },
        data: function () {
            return {    
                summary:{
                    isLoadSummary:false,
                    summaryList:[],
                    total:0,
                },
                updates:{
                    isCheck:false,
                    message:'Check System Updates'
                },
                free_apps:[],
                other_apps:[],
                isLoading:false
            }
        },
        methods: {
            loadApps:function(){
                var vm = this;
                vm.free_apps = [];
                vm.other_apps = [];
                vm.isLoading = true;
                WebRequest2('GET', '/manage/apps/list').then(resp=>{
                    resp.json().then(data=>{
                        vm.isLoading = false;
                        if(data.status==1 && data.result && data.result.status == 1){
                            vm.free_apps = data.result.data.filter(app=>{
                                if(app.defaults && app.defaults.is_notif_visible && app.defaults.is_free){
                                    return true;
                                }
                                return false;
                            });
                            vm.other_apps = data.result.data.filter(app=>{
                                if(app.defaults && app.defaults.is_notif_visible && !app.defaults.is_free){
                                    return true;
                                }
                                return false;
                            });
                        }
                    });
                });
            }

        },
        mounted:function(){ 
            this.loadApps();
        },
        updated:function(){

        }
    }
</script>
