<template>
    <span :class="'text-nowrap '+(is_right ? 'float-right':'float-left') " style="position:relative;z-index:99;" >
        <div class="text-nowrap" v-if="type=='numeric'">
            <i @click="sortClick('ASC')" title="ASCENDING" :class="'icon-sort-numeric-up '+(selected_sort == 'ASC' ? active_class:  ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
            <i @click="sortClick('DESC')" title="DESCENDING" :class="'icon-sort-numeric-down '+(selected_sort == 'DESC' ? active_class: ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
        </div>
        <div class="text-nowrap" v-else>
            <i @click="sortClick('ASC')" title="ASCENDING" :class="'icon-sort-alpha-up '+(selected_sort == 'ASC' ? active_class: ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
            <i @click="sortClick('DESC')" title="DESCENDING" :class="'icon-sort-alpha-down '+(selected_sort == 'DESC' ? active_class:  ( inactive_class ? inactive_class: 'text-secondary' ) )"></i>
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
            },
        },
        data: function () {
            return { 
                filter:{},
                selected_sort:''
            }
        },
        methods: { 
            sortClick:function(val){
                this.selected_sort = this.selected_sort == val ? '':val

                if(this.filter && typeof this.filter === 'object'){
                    if(!this.selected_sort)
                        delete this.filter[this.field];
                    else
                        this.filter[this.field] = this.selected_sort;

                    this.$emit('input', this.filter);
                }
                this.$emit('sort_changed', this.selected_sort);

            }
        },
        mounted:function(){ 
            if(this.value)
                this.filter = this.value;  
        },
        updated:function(){

        }
    }
</script>
