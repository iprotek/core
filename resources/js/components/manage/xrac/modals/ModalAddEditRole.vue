<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                <label v-if="role_info.id">
                    EDIT ROLE
                </label>
                <label v-else>
                    ADD ROLE
                </label>
            </template> 
            <template slot="body" >     
                <user-input2 v-model="role_info.name" :placeholder="'Role Name'"  :input_style="'height:40px;'"/>
                <user-input2 v-model="role_info.description" :placeholder="'Description info about the role'"  :input_style="'height:40px;'"/>
                <div class="mt-3">
                    <switch2 v-model="role_info.is_active" :off_color="'red'" /> Is Active 
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                    <button v-if="!role_info.id" type="button" class="btn btn-outline-primary">
                       <span class="fa fa-plus"></span> ADD & SYNC
                    </button> 
                    <button v-else type="button" class="btn btn-outline-success">
                       <span class="fa fa-save"></span> SAVE & SYNC
                    </button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>
    import BoostrapSwitch2Vue from '../../../common/BoostrapSwitch2.vue';
    import UserInput2Vue from '../../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {
            "switch2":BoostrapSwitch2Vue,
            "user-input2":UserInput2Vue
        },
        watch: { 
        },
        data: function () {
            return {        
                promiseExec:null,
                role_info:{
                    id:0,
                    name:'',
                    description:'',
                    is_active:true
                }
           }
        },
        methods:{ 
            reset:function(){
                this.role_info.id = 0;
                this.role_info.name = '';
                this.role_info.description = '';
                this.role_info.is_active = true;
            },
            show:function(id=0){ 
                var vm = this;
                vm.reset();

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
