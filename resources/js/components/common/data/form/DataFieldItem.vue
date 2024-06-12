<template>
    <div class="my-1"> 
        <!--
        <div class="input-group text-sm" >
            <span v-if="type == 'list'" :class="'input-group-text btn success'" @click="add_item" title="Add list item"  > 
                <small class="fa fa-plus"></small> 
            </span>
            <span v-else :class="'input-group-text btn btn-secondary disabled'"  > 
                <small class="fa fa-plus"></small> 
            </span>
            <span class="input-group-text btn btn-danger bg-red text-white" @click="$emit('remove_item')">
                <small class="fa fa-minus"></small>
            </span>
            <label :class="'input-group-text form-control '+( id == 0 ? 'text-success':'')" v-text="name"> </label>
            <small class="input-group-text" v-text="'['+type+']'"></small>
            <span v-if="order_no>1" class="input-group-text btn btn-primary" @click="$emit('move_up')">
                <small class="fa fa-arrow-up"></small>
            </span>
            <span v-if="has_down !== false" class="input-group-text btn btn-info" @click="$emit('move_down')">
                <small class="fa fa-arrow-down"></small>
            </span>
            <span  class="input-group-text btn btn-info" title="Move" >
                <small class="fa fa-arrows-alt"></small>
            </span>
        </div>
        <div style="padding-left:35px;">
            <data-field-item v-for="(opt, optIndex) in fields" v-bind:key="'field-item-'+optIndex+'-'+_uid" v-model="fields[optIndex]" :has_down="optIndex < fields.length - 1"  @move_up="move_up(opt);" @move_down="move_down(opt);" @remove_item="remove_item(opt);"></data-field-item>
        </div>
        -->
        <div class="card mb-1" v-if="type == 'list' || type == 'container'">
            <div class="card-header p-1 pl-3"> 
                <label v-text="name" class="text-primary m-0" v-if="type == 'list'"></label>
                <label v-text="name" class="text-success m-0" v-else-if="type == 'container'"></label>
                <label v-text="name" class="m-0" v-else></label>
            </div>
            <div class="card-body py-1">
                <!-- ADD FIELD -->
                <data-field-item :data_id="data_id" v-for="(opt, optIndex) in fields" v-bind:key="'data-field-item-'+opt.id+'-'+optIndex+'-'+_uid" v-model="fields[optIndex]"  ></data-field-item>
                <!-- ADD ITEM -->
                <div class="input-group text-sm mb-1" v-for="(dItem,dIndex) in data_values"  v-bind:key="'ditem-'+_uid+'-'+dItem.id+'-'+dIndex"> 
                    
                    <span :class="'input-group-text btn success'"  > 
                        <b><small v-text="dItem.type"></small> </b>
                    </span>
                    <label v-if="dItem.link_data"  class="input-group-text text-info form-control">
                        <b v-text="dItem.link_data.name"></b>
                    </label> 
                    <input v-else :id="'el-'+_uid" :type="dItem.type" :value="dItem.value1" class="form-control" :readonly="true"  />

                    
                    <span v-if="dIndex != 0" class="input-group-text btn btn-primary" @click="$emit('move_up')">
                        <small class="fa fa-arrow-up"></small>
                    </span>
                    <span v-if="dIndex != data_values.length - 1 && dIndex < data_values.length" class="input-group-text btn btn-info" @click="$emit('move_down')">
                        <small class="fa fa-arrow-down"></small>
                    </span>
                    <span v-if="dItem.link_data"  class="input-group-text btn btn-info" title="View" @click="view_data_field(dItem.link_data)" >
                        <small class="fa fa-eye"></small>
                    </span>
                    <span  class="input-group-text btn btn-danger" title="Remove" >
                        <small class="fa fa-times"></small>
                    </span>

                </div>
                <button class="btn btn-outline-primary btn-sm mt-2" v-if="type == 'list'" @click="add_item">
                    <span class="fa fa-plus"></span> Add Item
                </button>
            </div>
        </div>
        <div v-else class="input-group" > 
            <span class="input-group-text justify-content-end" style="min-width:130px;">
                <b>
                    <span v-text="name"></span>
                </b>
            </span>
            <input :id="'el-'+_uid" :type="type" v-model="input_value" class="form-control" :readonly="is_edit==false"  />

            <span v-if="is_edit==false" class="input-group-text btn btn-warning" title="Edit" @click="edit_el(); old_input_value=input_value; is_edit = true;" >
                <small class="fa fa-edit"></small>
            </span>
            <span v-if="is_edit==true"  class="input-group-text btn btn-primary" title="Save" @click="save_input()">
                <small class="fa fa-save"></small>
            </span>
            <span v-if="is_edit==true" class="input-group-text btn btn-secondary" title="Cancel" @click="input_value=old_input_value; is_edit = false;" >
                <small class="fa fa-times"></small>
            </span>
        </div>
        <swal-alert ref="swal_alert"></swal-alert>
    </div>
</template>

