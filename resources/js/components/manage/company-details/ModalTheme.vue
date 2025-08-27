<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0 mt-2'">
            <template slot="header" >
                THEME COLOR
            </template> 
            <template slot="body" > 
                <div>
                    <div>
                        <label class="mb-0">DASHBOARD THEME</label>
                    </div>
                    <div>
                        <label class="mb-0"> <i> NAV COLOR: </i> </label>
                        <select v-model="theme_dashboard_nav_color" class="form-control" @change="dashboardThemeChanged()">
                            <option value="dark navbar-dark">dark navbar-dark</option>
                            <option value="dark navbar-primary">dark navbar-primary</option>
                            <option value="dark navbar-info">dark navbar-info</option>
                            <option value="dark navbar-success">dark navbar-success</option>
                            <option value="dark navbar-danger">dark navbar-danger</option>
                            <option value="dark navbar-indigo">dark navbar-indigo</option>
                            <option value="dark navbar-purple">dark navbar-purple</option>
                            <option value="dark navbar-pink">dark navbar-pink</option>
                            <option value="dark navbar-navy">dark navbar-navy</option>
                            <option value="dark navbar-lightblue">dark navbar-lightblue</option>
                            <option value="dark navbar-teal">dark navbar-teal</option>
                            <option value="dark navbar-cyan">dark navbar-cyan</option>
                            <option value="dark navbar-gray-dark">dark navbar-gray-dark</option>
                            <option value="light navbar-light">light navbar-light</option>
                            <option value="light navbar-info">light navbar-info</option>
                            <option value="light navbar-success">light navbar-success</option>
                            <option value="light navbar-warning">light navbar-warning</option>
                            <option value="light navbar-danger">light navbar-danger</option>
                            <option value="light navbar-indigo">light navbar-indigo</option>
                            <option value="light navbar-lightblue">light navbar-lightblue</option>
                            <option value="light navbar-navy">light navbar-navy</option>
                            <option value="light navbar-teal">light navbar-teal</option>
                            <option value="light navbar-cyan">light navbar-cyan</option>
                            <option value="light navbar-gray">light navbar-gray</option>
                        </select>
                        <label class="mb-0 text-italic"> <i> SIDE BAR COLOR: </i> </label>
                        <select v-model="theme_dashboard_sidebar_color" class="form-control" @change="dashboardThemeChanged()">
                            <option value="dark-primary">dark-primary</option>
                            <option value="dark-secondary">dark-secondary</option>
                            <option value="dark-info">dark-info</option>
                            <option value="dark-success">dark-success</option>
                            <option value="dark-warning">dark-warning</option>
                            <option value="dark-danger">dark-danger</option>
                            <option value="dark-indigo">dark-indigo</option>
                            <option value="dark-lightblue">dark-lightblue</option>
                            <option value="dark-navy">dark-navy</option>
                            <option value="dark-teal">dark-teal</option>
                            <option value="dark-cyan">dark-cyan</option>
                            <option value="dark-gray-dark">dark-gray-dark</option>
                            <option value="light-primary">light-primary</option>
                            <option value="light-secondary">light-secondary</option>
                            <option value="light-info">light-info</option>
                            <option value="light-success">light-success</option>
                            <option value="light-warning">light-warning</option>
                            <option value="light-danger">light-danger</option>
                            <option value="light-indigo">light-indigo</option>
                            <option value="light-lightblue">light-lightblue</option>
                            <option value="light-navy">light-navy</option>
                            <option value="light-teal">light-teal</option>
                            <option value="light-cyan">light-cyan</option>
                            <option value="light-gray">light-gray</option>
                        </select>
                        <label class="mb-0 text-italic"> <i> CARD COLOR: </i> </label>
                        <select v-model="theme_dashboard_card_color" class="form-control" @change="dashboardThemeChanged()">
                            <option value=""></option>
                            <option value="primary">primary</option>
                            <option value="secondary">secondary</option>
                            <option value="info">info</option>
                            <option value="success">success</option>
                            <option value="warning">warning</option>
                            <option value="danger">danger</option>
                            <option value="indigo">indigo</option>
                            <option value="lightblue">lightblue</option>
                            <option value="navy">navy</option>
                            <option value="teal">teal</option>
                            <option value="cyan">cyan</option>
                            <option value="gray">gray</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <div>
                            <label class="mb-0">FRONT THEME</label>
                        </div>
                        <label class="mb-0 text-italic"> <i> NAV COLOR: </i> </label>
                        <select v-model="theme_front_nav_color" class="form-control">
                            <option value="dark navbar-dark">dark navbar-dark</option>
                            <option value="dark navbar-primary">dark navbar-primary</option>
                            <option value="dark navbar-info">dark navbar-info</option>
                            <option value="dark navbar-success">dark navbar-success</option>
                            <option value="dark navbar-danger">dark navbar-danger</option>
                            <option value="dark navbar-indigo">dark navbar-indigo</option>
                            <option value="dark navbar-purple">dark navbar-purple</option>
                            <option value="dark navbar-pink">dark navbar-pink</option>
                            <option value="dark navbar-navy">dark navbar-navy</option>
                            <option value="dark navbar-lightblue">dark navbar-lightblue</option>
                            <option value="dark navbar-teal">dark navbar-teal</option>
                            <option value="dark navbar-cyan">dark navbar-cyan</option>
                            <option value="dark navbar-gray-dark">dark navbar-gray-dark</option>
                            <option value="light navbar-info">light navbar-info</option>
                            <option value="light navbar-success">light navbar-success</option>
                            <option value="light navbar-warning">light navbar-warning</option>
                            <option value="light navbar-danger">light navbar-danger</option>
                            <option value="light navbar-indigo">light navbar-indigo</option>
                            <option value="light navbar-lightblue">light navbar-lightblue</option>
                            <option value="light navbar-navy">light navbar-navy</option>
                            <option value="light navbar-teal">light navbar-teal</option>
                            <option value="light navbar-cyan">light navbar-cyan</option>
                            <option value="light navbar-gray">light navbar-gray</option>
                        </select>
                        <label class="mb-0 text-italic"> <i> CARD COLOR: </i> </label>
                        <select v-model="theme_front_card_color" class="form-control">
                            <option value=""></option>
                            <option value="primary">primary</option>
                            <option value="secondary">secondary</option>
                            <option value="info">info</option>
                            <option value="success">success</option>
                            <option value="warning">warning</option>
                            <option value="danger">danger</option>
                            <option value="indigo">indigo</option>
                            <option value="lightblue">lightblue</option>
                            <option value="navy">navy</option>
                            <option value="teal">teal</option>
                            <option value="cyan">cyan</option>
                            <option value="gray">gray</option>
                        </select>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>                    
                    <web-submit :action="resetTheme" el_class="btn btn-outline-warning mr-4 btn-sm" icon_class="fa fa-undo" label="RESTORE DEFAULTS" :timeout="3000" />
                    <web-submit :action="save" el_class="btn btn-outline-primary btn-sm mr-4" icon_class="fa fa-save" label="SAVE" :timeout="3000" />
                    <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal" @click="$refs.modal.dismiss()">
                        <span class="fa fa-times"></span> Close
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    import WebSubmitVue from '../../common/WebSubmit.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: {
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {        
                promiseExec:null,
                errors:[],
                theme_dashboard_nav_color:'light navbar-light',
                theme_dashboard_sidebar_color:'dark-primary',
                theme_dashboard_card_color:'',
                theme_front_nav_color:'dark navbar-primary',
                theme_front_card_color:''
           }
        },
        methods:{ 
            save:function(){
                var vm = this;
                var request = {
                    theme_dashboard_nav_color: vm.theme_dashboard_nav_color,
                    theme_dashboard_sidebar_color: vm.theme_dashboard_sidebar_color,
                    theme_dashboard_card_color: vm.theme_dashboard_card_color,
                    theme_front_nav_color: vm.theme_front_nav_color,
                    theme_front_card_color: vm.theme_front_card_color
                }
                return WebRequest2('POST', '/manage/company-details/update-theme', JSON.stringify(request) ).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    })
                });
            },
            resetTheme:function(){
                return WebRequest2('POST', '/manage/company-details/reset-theme', JSON.stringify({}) ).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    })
                });
            },
            reset:function(){

            },
            dashboardThemeChanged:function(){
                var vm = this;
                let topNav = document.querySelector('nav#top-menu');
                let sideBar = document.querySelector('aside#main-sidebar');
                if(topNav){
                    topNav.setAttribute("class", "main-header navbar navbar-expand navbar-"+(vm.theme_dashboard_nav_color));
                }
                if(sideBar){
                    sideBar.setAttribute("class", "main-sidebar elevation-4 sidebar-"+(vm.theme_dashboard_sidebar_color));
                }
                //ALL CARD
                let cards = document.querySelectorAll('div.card');
                cards.forEach(card=>{
                    card.setAttribute("class", "card card-"+ vm.theme_dashboard_card_color);
                })

            },
            show:function(){ 
                var vm = this;

                this.$refs.modal.show();
                WebRequest2('GET', '/manage/company-details/get-theme').then(resp=>{
                    resp.json().then(data=>{
                        console.log("THEME DATA", data);
                        vm.theme_dashboard_nav_color = data.theme_dashboard_nav_color;
                        vm.theme_dashboard_sidebar_color = data.theme_dashboard_sidebar_color;
                        vm.theme_dashboard_card_color = data.theme_dashboard_card_color;
                        vm.theme_front_nav_color = data.theme_front_nav_color;
                        vm.theme_front_card_color = data.theme_front_card_color;
                    });
                })

                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
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
