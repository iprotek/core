<template>
    <span :class="'text-nowrap '+(is_right ? 'float-right':'float-left') " style="position:relative;z-index:99;" >
        <div class="text-nowrap">
            <i @click="sortClick('ASC')" title="ASCENDING" :class="'icon-sort-'+(type=='numeric' ?'numeric':'alpha')+'-up '+(selected_sort == 'ASC' ? active_class:  ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
            <i @click="sortClick('DESC')" title="DESCENDING" :class="'icon-sort-'+(type=='numeric' ?'numeric':'alpha')+'-down '+(selected_sort == 'DESC' ? active_class: ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
            <span v-if="selected_sort && item_priority" style="border: 1px solid green; border-radius: 50%; padding: 1px 4px; font-size: 7px; font-weight: bold; position: absolute; bottom: -7%; right:27%;" title="Sort Priority" v-text="item_priority"></span>
        </div>
    </span>
</template>

<script>
    export default {
        props:[ "value", "type","active_class", "inactive_class", "field", "is_right"  ],
        components: { 
        },
        watch: {
            value(newValue) {
                //this.input_value = newValue;
                if(newValue){
                   this.filter = newValue; 
                }
                this.getItemIndex();
            },
        },
        data: function () {
            return { 
                filter:{},
                selected_sort:'',
                item_priority:0
            }
        },
        methods: { 
            getItemIndex:function(){
                console.log("Triggered");
                if(!this.filter){
                    this.item_priority = 0;
                    return;
                };
                if(!this.field){
                    this.item_priority = 0;
                    return;
                }
                if(!this.filter[this.field]){
                    this.item_priority = 0;
                    return;
                }
                var obj = this.filter;
                // Convert object into entries to work with indices
                const entries = Object.entries(obj);
                if( Object.keys(obj).length <= 1 ){
                    this.item_priority = 0;
                    return;
                }

                // Find the index where `a` is 1 and `b` is 2
                const index = entries.findIndex(([key, value]) => {
                    if (key === this.field) return true;
                    return false;
                });
                if(index >= 0){
                    this.item_priority = index + 1;
                }else{
                    this.item_priority = 0;
                }
            },
            sortClick:function(val){
                var  vm = this;
                this.selected_sort = this.selected_sort == val ? '':val

                if(this.filter && typeof this.filter === 'object'){
                    if(!this.selected_sort)
                        delete this.filter[this.field];
                    else
                        this.filter[this.field] = this.selected_sort;
                    
                    var trans = vm.filter;
                    vm.$emit('input', null);
                    setTimeout(()=>{
                        vm.$emit('input', trans);
                        vm.$emit('sort_changed', vm.selected_sort, vm.filter);
                    }, 10);
                    //this.$emit('update:value', this.filter);
                }else{
                    this.$emit('sort_changed', this.selected_sort, this.filter);
                }
                //this.getItemIndex();
            }
        },
        mounted:function(){ 
            if(this.value)
                this.filter = this.value;  
            this.getItemIndex();
        },
        updated:function(){

        }
    }
</script>
