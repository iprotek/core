<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="(app_account_id? 50: 80)">
            <template #header >
                POLICY CONTROL
            </template> 
            <template #body >     
                <div class="py-1">
                    <div class="row" v-if="!app_account_id">
                        <div class="col-sm-5" >
                            <div class="card">
                                <div class="card-header"> <small><b> ALL POLICY CONTROLS</b></small> </div>
                                <div class="card-body">
                                    <file-tree 
                                        v-if="isLoadRoutes" 
                                        :routes="routes" 
                                        :policyControlList="policyControlList" 
                                        @move-route="moveToSelected"
                                        @move-routes="moveMultipleToSelected"
                                    />

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center">
                            <button class="btn btn-outline-primary btn-sm text-nowrap" @click="unloadAllPolicies">
                                <span class="fa fa-arrow-left"></span> UNSELECT ALL
                            </button>
                        </div>
                        <div class="col-sm-5">
                            <div class="card">
                                <div class="card-header"> <small><b> SELECTED POLICY CONTROLS</b></small> </div>
                                <div class="card-body">
                                    <file-tree 
                                        :is_plus="false" 
                                        :routes="selectedRoutes"
                                        @move-route="moveToAll"
                                        :policyControlList="policyControlList" 
                                        @move-routes="moveMultipleToAll"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">CURRENT POLICY CONTROL</div>
                                <div class="card-body"> 
                                    <file-tree-checking 
                                        v-if="selectedRoutes.length > 0" 
                                        :routes="selectedRoutes"
                                        :policyControlList="policyControlList"
                                        :uncheckedRoutes="uncheckedRoutes"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <web-submit v-if="!app_account_id" el_class="btn btn-outline-primary" :action="saveRolePolicy"  :icon_class="'fa fa-save'" :label="'SAVE ROLE'"  />
                    <web-submit v-else el_class="btn btn-outline-primary" :action="saveUserRolePolicy"  :icon_class="'fa fa-save'" :label="'SAVE USER ROLE'"  />
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    import WebSubmitVue from '../../../common/WebSubmit.vue';
    import FileTreeVue from '../component/FileTree.vue';
    import FileTreeCheckingVue from '../component/FileTreeCheking.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id","role_id", "app_account_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
            "file-tree":FileTreeVue,
            "file-tree-checking":FileTreeCheckingVue,
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {        
                promiseExec:null,
                errors:[],
                is_default:false,
                policyControlList:[],
                isLoadRoutes:true,
                routes:[
                    "api.cms.save",
                    "api.collector.batch.add",
                    "api.collector.batch.get",
                    "api.collector.batch.list",
                    "api.collector.branch.add",
                    "api.collector.branch.collection-list",
                    "api.collector.branch.get",
                    "api.collector.branch.list",
                    "api.collector.branch.my-collection-dashboard",
                    "api.collector.branch.remove",
                    "api.collector.branch.settings.get",
                    "api.collector.branch.settings.set",
                    "api.collector.collection.calendar.my-collection-events",
                    "api.collector.collection.calendar.my-collections",
                    "api.collector.collection.due.list",
                    "api.collector.collection.google-map.find-subscriber",
                    "api.data-model.model-fields.field.add",
                    "api.data-model.model-fields.field.remove",
                    "api.data-model.model-fields.field.update",
                    "api.data-model.model-fields.index"
                ],
                initialRoutes: [],
                selectedRoutes: [],
                uncheckedRoutes:[]
           }
        },
        methods:{ 
            getRolePolicy:function(){
                var vm = this;
                let url = `/api/group/${this.group_id}/xrac/policy-control/role-routes/${vm.role_id}?branch_id=${this.branch_id}`;
                return WebRequest2('GET', url).then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            return data;
                        });
                    }
                    return [];
                });
            },
            getUserDisablePolicy:function(){
                var vm = this;
                let url = `http://billing.iprotek.internal/api/group/${this.group_id}/xrac/policy-control/user-disable-routes/${this.app_account_id}`;
                return WebRequest2('GET', url).then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            return data;
                        });
                    }
                    return [];
                });
            },
            saveRolePolicy:function(){
                var vm = this;
                //console.log(vm.group_id, vm.branch_id, vm.role_id, vm.app_account_id);
                var request = {
                    xrole_id: vm.role_id,
                    branch_id: vm.branch_id,
                    policy_control_routes: vm.selectedRoutes
                };
                //console.log(request);
                //return;
                return WebRequest2('POST', `/api/group/${this.group_id}/xrac/policy-control/update-role`, JSON.stringify(request)).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    });
                })
            },
            saveUserRolePolicy:function(){
                var vm = this;
                console.log(vm.group_id, vm.branch_id, vm.role_id, vm.app_account_id);
            },
            reset:function(){

            },
            show:function(is_default=false){ 
                //console.log(this.group_id);
                var vm = this;
                vm.is_default = is_default;

                this.$refs.modal.show();

                
                this.loadPolicy();
                


                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            loadPolicy(){
                var vm = this;
                vm.isLoadRoutes = false;
                WebRequest2('GET', '/api/group/'+this.group_id+'/xrac/policy-control/list').then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            //console.log(data);
                            vm.routes = [];
                            vm.uncheckedRoutes = [];
                            let routes = [];
                            vm.policyControlList = data;

                            
                            //LOAD ROLE POLICY
                            vm.getRolePolicy().then(selectedPolicyRoutes=>{
                                
                                data.forEach(item=>{

                                    let hasItem = selectedPolicyRoutes.filter(a=>a == item.name)[0];
                                    if(!hasItem)
                                        routes.push(item.name);

                                });
                                //console.log(routes);
                                vm.routes = routes;
                                vm.initialRoutes = [...routes];
                                vm.selectedRoutes = selectedPolicyRoutes;


                                //LOAD USER DISABLED ROUTES
                                if(vm.app_account_id){
                                    vm.getUserDisablePolicy().then(data=>{
                                        //console.log("User disabled policy loaded.");
                                        vm.uncheckedRoutes = data;
                                        vm.isLoadRoutes = true;
                                    });
                                }
                                else{
                                    vm.isLoadRoutes = true;
                                }


                            });
                       


                        });
                    }
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
            },
            moveToSelected(route) {
                const idx = this.routes.indexOf(route);
                if (idx > -1) {
                    this.routes.splice(idx, 1);
                }
                if (!this.selectedRoutes.includes(route)) {
                    this.selectedRoutes.push(route);
                    this.selectedRoutes.sort();
                }
            },
            moveMultipleToSelected(routesList) {
                routesList.forEach(route => {
                    const idx = this.routes.indexOf(route);
                    if (idx > -1) {
                        this.routes.splice(idx, 1);
                    }
                    if (!this.selectedRoutes.includes(route)) {
                        this.selectedRoutes.push(route);
                    }
                });
                this.selectedRoutes.sort();
            },
            moveToAll(route) {
                const idx = this.selectedRoutes.indexOf(route);
                if (idx > -1) {
                    this.selectedRoutes.splice(idx, 1);
                }
                if (!this.routes.includes(route)) {
                    this.routes.push(route);
                    this.routes.sort();
                }
            },
            moveMultipleToAll(routesList) {
                routesList.forEach(route => {
                    const idx = this.selectedRoutes.indexOf(route);
                    if (idx > -1) {
                        this.selectedRoutes.splice(idx, 1);
                    }
                    if (!this.routes.includes(route)) {
                        this.routes.push(route);
                    }
                });
                this.routes.sort();
            },
            unloadAllPolicies() {
                this.routes = [...this.initialRoutes];
                this.selectedRoutes = [];
            }

        },
        mounted:function(){      
            this.initialRoutes = [...this.routes];
        },
        updated:function(){

        }
    }
</script>
