<template> 
    <div >
        <div v-if="hasLoaded" :class="'input-group '+input_size">
            <select v-if="branchList.length == 0 && disable_multi_branch" class="form-control" v-model="selected_branch_id" style="min-width: 100px;" >
                <option :value="1">#1 DEFAULT COMPANY/BRANCH </option>
            </select>
            <select v-else class="form-control" style="min-width: 100px;" @change="selection_changed" v-model="selected_branch_id">
                <option v-for="(item,itemIndex) in branchList" v-bind:key="'branch-'+item.id+'-'+itemIndex" :value="item.id" v-text="item.name" :selected="selected_branch_id == item.id">
                </option>
            </select>
            <span title="ID# of Branch" class="input-group-text text-primary" >
                <b v-text="'#'+selected_branch_id"></b>
            </span>
            <span v-if="can_manage_branch" class="btn btn-default" @click="modalBranchList()">
                <small title="Search" class="fa fa-list text-primary"></small>
            </span> 
        </div> 
    </div>
</template>

<script>
    export default {
        props:[ "input_size", "is_system_select" ],
        components: { 
        },
        watch: { 
        },
        data: function () {
            return {    
                branchList:[],
                selected_branch_id:1,
                isLoading:false,
                hasLoaded:false,
                disable_multi_branch:false,
                can_manage_branch:false
            }
        },
        methods: { 
            selection_changed:function(){
                //this.selected_branch_id
                this.$emit('selection_changed', this.selected_branch_id);
                if(this.is_system_select){
                    WebRequest2('POST', '/manage/xrac/branch/select-branch', JSON.stringify({branch_id: this.selected_branch_id})).then(resp=>{
                        resp.json().then(data=>{
                            if(data.status == 1){
                                setTimeout(()=>{
                                    window.location.reload();
                                }, 500);
                            }
                        });
                    });
                }
            },
            modalBranchList:function(){
                return window.ModalBranchView.show();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadActiveBranches:function(){
                var vm = this;
                vm.isLoading = true;
                WebRequest2('GET', '/manage/xrac/branch/active-list').then(resp=>{
                    vm.isLoading = false;
                    resp.json().then(data=>{
                        //console.log("Branch Data", data);
                        vm.can_manage_branch = data.can_manage_branch;
                        vm.branchList = data.list;
                        vm.selected_branch_id = data.selected_id; 
                        vm.hasLoaded = true;
                        vm.disable_multi_branch = data.disable_multi_branch;
                        if(vm.selected_branch_id)
                            vm.$emit('selection_changed', vm.selected_branch_id);

                    })
                });
            }
        },
        mounted:function(){   
            this.loadActiveBranches();
        },
        updated:function(){

        }
    }
</script>
