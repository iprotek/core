<template>
    <div v-if="is_selection">
        <select2  v-model="selectItem" @selected="itemSelected" :has_clear="true" :default_theme="select_theme" :placeholder="' '" :select_data="colordata"  :select_template="colorTemplate" ></select2>
    </div>
    <div v-else>
        <ul class="fc-color-picker" :id="'color-chooser-'+_uid">
            <li><a class="text-primary" href="#" @click="colorSelected('primary')"><i class="fas fa-square" :style="(color == 'primary' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
            <li><a class="text-warning" href="#" @click="colorSelected('warning')"><i class="fas fa-square" :style="(color == 'warning' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
            <li><a class="text-success" href="#" @click="colorSelected('success')"><i class="fas fa-square" :style="(color == 'success' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
            <li><a class="text-danger" href="#" @click="colorSelected('danger')"><i class="fas fa-square" :style="(color == 'danger' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
            <li><a class="text-muted" href="#" @click="colorSelected('muted')"><i class="fas fa-square" :style="(color == 'muted' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
            <li><a class="text-info" href="#" @click="colorSelected('info')"><i class="fas fa-square" :style="(color == 'info' ? 'border: 2px solid black; border-radius:5px; padding:1px;' :'')"></i></a></li>
        </ul>    
    </div>
</template>
<script>
    import Select2Vue from './Select2.vue';
    export default {
        props:[ "value", "is_selection" ,"select_theme"],
        components: { 
            "select2":Select2Vue
        },
        watch: {
            value(newValue) {
                this.color = newValue;
                if(this.is_selection){
                    var colorSel = null;
                    if(!this.color){
                        colorSel = this.colordata[0];
                    }
                    else{
                        colorSel = this.colordata.filter(a=>a.id == this.color)[0];
                    }
                    if(colorSel)
                        this.selectItem = colorSel;
                }
            },
        },
        data: function () {
            return {  
                color:'primary',
                selectItem:{
                    id:'',
                    text: ' '
                },
                colordata:[
                    {
                        id:'',
                        text: ' '
                    },
                    {
                        id:'primary',
                        text:' '
                    },{
                        id:'warning',
                        text:' '
                    },{
                        id:'success',
                        text:' '
                    },{
                        id:'danger',
                        text:' '
                    },{
                        id:'muted',
                        text:' '
                    },{
                        id:'info',
                        text:' '
                    }
                ]

            }
        },
        methods: {
            itemSelected:function(val){
                this.color = val.id;
                this.$emit("input", this.color);
                this.$emit('color_selected', val);
                //console.log(val);
            },
            colorTemplate:function(state){
                 if (!state.id) { return state.text; }
                    var $state = $(
                    '<label class="m-0 p-0"><span class="fa fa-square text-'+state.id+'" /> ' +   state.text +     '</label>'
                );
                return $state;
            },


            value_changed:function(){
                this.$emit("input", this.color);
            }, 
            colorSelected:function(color){
                this.color = color;
                this.$emit("input", this.color);
            }

        },
        mounted:function(){     
            if(this.value){
                this.color = this.value;
            }
            if(this.is_selection){
                var colorSel = null;

                if(!this.value){
                    colorSel = this.colordata[0];
                }
                else{
                    colorSel = this.colordata.filter(a=>a.id == this.color)[0];
                }
                if(colorSel)
                    this.selectItem = colorSel;
                //console.log(colorSel);
            }

        }
    }

</script>
