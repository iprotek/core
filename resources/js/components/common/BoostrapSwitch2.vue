<template>  
    <div style="display:inline-block;">
        <input :id="switchID" v-model="input_check" type="checkbox" @change="value_changed" data-bootstrap-switch :data-off-color="off_color" :data-on-color="on_color">
    </div>
</template>
<script> 
    export default {
        props:["off_color","on_color","value"],
        components: {    
        },
        data: function () {
            return {
                switchID:'switch-id-'+this._uid,
                input_check:false
            }
        },
        watch: {
            value(newValue) {
                this.input_value = newValue ? true:false;
                this.input_check = this.input_value;
                $('#'+this.switchID).bootstrapSwitch('state', this.input_value);          
            },
        },
        methods: { 
            value_changed:function(){
                this.$emit("input", this.input_check);
            },
            setOn:function(check){
                this.input_value = check ? true:false;
                $('#'+this.switchID).bootstrapSwitch('state', this.input_value);                
            },
            setState:function(check){
                this.input_value = check ? true:false;
                $('#'+this.switchID).bootstrapSwitch('state', this.input_value);
            },
            getState:function(){
                return $('#'+this.switchID).bootstrapSwitch('state');
            }

        },
        mounted:function(){     
            var vm = this;  
            var exists = document.querySelector('#'+vm.switchID);
            if(exists){ 
                $(function(){
                    setTimeout(()=>{
                        if(vm.value){
                            vm.input_check = true;
                        }
                        else{
                            vm.input_check = false;
                        }
                        $('#'+vm.switchID).bootstrapSwitch('state', vm.input_check);
                        $('#'+vm.switchID).on('switchChange.bootstrapSwitch',  function (e, data) {
                            vm.input_check = data;
                            vm.value_changed();
                        });
                    }, 300);
                });
            } 
        }
    }

</script>
