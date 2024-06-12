<template>    
    <div class="user-input-wrp"><br>
        <input v-model="value" @change="value_changed" @focus="focusenter" @focusout="focusout" :readonly="is_readonly" :type="type ? type:'text' " class="inputText" :disabled="is_disabled" :style="input_style" required="" :title="placeholder_description">
        <span :id="label_id" class="floating-label" v-html="placeholder" :title="placeholder_description" :style="placeholder_style"></span>
    </div>
</template>
<script>
    export default {
        props:[ "type","placeholder", "input_style", "placeholder_style", "readonly" , "placeholder_description", "placeholder_focus_color", "disabled" ],
        components: { 
        },
        data: function () {
            return {  
                is_readonly:false,
                is_disabled:false,
                current_color:"",
                label_id: 'ph-label-'+this._uid,
                value:""
            }
        },
        methods: {
            value_changed:function(){
                this.$emit("input", this.value);
            },
            setReadOnly:function(val){
                this.is_readonly = val ? true: false;
            },
            setDisabled:function(val){
                this.is_disabled = val ? true: false;
            },
            show: function () {
               // this.$refs.modal.show();
            },  
            focusenter:function(evt){
                var el = document.getElementById(this.label_id);
                el.style.color = this.placeholder_focus_color;
                //console.log("Enter");
            },
            focusout:function(evt){
                //console.log("LEAVE");
                var el = document.getElementById(this.label_id);
                if(!this.value){
                    el.style.color = this.current_color;
                }
            }

        },
        mounted:function(){    
            this.is_readonly = this.readonly ? true: false;
            this.is_disabled = this.disabled ? true: false;

            var el = document.getElementById(this.label_id);
            this.current_color = el.style.color;  

        }
    }
    
</script>