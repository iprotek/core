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
                                <div class="card-header">ALL POLICY CONTROLS</div>
                                <div class="card-body">
                                    <file-tree />

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">

                        </div>
                        <div class="col-sm-5">
                            <div class="card">
                                <div class="card-header">SELECTED POLICY CONTROLS</div>
                                <div class="card-body">
                                    <file-tree />
                                    <file-tree-checking />

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">CURRENT POLICY CONTROL</div>
                                <div class="card-body"> 
                                    <file-tree-checking />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    import FileTreeVue from '../component/FileTree.vue';
    import FileTreeCheckingVue from '../component/FileTreeCheking.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id","role_id", "app_account_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
            "file-tree":FileTreeVue,
            "file-tree-checking":FileTreeCheckingVue
        },
        data: function () {
            return {        
                promiseExec:null,
                errors:[],
                is_default:false,
                policyControlList:[]
           }
        },
        methods:{ 
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
                WebRequest2('GET', '/api/xrac/group/'+this.group_id+'/policy-control/list').then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            console.log(data);
                            vm.policyControlList = data;
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
            }

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
