<template>
    <div class="my-1"> 
        <div class="input-group text-sm" >
            <span v-if="type == 'list' || type == 'container'" :class="'input-group-text btn success'" @click="add_item" title="Add list item"  > 
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
            <field-item v-for="(opt, optIndex) in fields" v-bind:key="'field-item-'+optIndex+'-'+_uid" v-model="fields[optIndex]" :has_down="optIndex < fields.length - 1"  @move_up="move_up(opt);" @move_down="move_down(opt);" @remove_item="remove_item(opt);"></field-item>
        </div>
        <swal-alert ref="swal_alert"></swal-alert>
    </div>
</template>

<script>  
    import SwalAlertVue from '../../../common/SwalAlert.vue';
    export default {
        name:'FieldItem',
        props:[  "value", "has_down" ],
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
                order_no:0
            }
        },
        methods: { 
            add_item:function(){
                
                window.ModalModelAddFieldView.show(this.field_add);
                

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
                    order_no: order_no
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
            }
        },
        updated:function(){

        }
    }
</script>
