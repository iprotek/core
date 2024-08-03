<template>
    <div>
        <div class="card" v-if="source_id">
            <div class="card-header" v-text="title">    </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-2" v-if="is_add_edit == false">
                    <div v-for="(delItem,delIndex) in delegateDataList" v-bind:key="'delegate-'+_uid+'-'+delItem.id+'-'+delIndex">
                        <div v-if="delItem.source_type == 'model'" class="input-group text-sm input-group-sm mb-1">
                            <span class="btn btn-sm btn-default" title="Show" >
                                <small :class="'fa '+(delItem.is_show ? 'fa-eye text-success':'fa-eye-slash text-danger')" title="Show"></small>
                            </span>
                            <span @click="edit_data(delItem)" class="input-group-text btn btn-outline-warning" title="Edit">
                                <small class="fa fa-edit"></small>
                            </span> 
                            <span class="input-group-text btn btn-danger bg-red text-white">
                                <small class="fa fa-minus"></small>
                            </span> 
                            <label class="input-group-text form-control ">
                                <label class="m-0" v-text="delItem.placeholder"></label> ~ [
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'self'">
                                    self on 
                                    <label class="m-0" v-text="delItem.field_info.model_selected"></label>::
                                    <label class="m-0" v-text="delItem.field_info.field_selected"></label>
                                </label>
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'instance'">
                                    $<label class="m-0" v-text="delItem.field_info.instance_name"></label>->
                                    <label class="m-0" v-text="delItem.field_info.field_selected"></label>
                                </label>
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'global'">
                                    glob $<label class="m-0" v-text="delItem.field_info.instance_name"></label>->
                                    <label class="m-0" v-text="delItem.field_info.field_selected"></label>
                                </label>
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'local'">
                                      $<label class="m-0" v-text="delItem.field_info.instance_name"></label>->
                                    <label class="m-0" v-text="delItem.field_info.field_selected"></label>
                                </label>
                                ]
                            </label> 
                            <small class="input-group-text">[REF:MODEL]</small> <!----> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-down"></small>
                            </span> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-up"></small>
                            </span> 
                            <span title="Move" class="input-group-text btn btn-info">
                                <small class="fa fa-arrows-alt"></small>
                            </span>
                        </div>
                        <div v-else-if="delItem.source_type == 'input'" class="input-group text-sm input-group-sm mb-1">
                            <span class="btn btn-sm btn-default" title="Show" >
                                <small :class="'fa '+(delItem.is_show ? 'fa-eye text-success':'fa-eye-slash text-danger')" title="Show"></small>
                            </span> 
                            <span v-if="delItem.field_info" class="btn btn-sm btn-default" title="Required" >
                                <small :class="'fa '+(delItem.field_info.is_required == 1 ? 'fa-check text-primary':'fa-question text-warning')"></small>
                            </span> 
                            <span @click="edit_data(delItem)" class="input-group-text btn btn-outline-warning" title="Edit">
                                <small class="fa fa-edit"></small>
                            </span> 
                            <span class="input-group-text btn btn-danger bg-red text-white">
                                <small class="fa fa-minus"></small>
                            </span>  
                            <label class="input-group-text form-control ">
                                <label class="m-0" v-text="delItem.placeholder"></label> - DEFAULT: <label v-if="delItem.field_info" class="m-0" v-text="delItem.field_info.input_default_value"></label>
                            </label> 
                            <small v-if="delItem.field_info" class="input-group-text" v-text="'[INPUT:'+delItem.field_info.input_type.toUpperCase()+']'"> </small> <!----> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-down"></small>
                            </span> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-up"></small>
                            </span> 
                            <span title="Move" class="input-group-text btn btn-info">
                                <small class="fa fa-arrows-alt"></small>
                            </span>
                        </div>
                        <div v-else-if="delItem.source_type == 'instance'" class="input-group text-sm input-group-sm mb-1">
                            <span class="btn btn-sm btn-default" title="Show" >
                                <small :class="'fa '+(delItem.is_show ? 'fa-eye text-success':'fa-eye-slash text-danger')" title="Show"></small>
                            </span> 
                            <span @click="edit_data(delItem)" class="input-group-text btn btn-outline-warning" title="Edit">
                                <small class="fa fa-edit"></small>
                            </span> 
                            <span class="input-group-text btn btn-danger bg-red text-white">
                                <small class="fa fa-minus"></small>
                            </span> 
                            <label class="input-group-text form-control ">
                                <label class="m-0" v-text="delItem.placeholder"></label> ~ 
                                 [
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'instance'">
                                    <label class="m-0" v-text="delItem.field_info.instance_name"></label>
                                </label>
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'global'">
                                    glob <label class="m-0" v-text="delItem.field_info.instance_name"></label>
                                </label>
                                <label class="m-0" v-if="delItem.field_info && delItem.field_info.source_instance == 'local'">
                                    <label class="m-0" v-text="delItem.field_info.instance_name"></label>
                                </label>
                                ]
                            </label> 
                            <small class="input-group-text">[REF:VAR]</small> <!----> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-down"></small>
                            </span> 
                            <span class="input-group-text btn btn-info">
                                <small class="fa fa-arrow-up"></small>
                            </span> 
                            <span title="Move" class="input-group-text btn btn-info">
                                <small class="fa fa-arrows-alt"></small>
                            </span>
                        </div>
                    </div>
                    <div v-if="delegateDataList.length == 0">
                        <code> ** No data delegate  ** </code>
                    </div>
                </ul>
                <ul class="list-group list-group-flush" v-if="is_add_edit == false"> 
                    <button class="btn btn-sm btn-outline-primary" @click="is_add_edit=true">
                        <span class="fa fa-plus"></span> ADD FIELD
                    </button>
                </ul>
                <ul class="list-group list-group-flush" v-else>
                    <add-delegate-data ref="edit_add_data" @modal_updated="loadDataDelegates()" @cancel="cancel_add_edit" v-model="new_data" :source_id="source_id" :source_name="source_name" :view_scale="'80%'" ></add-delegate-data>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import AddEditSourceDataVue from './AddEditSourceData.vue';
    export default {
        props:[ "value", "source_name", "title" ],
        watch: {
            value(newValue) {
                this.source_id = newValue;
            },
        },
        components: { 
            "add-delegate-data":AddEditSourceDataVue
        },
        data: function () {
            return {
                is_add_edit:false,
                new_data:0,
                source_id:0,
                delegateDataList:[]
            }
        },
        methods: { 
            edit_data:function(data){
                this.is_add_edit = true;
                var  vm = this;
                setTimeout(()=>{
                    vm.$refs.edit_add_data.edit_data(data);
                }, 100);
            },
            cancel_add_edit:function(){
                this.is_add_edit = false;
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadDataDelegates:function(){
                var vm = this;
                vm.delegateDataList = [];
                WebRequest2('GET', '/manage/iprotek-data/get-delegates?'+this.queryString({source_id: this.source_id, source_name: this.source_name})).then(resp=>{
                    if(resp.ok){
                        resp.json().then(data=>{
                            //console.log("RES", data);
                            data.forEach(d=>{
                                d.field_info = JSON.parse( d.field_info );
                            })
                            vm.delegateDataList = data;
                        });
                    }
                })
            }
        },
        mounted:function(){     
            this.source_id = this.value;
            this.loadDataDelegates();
        },
        updated:function(){

        }
    }
</script>
