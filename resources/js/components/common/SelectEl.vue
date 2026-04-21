<template>  
    <select class="form-control" v-model="id" @change="sel_changed">
        <option :value="0" v-text="placeholder"></option>
        <option v-for="(item,itemIndex) in item_list" v-bind:key="'item-list-'+itemIndex+'-'+_uid+'-'+item.id" :value="is_text_value? item.text: item.id" v-text="item.text"></option>
    </select>
</template>
<script>
    
    import { getCurrentInstance } from 'vue';
    export default {
        props:[ "modelValue", "value","placeholder","is_text_value","url" ],
        components: { 
        },
        watch: {
            modelValue(newValue){
                this.search_text = newValue;
            },
            value(newValue) {
                this.search_text = newValue;
            },
        },
        data: function () {
            let _uid = getCurrentInstance().uid;
            return {
                _uid: _uid,
                data_list_id: 'data-list-'+_uid,
                search_text:'',
                search_fn:null,
                counter:1,
                id:0,
                item_list:[]
            }
        },
        methods: {  
            text_changed:function(){                
                this.$emit("input", this.search_text);
                this.$emit('update:modelValue', this.search_text);
            },
            sel_changed:function(evt){
                var vm = this;

                var selected = false;
                
                this.$emit("input", this.id);
                this.$emit('update:modelValue', this.id);
                vm.item_list.forEach((el)=>{
                    if(selected)
                        return; 
                    if(vm.is_text_value){
                        if(vm.id.trim().toLowerCase() == el.text.trim().toLowerCase()){
                            selected = true;
                            vm.$emit('selected', el);
                        } 
                    }
                    else if(vm.id == el.id){
                            selected = true;
                            vm.$emit('selected', el);                        
                    }

                });

                if(selected == false){
                    vm.$emit('selected', {id:0, text: ''});
                }

            },
            search_keyup:function(){
                var vm = this;
                if(this.search_fn){
                    clearTimeout(this.search_fn);
                }

                this.search_fn = setTimeout(()=>{ 
                    vm.loadItems();
                }, 1000);
            },
            loadItems:function(){
                var vm = this;
                if(vm.url){
                    WebRequest2('GET', vm.url+'?search_text='+this.search_text).then(resp=>{
                        resp.json().then(data=>{  
                            
                            vm.item_list = data.data;
                        })
                    });
                }
            }
        },
        mounted:function(){    
            if(this.value){
                this.search_text = this.value;
            }
            this.loadItems();
        }
    }
</script>