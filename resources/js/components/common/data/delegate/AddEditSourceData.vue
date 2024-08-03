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
                    <div class="mb-1">
                        <switch2 v-model="is_show"></switch2>
                        <label class="mb-0">Show</label> 
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
                    <div v-if="placeholder_name && source_type == 'model'">
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
                            <button class="btn btn-sm btn-primary" v-if=" id == 0" @click="save()" >ADD</button>
                            <button class="btn btn-sm btn-success" v-else @click="save()" >SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                    <div v-else-if="placeholder_name && source_type == 'instance'">
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
                            <button class="btn btn-sm btn-primary" v-if="id == 0"  @click="save()" >ADD</button>
                            <button class="btn btn-sm btn-success" v-else  @click="save()" >SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                    <div v-else-if="placeholder_name && source_type == 'input'">
                        
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
                            <button v-if="id == 0" class="btn btn-sm btn-primary" @click="save()"  >ADD</button>
                            <button v-else class="btn btn-sm btn-success"  @click="save()" >SAVE</button>
                            <button class="btn btn-sm btn-default" @click="$emit('cancel')">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>
<script>
    import UserInput2Vue from '../../UserInput2.vue'; 
    import BoostrapSwitch2Vue from '../../BoostrapSwitch2.vue';
    import SwalAlertVue from '../../Swal.vue';
    export default {
        props:[ "value","view_scale", "source_id" , "source_name" ],
        watch: {
            value(newValue) {
                this.id = newValue;
            },
        },
        components: { 
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue,
            "swal":SwalAlertVue
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
                input_type:'',
                is_show:true
            }
        },
        methods: { 
            edit_data:function(data){ 
                var  vm = this;
                this.id = data.id;
                this.placeholder_name = data.placeholder;
                this.source_type = data.source_type;
                this.is_show = data.is_show == 1;
                this.source_id = data.source_id
                this.source_name = data.source_name;
                var field_info = data.field_info;
                if(data.source_type == 'model' && data.field_info){  
                    this.model_selected = field_info.model_selected;
                    this.field_selected = field_info.field_selected;
                    this.source_instance = field_info.source_instance;
                    this.instance_name = field_info.instance_name;
                    var ss = this.source_type_changed(false);
                    if(ss){
                        ss.then(data=>{
                            vm.model_select_changed(false);
                        })
                    }
                }else if(data.source_type == 'input' && data.field_info){
                    this.input_type = field_info.input_type;
                    this.is_required = field_info.is_required;
                    this.input_default_value = field_info.input_default_value; 
                }
                else if(data.source_type == 'instance' && data.field_info){
                    this.instance_name = field_info.instance_name;
                    this.source_instance = field_info.source_instance;
                }
            },
            source_type_changed:function(is_reset=true){
                var vm = this;
                if(is_reset){
                    vm.model_selected = '';
                    vm.instance_name = '';
                    vm.field_selected = '';
                    if(this.source_type == 'instance' || this.source_type == 'local' || this.source_type == 'global'){
                        this.source_instance = 'instance';
                    }
                }
                if(this.source_type == 'model'){
                  return WebRequest2('GET', '/manage/iprotek-data/models').then(resp=>{
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
            model_select_changed:function(is_reset=true){
                var vm = this;
                vm.fieldList = [];
                if(is_reset){
                    vm.field_selected = '';
                    vm.source_instance = 'self';
                }
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
            },
            field_info:function(){
                if(this.source_type == 'model'){
                    return {
                        model_selected: this.model_selected,
                        field_selected: this.field_selected,
                        source_instance: this.source_instance,
                        instance_name: this.instance_name
                    };
                }
                if(this.source_type == 'instance'){
                    return {
                        instance_name: this.instance_name,
                        source_instance: this.source_instance
                    }
                }
                if(this.source_type == 'input'){
                    
                    return {
                        input_type: this.input_type,
                        is_required: this.is_required ? 1 : 0,
                        input_default_value: this.input_default_value
                    }
                }
            },
            save:function(){
                var vm = this;
                var req = {
                    id: this.id,
                    source_id: this.source_id,
                    source_name: this.source_name,
                    placeholder: this.placeholder_name,
                    source_type: this.source_type,
                    is_show: this.is_show ? 1 : 0,
                    field_info: this.field_info()
                };
                console.log(req);
                this.$refs.swal_prompt.alert(
                    'question', 
                    this.id == 0 ? "Add Data?":"Update Data?", 
                    "Confirm" , 
                    this.id == 0 ? "POST":"PUT", 
                    this.id == 0 ?"/manage/iprotek-data/add-delegate":"/manage/iprotek-data/update-delegate/"+this.id, 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            vm.id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            }

        },
        mounted:function(){     
            //value is the source
        },
        updated:function(){

        }
    }
</script>
