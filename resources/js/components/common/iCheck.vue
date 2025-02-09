<template>
    <div class="form-group clearfix mb-0">
        <div class="icheck-primary d-inline">
            <input :type="default_type" :id="radioId" :name="name" :checked="is_checked" :value="data_value" @click="selected">
            <label :for="radioId" v-text="default_label"> </label>
        </div>
    </div>
</template>
<script setup>
    defineEmits(['update:data_value'])
</script>
<script>
    export default {
        props:[ "type", "label", "name", "checked", "data_value", "value"],
        components: {    
        },
        emits: ["update:checked", "selected"], //For hint purpose
        watch:{
            checked:function(val){
                this.is_checked = val === true;
                document.querySelector('#'+this.radioId).checked = this.is_checked;
                //console.log( "icheck_trigger", val, this.is_checked);
            }
        },
        data: function () {
            return {
                radioId:'icheck-'+this._uid,
                default_type:"checkbox",
                default_label:"",
                is_checked:false
            }
        },
        methods: { 
            selected:function(){
                var vm = this;
                //For v-model
                this.$emit("input", this.data_value);
                //For emits
                var is_checked = document.querySelector('#'+this.radioId).checked;
                this.$emit('update:checked', is_checked, vm.data_value);
                setTimeout(()=>{
                    vm.$emit('selected', vm.data_value, is_checked );
                }, 50);
            }
        },
        mounted:function(){  
            if(this.type)
                this.default_type = this.type;
            if(this.label)
                this.default_label = this.label; 
            if(this.checked === true)
                this.is_checked = true;   
        }
    }

</script>
