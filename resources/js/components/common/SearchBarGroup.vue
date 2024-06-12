<template>
    <div class="w-100"> 
        <div class="input-group w-100" >
            <span class="input-group-text btn btn-success" v-if="add_title" :title="add_title" @click="$emit('add_click')" > 
                <span class="fa fa-plus"></span> 
            </span>
            <span v-if="list_title" class="input-group-text btn btn-outline-primary" :title="list_title" @click="$emit('list_click')" > 
                <span class="fa fa-list"></span> 
            </span>
            <span class="input-group-text">
                <span class="fa fa-search"></span>
            </span>
            <input v-if="input_data.search_input" :placeholder="input_data.search_input.placeholder" v-model="input_data.search_input.search_text" style="text-align:left;" class="form-control" type="text" @keyup.enter="$emit('search_changed', input_data)" /> 
            <span v-for="(selItem, selIndex) in input_data.selections" v-bind:key="'search-bar-'+_uid+'-'+selIndex" class="form-control p-0" >
                <select2 v-if="selItem.url"
                    @selected="selectedItem" 
                    v-model="selItem.itemInfo" 
                    :default_theme="selItem.theme" 
                    :search_param="selItem.search_param" 
                    :placeholder="selItem.placeholder ? selItem.placeholder :'FIND ITEM'" 
                    :url="selItem.url"  ></select2>
            </span>
            <button class="input-group-text" @click="$emit('search_changed', input_data)">Find</button>
            <span v-for="(ordItem,ordIndex) in input_data.order_fields" v-bind:key="'order-item-'+_uid+'-'+ordIndex" class="input-group-text p-0" >
                <order-fields @selected="$emit('search_changed', input_data)" v-if="ordItem && ordItem.model" v-model="ordItem.model"></order-fields>
            </span>
        </div> 
    </div>
</template>

<script>
    /**
     * EVENTS
     *  add_click
     *  list_click           //
     *  search_changed      //text search text  
     */
    /** THIS IS THE INPUT MODEL
     * input_data settings:
     * 
     * {
     *  search_input:{
     *      search_text:'',
     *      placeholder:'',
     *  },
     *  selections:[{
     *      itemInfo:{
     *          id:0,
     *          text:'',
     *      },
     *      theme:'boostrap-5',
     *      search_param:'search',
     *      placeholder: ' -- SEARCH ITEM --',
     *      url:'' //GET
     *  }],
     *  order_fields:[
     *    { model:
     *      {
    *           orderBy:'ASC',
    *           selectedFieldId:'',
    *           fieldList:[{
    *           id:1,
    *           text:'Name'
    *           }]
     *     }
     *  }
     * 
     * ]
     * 
     * }
     * 
     */


    import Select2Vue from './Select2.vue'
    import OrderFieldsVue from './OrderFields.vue'
    export default {
        props:[ "list_title", "add_title","value" ],
        components: { 
            "select2": Select2Vue,
            "order-fields":OrderFieldsVue
        },
        watch: {
            value(newValue) {
                if(newValue){
                    var inp = this.input_data;
                    inp.search_input = newValue.search_input;
                    inp.selections = newValue.selections;
                    inp.order_fields = newValue.order_fields;
                } 
            },
        },
        data: function () {
            return {    
                input_data:{
                    search_input:null, 
                    selections:[], 
                    order_fields:[], 
                }
            }
        },
        methods: { 
            selectedItem:function(val, tags){
                //if(this.input_data.selections)
                //    this.input_data.selections.tags = tags;
                $emit('search_changed', this.input_data);
            }
        },
        mounted:function(){      
            if(this.value){
                //This style is necessary due to some error encounter on base sub field model
                var inp = this.input_data;
                inp.search_input = this.value.search_input;
                inp.selections = this.value.selections;
                inp.order_fields = this.value.order_fields;
            }
        },
        updated:function(){

        }
    }
</script>
