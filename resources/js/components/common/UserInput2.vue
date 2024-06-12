<template>
    <div class="p-0 m-0">
        <div v-if="prepend_icon" class="input-group mt-4">
            <div class="input-group-prepend input-group-text" :title="prepend_icon_title" @click="$emit('prepend_icon_click')"> 
                <span class="p-0" style="font-size:20px;"> 
                    <i :class="'fa mx-2 '+prepend_icon"></i>
                </span>
            </div>
            <div class="input-group-text form-control text-nowrap" style="padding:0px; max-width:100%;">
                <div class="user-input-wrp" style="margin-top:-25px;"><br>
                    <input :id="input2_id" v-model="input_value" @keyup="userInputKeyUp" @change="value_changed" @focus="focusenter" @focusout="focusout" :readonly="is_readonly" :type="type ? type:'text' " class="inputText" :disabled="is_disabled" :style="'border:0px;'+input_style" required="" :title="placeholder_description">
                    <span :id="label_id" class="floating-label" v-html="placeholder" :title="placeholder_description" :style="placeholder_style"></span>
                </div>
            </div>
        </div>
        <div v-else class="user-input-wrp"><br>
            <input :id="input2_id" v-model="input_value" @keyup="userInputKeyUp" @change="value_changed" @focus="focusenter" @focusout="focusout" :readonly="is_readonly" :type="type ? type:'text' " class="inputText" :disabled="is_disabled" :style="input_style" required="" :title="placeholder_description">
            <span :id="label_id" class="floating-label" v-html="placeholder" :title="placeholder_description" :style="placeholder_style"></span>
        </div>
        <i v-if="placeholder_description"><small class="text-secondary" v-html="placeholder_description"> </small></i>
    </div>
</template>
<script>
    export default {
        props:[ "type","placeholder","prepend_icon_title" , "input_style", "placeholder_style", "readonly" , "placeholder_description", "placeholder_focus_color", "disabled", "value", "prepend_icon"],
        components: { 
        },
        watch: {
            value(newValue) {
                this.input_value = newValue;
            },
        },
        data: function () {
            return {  
                is_readonly:false,
                is_disabled:false,
                current_color:"",
                label_id: 'ph-label-'+this._uid,
                input_value: this.value,
                input2_id: 'input2-'+this._uid
            }
        },
        methods: {
            value_changed:function(evt){
                this.$emit("input", this.input_value);
                this.$emit("value_changed", evt, this.input_value);
            },
            userInputKeyUp:function(evt){
                this.$emit("input", this.input_value);
                this.$emit("keyup", this.input_value);
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
                if(el)
                    el.style.color = this.placeholder_focus_color;
                //console.log("Enter");
            },
            focusout:function(evt){
                //console.log("LEAVE");
                var el = document.getElementById(this.label_id);
                
                if(el && !this.value){
                    el.style.color = this.current_color;
                }
            }

        },
        mounted:function(){    
            this.is_readonly = this.readonly ? true: false;
            this.is_disabled = this.disabled ? true: false;

            var el = document.getElementById(this.label_id);
            if(el)
                this.current_color = el.style.color;  
            if(this.value){
                this.input_value = this.value;
            }
            

        },
        updated:function(){
            this.is_readonly = this.readonly ? true: false;
            this.is_disabled = this.disabled ? true: false;
            
        }
    }
    
</script>