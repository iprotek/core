<template>
    <div class="col-md-4" :style="view_scale?('transform:scale('+view_scale+')'):''" > 
        <div :class="'card '+(id == 0 ? 'card-primary':'card-success')">
            <div class="card-header p-1 text-center" v-if="id == 0">
               NEW DATA <button class="btn btn-sm btn-outline-danger float-right" @click="$emit('cancel')"><span class="fa fa-times"></span></button>
            </div>
            <div class="card-header p-1 text-center" v-else>
                UPDATE DATA <button class="btn btn-sm btn-outline-danger  float-right" @click="$emit('cancel')"><span class="fa fa-times"></span></button>
            </div>
            <div class="card-body py-1">
                <div>
                    <div class="mb-1">
                        <small>Field Title:</small>
                        <input class="form-control" v-model="placeholder_name" placeholder="Required: Field Title" />
                    </div>
                    <div class="mb-1" v-if="placeholder_name">
                        <small>SOURCE TYPE:</small>
                        <select v-model="source_type" class="form-control" @change="source_type_changed">
                            <option :value="''">-- SELECT SOURCE TYPE --</option>
                            <option :value="'model'">MODEL</option>
                            <option :value="'instance'">INSTANCE</option>
                            <option :value="'input'">INPUT</option>
                        </select>
                    </div>
                    <div v-if="source_type == 'model'">
                        <div>
                            <small>Model:</small>
                            <select v-model="model_selected" class="form-control mb-1" @change="model_select_changed">
                                <option :value="''">-- SELECT MODEL --</option> 
                                <option v-for="(modelItem, modelIndex) in modelList" v-bind:key="'model-'+_uid+'-'+modelIndex" :value="modelItem" v-text="modelItem"></option>
                            </select>
                        </div>
                        <div v-if="model_selected">
                            <small>Field:</small>
                            <select v-model="field_selected" class="form-control mb-1" >
                                <option :value="''"> -- SELECT FIELD -- </option>
                                <option v-for="(fieldItem, fieldindex) in fieldList" v-bind:key="'field-item'+fieldindex+'-'+_uid" :value="fieldItem" v-text="fieldItem"></option>
                            </select>
                        </div>
                        <div v-if="field_selected">
                            <small>Instance Source:</small>
                            <select v-model="source_instance" class="form-control mb-1">
                                <option :value="'self'">  DEFAULT: SELF </option>
                                <option :value="'instance'">  INSTANCE </option>
                                <option :value="'local'"> LOCAL </option>
                                <option :value="'global'"> GLOBAL </option>
                            </select>
                        </div>
                        <div v-if="source_instance != 'self' && field_selected">
                            <small>Instance Name in (PHP lan):</small>
                            <input v-model="instance_name" class="form-control mb-1" placeholder="INSTANCE NAME"/>
                        </div>
                        <div v-if="(field_selected && source_instance == 'self') || (instance_name && isValidVariableName(instance_name))">
                            <button class="btn btn-sm btn-primary" v-if=" id == 0">ADD</button>
                            <button class="btn btn-sm btn-success" v-else>SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                    <div v-else-if="source_type == 'instance'">
                        <div >
                            <small>Instance Name in (PHP lan):</small>
                            <input v-model="instance_name" class="form-control mb-1" />
                        </div>
                        <div v-if=" instance_name ">
                            <small>Instance Source:</small>
                            <select  v-model="source_instance" placeholder="INSTANCE NAME" class="form-control mb-1">
                                <option :value="'instance'"> INSTANCE </option>
                                <option :value="'local'">  TARGET LOCAL </option>
                                <option :value="'global'"> TARGET GLOBAL </option>
                            </select>
                        </div>
                        <div v-if="instance_name && source_instance != 'self'">
                            <button class="btn btn-sm btn-primary" v-if="id == 0">ADD</button>
                            <button class="btn btn-sm btn-success" v-else>SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                    <div v-else-if="source_type == 'input'">
                        
                        <div class="mb-1" >
                            <small>INPUT TYPE:</small>
                            <select v-model="input_type"  class="form-control mb-1" @change="input_type_changed">
                                <option :value="''">  -- INPUT TYPE -- </option>
                                <option :value="'text'">  TEXT </option> 
                                <option :value="'date'">  DATE </option> 
                                <option :value="'checkbox'">  CHECKBOX </option> 
                            </select>
                        </div> 
                        <div class="mb-2" v-if="input_type" > 
                            <small>DEFAULT VALUE:</small> 
                            <input v-if="input_type == 'checkbox'" v-model="input_default_value" :type="input_type" :checked="input_default_value" />
                            <input class="form-control" v-else v-model="input_default_value" :type="input_type" />
                        </div>
                        <div class="mb-2" v-if="input_type">
                            <switch2 v-model="is_required"></switch2> <label class="mb-0">Is Required?</label>
                        </div>
                        <div v-if="input_type">
                            <button v-if="id == 0" class="btn btn-sm btn-primary"  >ADD</button>
                            <button v-else class="btn btn-sm btn-success" >SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import UserInput2Vue from '../../UserInput2.vue'; 
    import BoostrapSwitch2Vue from '../../BoostrapSwitch2.vue';
    export default {
        props:[ "value","view_scale", "source_id" , "source_name" ],
        watch: {
            value(newValue) {
                this.id = newValue;
            },
        },
        components: { 
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue
        },
        data: function () {
            return {
                id:0,
                placeholder_name:'',
                source_type:'',
                modelList:[],
                fieldList:[],
                model_selected:'',
                field_selected:'',
                instance_name:'',
                source_instance:'self',
                input_default_value:'',
                is_required:false,
                input_type:''
            }
        },
        methods: { 
            source_type_changed:function(){
                var vm = this;
                vm.model_selected = '';
                vm.instance_name = '';
                vm.field_selected = '';
                if(this.source_type == 'model'){
                    WebRequest2('GET', '/manage/iprotek-data/models').then(resp=>{
                        resp.json().then(data=>{
                            vm.modelList = data;
                        })
                    });
                }
            },
            input_type_changed:function(){
                var vm = this;
                if(vm.input_type){
                    if(vm.input_type == 'checkbox'){
                        if(vm.input_default_value !== false && vm.input_default_value !== true){
                            vm.input_default_value = false;
                        }
                    }else{
                        vm.input_default_value = '';
                    }
                }
            },
            model_select_changed:function(){
                var vm = this;
                vm.fieldList = [];
                vm.field_selected = '';
                vm.source_instance = 'instance';
                if(this.model_selected){
                    WebRequest2('GET', '/manage/iprotek-data/model-fields?model_name='+this.model_selected).then(resp=>{
                        resp.json().then(data=>{
                            vm.fieldList = data;
                        })
                    })
                }
            },
             isValidVariableName:function(name) {
                try {
                    // Attempt to create a function with the name as a parameter
                    new Function(name, 'var ' + name);
                    return true;
                } catch (e) {
                    return false;
                }
            }

        },
        mounted:function(){     
            //value is the source
        },
        updated:function(){

        }
    }
</script>
