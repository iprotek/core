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
                        <select class="form-control">
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
                        <label class="mb-0 text-italic">SIDE BAR COLOR:</label>
                        <select class="form-control">
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
                            <option value="dark-primary">light-primary</option>
                            <option value="dark-secondary">light-secondary</option>
                            <option value="dark-info">light-info</option>
                            <option value="dark-success">light-success</option>
                            <option value="dark-warning">light-warning</option>
                            <option value="dark-danger">light-danger</option>
                            <option value="dark-indigo">light-indigo</option>
                            <option value="dark-lightblue">light-lightblue</option>
                            <option value="dark-navy">light-navy</option>
                            <option value="dark-teal">light-teal</option>
                            <option value="dark-cyan">light-cyan</option>
                            <option value="dark-gray">light-gray</option>
                        </select>
                        <label class="mb-0 text-italic">CARD COLOR:</label>
                        <select class="form-control">
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
                    <div>
                        <label class="mb-0">FRONT THEME</label>
                        <label class="mb-0 text-italic">NAV COLOR:</label>
                        <select class="form-control">
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
                        <label class="mb-0 text-italic">CARD COLOR:</label>
                        <select class="form-control">
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
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    
    export default {
        props:[ "theme_info", "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
        },
        data: function () {
            return {        
                promiseExec:null,
                errors:[],
                dashboard_nav_color:'',
                dashboard_sidebar_color:'',
                dashboard_card_color:'',
                front_nav_color:'',
                front_card_color:''
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                var vm = this;

                this.$refs.modal.show();

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
