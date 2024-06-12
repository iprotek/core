<template>
    <div>
        <div class="input-group">
            <span style="font-size:1.3em;" ></span>
            <button v-if="order.orderBy" class="input-group-text" style="font-size:1.3em;" @click="order.orderBy = ((order.orderBy=='DESC')?'ASC':'DESC')">
                <span class="fa fa-arrow-up" title="ASCENDING" v-if="order.orderBy == 'ASC'" ></span>
                <span class="fa fa-arrow-down" title="DESCENDING" v-if="order.orderBy == 'DESC'" ></span>
            </button>
            <select @change="$emit('selected', order.selectedFieldId)" class="form-control" v-model="order.selectedFieldId" :style=" (!order.orderBy) ? 'font-size:1.1em;' : '' ">
                <option value="" selected v-text="order.placeholder"></option>
                <option v-for="(item, index) in order.fieldList" :value="item.id" v-bind:key="'orderfield-'+_uid+'-'+item.id+'-'+index" v-text="item.text"></option> 
            </select>
        </div>
    </div>
</template>
<script> 
    export default {
        props: ["value"],
        data: function () {
            return { 
                order:{
                    orderBy:'ASC',
                    selectedFieldId:'',
                    fieldList:[],
                    placeholder:'-- FIELD ORDER --'
                }
            }
        }, 
        watch: {
            value(newValue) {
                this.setValue(newValue);
            },
        },
        methods: {  
            setValue:function(newValue){
                var vm = this;
                if(newValue){
                    vm.order = newValue;
                    //vm.order.selectedField = newValue.selectedField;
                    //vm.order.fieldList = newValue.fieldList;
                    if(!newValue.placeholder){
                        vm.order.placeholder = '-- FIELD ORDER --';
                    }
                    else if(!vm.order.fieldList){
                        vm.order.fieldList = [];
                    }
                    else if(!vm.order.selectedFieldId){
                        vm.order.selectedFieldId = '';
                    }

                }
            }
        },
        mounted: function () {
            this.setValue(this.value);

        }
    }
</script>
