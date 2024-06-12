<template>    
    <label>
        <input :id="data_input_list_id" @focus="showOptions" :placeholder="placeholder" :list="data_list_id" @change="text_changed" class="form-control form-control-sm" @keyup="search_keyup" v-model="search_text" />
        <datalist :id="data_list_id">
            <option v-for="(item,itemIndex) in item_list" v-bind:key="'item-list-'+itemIndex+'-'+_uid+'-'+item.id" :value="is_text_value? item.text: item.id" v-text="item.text"></option>
        </datalist>
    </label>
</template>
<script>
    
    export default {
        props:[ "value", "exclude_values", "placeholder","is_text_value","url","placeholder" ],
        components: { 
        },
        watch: {
            value(newValue) {
                this.search_text = newValue;
                /*
                this.item = newValue;
                if(this.item){
                    this.selectedText = this.item.text;
                    this.selectedItem = this.item;
                }
                */
                //this.loadElement();
            },
        },
        data: function () {
            return {  
                data_input_list_id:'data-input_'+this._uid,
                data_list_id: 'data-list-'+this._uid,
                search_text:'',
                search_fn:null,
                counter:1,
                item_list:[]
            }
        },
        methods: {  
            showOptions:function(){
                var input = document.querySelector('#'+this.data_input_list_id);
                if(!input.value){
                    //input.blur();
                    input.value = ' ';
                    input.value = '';
                }
           },  
            text_changed:function(){                
                this.$emit("input", this.search_text);
                var vm = this;

                var selected = false;
                vm.item_list.forEach((el)=>{
                    if(selected)
                        return; 
                    if(vm.search_text.trim().toLowerCase() == el.text.trim().toLowerCase()){
                        selected = true;
                        vm.$emit('selected', el);
                    } 

                });
                if(selected == false){
                    vm.$emit('selected', {id:0, text: this.search_text});
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
                WebRequest2('GET', vm.url+'?search_text='+this.search_text).then(resp=>{
                    resp.json().then(data=>{
                        var result = data.data.filter((item)=>{
                            
                            if(vm.exclude_values && typeof vm.exclude_values === 'object' && vm.exclude_values !== null){
                                return !vm.exclude_values.filter( val => val.trim().toUpperCase()== item.text.trim().toUpperCase() )[0];
                            }
                            return true;
                        
                        });


                        vm.item_list = result; 
                    })
                });
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