<script>  
    import SwalAlertVue from '../../SwalAlert.vue'; 
    export default {
        name:'DataFieldItem',
        props:[  "value", "has_down", "data_id" ],
        components: {
            "swal-alert":SwalAlertVue
        },
        watch: {
            value(newValue) {
                //console.log("Watched",newValue);
                this.id = newValue.id;
                this.name = newValue.name;
                this.type = newValue.type;
                this.fields = newValue.fields ? newValue.fields : [];
                this.order_no = newValue.order_no;
            },
        },
        data: function () {
            return {  
                id:0,
                name:'',
                type:'input',
                fields:[],
                order_no:0,
                data_values:[],
                input_value:'',
                is_edit:false,
                old_input_value:''
            }
        },
        methods: { 
            view_data_field:function(dataItem){
                //$refs.data_form.show(dataItem.id,dataItem)
                window.DataFormView.show(dataItem.id, dataItem);
            },
            edit_el:function(){
                console.log("Data ID:",this.data_id);
                document.querySelector('#el-'+this._uid).focus();
            },
            save_input:function(){
                var vm = this;
                var req = {
                    data_id: this.data_id,
                    value: this.input_value
                }
                console.log(req);
                WebRequest2('POST', '/manage/projects-monitoring/searches/data/data-value/'+this.id, JSON.stringify(req) ).then(resp=>{
                    resp.json().then(data=>{
                        console.log(data);
                        if(data.status == 1){
                            vm.old_input_value = vm.input_value;
                            vm.is_edit = false;
                        }
                    });
                });

            },
            add_item:function(){
                
                //window.ModalModelAddFieldView.show(this.field_add);
                window.ModalAddDataView.show(this.data_id, this.id, this.add_data_result);

            },
            add_data_result:function(data){
                console.log("Result:",data);
                this.data_values.push(data);
            },
            field_add:function(field){ 
                var order_no = this.fields.length + 1; 
                this.fields.push({
                    id:0,
                    name:field.name,
                    type:field.data_type,
                    fields:[],
                    data_model_id: field.data_model_id,
                    model_field_id:field.id,
                    order_no: order_no,
                    data_values:[]
                });
                this.update_input();
            },
            update_input:function(){
                //return;
                this.$emit('input', {
                    id: this.id,
                    name: this.name,
                    type: this.type,
                    fields: this.fields,
                    order_no: this.order_no,
                    model_field_id: this.value.model_field_id,
                    data_model_id: this.value.data_model_id
                } );
            },
            remove_item:function(item){
                
                this.$refs.swal_alert.confirm("Remove this field? Make sure it doesn't exist or it can greatly affect your data.", "Yes", "Cancel").then(res=>{
                    if(res.isConfirmed){
                        var order_no = 1;
                        this.fields = this.fields.filter(a=>a!= item).filter(b=>{
                            b.order_no = order_no;
                            order_no++;
                            return true;
                        }); 
                        this.update_input();
                    }

                });

            },
            move_up:function(item){
                var order_no = item.order_no - 1; 
                //Swap 1
                var swap1 =  this.fields.filter(a=>a.order_no == order_no)[0];
                if(!swap1) return; 

                //Swap 2
                var swap2 = this.fields.filter(a=>a.order_no == item.order_no)[0];
                if(!swap2) return; 

                swap1.order_no = order_no+1;
                swap2.order_no = order_no;

                this.fields = this.fields.sort((a,b)=>{
                    return a.order_no - b.order_no;
                });
 
            },
            move_down:function(item){
               
                var order_no = item.order_no + 1; 
                //Swap 1
                var swap1 =  this.fields.filter(a=>a.order_no == order_no)[0];
                if(!swap1) return; 

                //Swap 2
                var swap2 = this.fields.filter(a=>a.order_no == item.order_no)[0];
                if(!swap2) return; 

                swap1.order_no = order_no-1;
                swap2.order_no = order_no;

                this.fields = this.fields.sort((a,b)=>{
                    return a.order_no - b.order_no;
                });

            },
            addField:function(){
                //Modal SHow
                var order_no = this.fields.length + 1;
                this.fields.push({
                    id:0,
                    name:'Test - '+order_no,
                    type:'list',
                    fields:[],
                    order_no: order_no
                });
            },

        },
        mounted:function(){     
            //console.log( "loaded",this.value);
            //this.$emit("input", this.value);
            if(this.value){
                this.id = this.value.id;
                this.name = this.value.name;
                this.type = this.value.type; 
                this.fields = this.value.fields ? this.value.fields:[];
                this.order_no = this.value.order_no;

                //data values
                if(this.value.data_values && this.value.data_values.length > 0){
                    //Data Input
                    if(this.type == 'text' || this.type == 'date' || this.type == 'bool'){
                        var data_val = this.value.data_values[0];
                        if(this.type == 'text'){
                            this.input_value = data_val.value2;
                        }else if(this.type == 'date' || this.type == 'bool'){
                            this.input_value = data_val.value1;
                        } 
                    }
                    //Data Others
                    else{
                        this.data_values = this.value.data_values;
                    }
                }
                else{
                    this.data_values = this.value.data_values;
                }
            }
        },
        updated:function(){

        }
    }
</script>
