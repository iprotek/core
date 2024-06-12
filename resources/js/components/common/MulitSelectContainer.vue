<template>    
    <div class="w-100 card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-3 d-flex align-items-center justify-content-center">
                    <label class="m-0" v-text="title"></label>
                </div>
                <div class="col-sm-9">
                    <select2 :modal_selector="modal_selector" v-model="item_select" :has_clear="has_clear" @selected="fn_item_selected" :placeholder="select_placeholder"  :url="url" ></select2>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div>
                <div v-if="items.length == 0">
                    <code v-if="empty_items_text" v-text="empty_items_text">   </code>
                    <code v-else> ~~ No Entry Available ~~ </code>
                </div>
                <div v-for="(item, itemIndex) in items" v-bind:key="'m-select-'+_uid+'-'+itemIndex" class="my-1" style="display: inline;">
                    <label class="p-1">
                        <div class="input-group pr-1">
                            <span class="btn btn-outline-danger input-group-text" style="border-color: var(--bs-gray-400);" @click="remove_item(item)">
                                <span class="fa fa-times"></span>
                            </span> 
                            <span class="input-group-text fw-bold" v-text="item"></span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Select2Vue from './Select2.vue'; 
    export default {
        props:[ "value", "title", "modal_selector", "select_placeholder", "url", "has_clear", "empty_items_text"],
        components: { 
            "select2":Select2Vue
        },
        watch: {
            value(newValue) {
                this.items = newValue;
            },
        },
        data: function () {
            return {  
                items:[],
                item_select:{
                    id:0,
                    text:''
                }
            }
        },
        methods: {  
            remove_item:function(item){
                var vm = this;
                vm.items = vm.items.filter(a=>a!=item); 
                vm.$emit("input", vm.items);
            },
            fn_item_selected:function(val){ 
                //console.log(val, this.item_select);
                val = this.item_select;
                if(val.id <= 0){
                    vm.clear_selection();
                     return;
                }
                var vm = this;
                var item = vm.items.filter(a=> a == val.text)[0]; 
                if(item){
                    vm.clear_selection();
                    return;
                }else if(!val.text.trim()){
                    vm.clear_selection();
                    return;
                }

                var items = vm.items;
                items.push(val.text);
                vm.items = items;

                vm.$emit("input", vm.items);
                vm.clear_selection();
            },
            clear_selection:function(){
                var vm = this;
                setTimeout(()=>{
                    vm.item_select = {
                        id:0,
                        text:''
                    }
                }, 50);
            }
        },
        mounted:function(){
            if(this.value){
                this.items = this.value;
            }
        }
    }
</script>