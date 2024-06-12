<template>
    <div class="form-group clearfix">
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
                //For v-model
                this.$emit("input", this.data_value);
                //For emits
                this.$emit('selected', this.data_value );
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
