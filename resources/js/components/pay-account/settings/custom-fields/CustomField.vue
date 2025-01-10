<template>
    <div class="custom-field w-100"> 
           <b> <label v-text="el_object.name" class="text-primary"></label></b>
        <div v-if="el_object.dataType == 'RADIO'">
            <div>
                <div  v-for="(item, itemIndex) in el_object.picklistOptions" v-bind:key="'custom-field-'+itemIndex+'-'+_uid">
                    <input :disabled="readonly === true" :readonly="readonly === true" @change="radioChanged" v-model="item_value" :name="'radio-'+_uid" :checked="item_value == item" :value="item" type="radio" /> <label v-text="item"></label>
                </div>
            </div>
        </div>
        <div v-else-if="el_object.dataType == 'SINGLE_OPTIONS' ">
            <div>
                <select v-model="item_value" @change="selectionChanged" :disabled="readonly === true">
                    <option  v-for="(val, valIndex) in el_object.picklistOptions" :value="val" v-bind:key="'sel-'+valIndex+'-'+_uid" v-text="val"></option>
                </select>
            </div>
        </div>
        <div v-else-if="el_object.dataType == 'TEXT' || el_object.dataType == 'PHONE'"> 
            <div>
                <input :id="inputEl" :readonly="readonly === true" :disabled="readonly === true" v-model="item_value" class="form-control" type="text" @change="textChanged" /> 
            </div>
        </div>
        <div v-else-if="el_object.dataType == 'CHECKBOX' || el_object.dataType=='MULTIPLE_OPTIONS' ">
            <div>
                <div  v-for="(item, itemIndex) in el_object.picklistOptions" v-bind:key="'custom-field-'+itemIndex+'-'+_uid">
                    <input :readonly="readonly === true" :disabled="readonly === true" :value="item" :name="checkboxName" :checked="!item_value ? false: item_value.indexOf(item)>=0 " type="checkbox" @change="checkBoxChanged" /> <label v-text="item"></label>
                </div>
                <small class="text-secondary">Please select at least 1.</small>
            </div>
        </div>
        <div v-else-if="el_object.dataType == 'LARGE_TEXT'"> 
            <div>
                <textarea :id="inputEl" :readonly="readonly === true" :disabled="readonly === true" v-model="item_value" class="form-control" @change="textChanged"></textarea> 
            </div>
        </div>
        <div v-else>
            <code>Unsupported Field.<span v-text="el_object.dataType"></span></code>
        </div>
        <div v-if="is_showbutton === true && (el_object.dataType == 'TEXT' || el_object.dataType == 'LARGE_TEXT')">
            <label>
                <span class="input-group">
                    <span class="btn btn-outline-primary btn-sm" @click="add_var('event-title')"> Event Title </span>
                    <span class="btn btn-outline-primary btn-sm" @click="add_var('categories')"> Categories </span>
                    <span class=" input-group-text btn btn-outline-primary btn-sm" @click="add_var('event-start')"> Event Start </span>
                    <span class="input-group-text btn btn-outline-primary btn-sm" @click="add_var('event-end')"> Event End </span>
                    <a target="_blank" href="https://docs.google.com/document/d/1vfMg0AW7SUIdA39azrnGwPabPk1R8QwWslbyX6fd83M/edit?usp=sharing" class="input-group-text text-primary" title="Help for format"><span class="fa fa-question"></span></a>
                </span>
            </label>
            <tag-previewer v-if="tag_data" ref="tag_previewer" v-model="tag_data"></tag-previewer>
        </div>
    </div>
</template>

<script>
    import TagPreviewerVue from '../TagPreviewer.vue';
    export default {
        props:[ "el_object", "value","readonly","is_showbutton","tag_data" ],
        components: { 
            "tag-previewer":TagPreviewerVue
        },
        watch: {
            value(newValue) {
                this.item_value = newValue; 
            },
        },
        data: function () {
            return {    
                item_value:null,
                checkboxName:'checkbox-'+this._uid,
                inputEl:'input-el-'+this._uid
            }
        },
        methods: { 
            value_changed:function(){
                if(this.tag_data)
                    this.tag_data.tag_name = this.item_value;
                
                if(this.$refs.tag_previewer){
                    this.$refs.tag_previewer.tag_name = this.item_value;
                    this.$refs.tag_previewer.loadPreview();
                } 
            },
            add_var:function(variable){
                
                var el = document.querySelector('#'+this.inputEl);                
                var startPos = el.selectionStart;
                var endPos = el.selectionEnd;
                var text = '';
                if(variable == 'event-title'){
                    text = '{{phb-event-title}}';
                } 
                else if(variable == 'event-start'){
                    text = '{{phb-event-start format=<format> [timezone=<str>] [offset_mins=<int>]}}'; 
                }
                else if(variable == 'event-end'){
                    text = '{{phb-event-end format=<format> [timezone=<str>] [offset_mins=<int>]}}';
                }
                else if(variable == 'categories'){
                    text = '{{categories}}';
                }
                el.value = el.value.substring(0, startPos) + text + el.value.substring(endPos, el.value.length); 
                this.item_value = el.value;
                //this.$emit('input', this.input_value);
                //this.$emit('changed', this.input_value);
                this.value_changed();
            },
            radioChanged:function(evt){
                //console.log(evt, this.item_value);

                this.$emit('input', this.item_value);
                this.$emit('changed', this.el_object, this.item_value);
            },
            textChanged:function(evt){
                this.$emit('input', this.item_value);
                this.$emit('changed', this.el_object, this.item_value);
                this.value_changed();
            },
            selectionChanged:function(evt){
                //console.log(evt);
                this.$emit('input', this.item_value);
                this.$emit('changed', this.el_object, this.item_value);
            },
            checkBoxChanged:function(){
                var checkboxes = document.getElementsByName(this.checkboxName);
                var selectedValues = [];
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        selectedValues.push(checkbox.value);
                    }
                });
                this.item_value = selectedValues;
                this.$emit('input', this.item_value);
                this.$emit('changed', this.el_object, this.item_value);
            }
        },
        mounted:function(){     
            this.item_value = this.value;
            var el_object = this.el_object;
            if(!this.value && el_object){
                if(el_object.dataType == 'RADIO' || el_object.dataType == 'SINGLE_OPTIONS'){
                    if(el_object.picklistOptions.length > 0)
                        this.item_value = el_object.picklistOptions[0];
                }
                else if(el_object.dataType == 'TEXT' || el_object.dataType == 'PHONE'){
                    this.item_value = "";
                }
                else if(el_object.dataType == 'CHECKBOX' || el_object.dataType=='MULTIPLE_OPTIONS'){
                    if(el_object.picklistOptions.length > 0)
                        this.item_value = [ el_object.picklistOptions[0] ];
                }
                else if(el_object.dataType == 'LARGE_TEXT'){
                    this.item_value = "";
                }
                this.$emit('input', this.item_value);
            }

        },
        updated:function(){

        }
    }
</script>
