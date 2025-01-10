<template>
    <div class="mt-2">
        <small><b>Branch:</b></small>
        <div class="input-group w-100" >
            <label class="input-group-text p-0" >
                <div style="min-width:300px;">
                    <select2 :modal_selector="modal_selector" v-model="item_branch" :has_clear="true" @selected="brandSelected" :placeholder="' -- SELECT ALL BRANCH -- '"  :url="'/api/group/'+group_id+'/branch/list'" ></select2>
                </div>
            </label>
            <button v-if="item_branch.id <= 0 && item_branch.id != default_branch.id" class="btn btn-outline-primary btn-sm" @click="setDefaultBranch">
               Clear Branch?
            </button>
            <button v-else-if="item_branch.id != default_branch.id" class="btn btn-outline-primary btn-sm" @click="setDefaultBranch">
               Default Branch?
            </button>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import Select2Vue from '../common/Select2.vue';
    export default {
        props:[ "group_id", "modal_selector", "can_set_default" ],
        components: {
            "select2":Select2Vue 
        },
        data: function () {
            return {    
                item_branch:{
                    id:0,
                    text:''
                },
                default_branch:{
                    id:0,
                    text:''
                }
            }
        },
        methods: { 
            brandSelected:function(branch){
                this.$emit('selected', this.item_branch);
            },
            loadBranchDefaults:function(){
                var vm = this;
                //this.$emit('selected', this.item_branch );
                setTimeout(()=>{
                    WebRequest2('GET', '/api/group/'+this.group_id+'/branch/my-default').then(resp=>{
                        resp.json().then(data=>{
                            //console.log("DATA: ", data);
                            if(data.branch){
                                vm.item_branch = {
                                    id:data.branch.id,
                                    text:data.branch.name
                                }
                                vm.default_branch = {
                                    id:data.branch.id,
                                    text:data.branch.name
                                }
                            } 
                            vm.$emit('selected', this.item_branch );
                        });
                    });
                }, 500);

            },
            setDefaultBranch:function(){
                var vm = this;
                this.$refs.swal_prompt.alert(
                    'question', 
                    this.item_branch.id == 0 ? "Clear default branch?":"Make this default branch?", 
                    "Confirm" , 
                    "PUT", 
                    "/api/group/"+this.group_id+"/branch/set-default/"+this.item_branch.id, 
                    "{}"
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.default_branch = {
                                id: vm.item_branch.id,
                                text : vm.item_branch.text
                            }
                        }
                    }
                }); 



            }

        },
        mounted:function(){
            this.loadBranchDefaults();
        },
        updated:function(){

        }
    }
</script>
