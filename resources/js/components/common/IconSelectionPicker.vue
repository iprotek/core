<template>
    <div >
        <select2  
            v-model="selectItem"
             @selected="itemSelected"
             :default_theme="select_theme" 
             :placeholder="placeholder" 
             :select_data="iconList"  
             :select_template="colorTemplate"
            >
        </select2>
    </div> 
</template>
<script>
    import Select2Vue from './Select2.vue';
    export default {
        props:[ "value",  "select_theme",  "set_icon_list", "placeholder"],
        components: { 
            "select2":Select2Vue
        },
        watch: {
            value(newValue) {
                //console.log()
                //if(this.color != newValue){
                    this.color = newValue;
                    var colorSel = null;
                    var vm = this; 
                    colorSel = vm.iconList.filter(a=>a.id == -1)[0]; 
                    if(colorSel){
                        setTimeout(()=>{
                            vm.selectItem = colorSel; 
                            console.log("Selected",colorSel);
                        }, 500);
                    } 
            },
        },
        data: function () {
            return {  
                color:'primary',
                selectItem:{
                    id:-1,
                    text: ' ',
                    icon:''
                },
                iconList:[]

            }
        },
        methods: {
            itemSelected:function(val){
                var vm = this; 

                vm.color = val.id; 
                
                //var colorSel = vm.iconList.filter(a=>a.id == val.id)[0];
                //vm.selectItem = colorSel;
                vm.$emit("input", vm.color);
                vm.$emit('icon_selected', val); 
 
            },
            colorTemplate:function(state){ 
                var $state = $(
                ' <span class="fa fa-'+state.icon+'" > ' +   state.text +'</span>'  
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
            if(this.set_icon_list){
                this.iconList = this.set_icon_list;
            }


            if(this.value){
                this.color = this.value;
            } 
            var colorSel = null;

            if(!this.set_icon_list) return;

            if(!this.value){
                colorSel = this.iconList[0];
            }
            else{
                colorSel = this.iconList.filter(a=>a.id == this.color)[0];
            }
            if(colorSel)
                this.selectItem = colorSel; 

        }
    }

</script>
