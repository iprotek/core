<template>
    <div :id="'add-data-modal-'+_uid">
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Add Data
            </template> 
            <template slot="body" >     
                <div class="mt-2">
                    <switch2 v-model="is_link" ></switch2> <label>Link Data</label>
                    <div v-if="is_link">
                        <select2 v-if="select2ready" v-model="link_item" @selected="selected_data" :allowtag="true" :url="'/manage/projects-monitoring/searches/data/list-selection'" :modal_selector="'#add-data-modal-'+_uid+' .modal-body'" :placeholder="'-- Select Data --'"></select2>
                        <small class="text-secondary"><i>Any name that would be used as linking to any model</i></small>
                        
                        <div v-if="has_model">
                            <label v-if="current_model" v-text="'Model: '+current_model"></label>
                        </div>
                        <div v-else-if="has_model == false">
                            <select2 v-model="model_item" :url="'/manage/projects-monitoring/model-fields/model/list-selection'" :modal_selector="'#add-data-modal-'+_uid+' .modal-body'" :placeholder="'-- Select Model --'"></select2>
                            <small class="text-danger"><i>Please select a model.</i></small>
                        </div>

                    </div>
                    <div v-else>
                        <user-input2 v-model="link_item.text" :input_style="'height:40px'" :placeholder="'Any Text Value'"  :placeholder_description="'Anything that describes the input.'" ></user-input2>
                    </div>
                </div>
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="save" >ADD</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script> 
    import BoostrapSwitch2Vue from '../../../../common/BoostrapSwitch2.vue'; 
    import Select2Vue from '../../../../common/Select2.vue';
    import UserInput2Vue from '../../../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: { 
            "switch2":BoostrapSwitch2Vue,
            "select2":Select2Vue,
            "user-input2":UserInput2Vue
        },
        data: function () {
            return { 
                is_link: true,
                has_model:true,
                //input_value:'',
                current_model:'',
                link_item:{
                    id:0,
                    text:''
                },
                model_item:{
                    id:0,
                    text:''
                },
                data_id:0,
                data_model_field_id:0,
                cback:null,
                select2ready:false
           }
        },
        methods:{ 
            save:function(){
                var cmodel = this.current_model;
                if(this.has_model == false){
                    cmodel = this.model_item.type;
                }
                var req = {
                    model: cmodel ? cmodel : '',
                    name: this.link_item.text,
                    is_link: this.is_link ? 1 : 0,
                    data_id: this.data_id,
                    data_model_field_id: this.data_model_field_id
                }
                console.log(req);
                var vm = this;


                this.$refs.swal_prompt.alert(
                    'question', 
                    'Add this to list?', 
                    "Confirm" , 
                    "POST", 
                    '/manage/projects-monitoring/searches/data/add-to-list', 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            //vm.id = res.value.data.id;
                            vm.cback(res.value.data)
                            vm.$refs.modal.dismiss();
                        }
                    }
                }); 

            },
            selected_data:function(val){
                console.log(val);
                var vm = this;
                this.has_model = true;
                this.current_model = '';

                //Check if has model
                WebRequest2('POST', '/manage/projects-monitoring/searches/data/name-check', JSON.stringify({ name: val.text })).then(resp=>{
                    resp.json().then(data=>{
                        console.log(data);
                        if(data.status == 1){
                            vm.has_model = true;
                            vm.current_model = data.data.data_model_type;
                        }else{
                            vm.has_model = false;
                        }
                    })
                });

            },
            reset:function(){
                this.is_link = true;
                this.has_model = true;
                this.link_item = {
                    id:0,
                    text:''
                };
                this.model_item = {
                    id:0,
                    text:''
                };
            },
            show:function(data_id=0, data_model_field_id = 0, cback=null){ 
            
                var vm = this;
                vm.reset();
                vm.data_id = data_id;
                vm.data_model_field_id = data_model_field_id;
                vm.cback = cback;
                vm.$refs.modal.show();
                vm.select2ready = false;
                setTimeout(()=>{
                    vm.select2ready = true;
                }, 100);
            },

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
