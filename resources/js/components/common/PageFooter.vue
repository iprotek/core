<template>
    <div class="w-100">
        <label v-text="'Total Items: '+total_item"></label>
        <div style="float:right;" class="text-nowrap">
            <button @click="pagePrev" class="btn btn-primary" v-text="'<'" :disabled="current_page<=1">  </button>
            <label>Page</label>
            <input class="form-control" v-model="current_page" @change="pageChanged" style="width:40px; text-align:center; display:inline;"/>
            <label v-text="'of '+last_page"></label>
            <button @click="pageNext" class="btn btn-primary" v-text="'>'" :disabled="current_page>=last_page">  </button>
        </div>
        
    </div>
</template>
<script> 
    export default {
        props: ["value"],
        data: function () {
            return { 
                current_page:1,
                total_item:0,
                last_page:1,
                page_trigger:1,
                input_value:null
            }
        }, 
        watch: {
            value(newValue) {
                this.input_value = newValue;
                if(this.input_value){
                    this.setPaging(this.input_value);
                }
            },
        },
        methods: { 
            setPaging:function(paginateData){
                this.current_page = paginateData.current_page;
                this.total_item = paginateData.total;
                this.last_page = paginateData.last_page;
            },
            pageNext:function(){
                this.current_page++;
                this.pageChanged();
            },
            pagePrev:function(){
                this.current_page--;
                this.pageChanged();
            },
            pageChanged:function(){
                if(this.current_page < 1)
                    this.current_page = 1;
                else if(this.current_page > this.last_page){
                    this.current_page = this.last_page;
                }
                else if(isNaN(this.current_page)){
                    this.current_page = 1;
                }
                //Prevent from trigger same page
                if(this.current_page == this.page_trigger){
                    return;
                }
                this.page_trigger = this.current_page;
                this.$emit('page_changed', this.current_page);
            }
        },
        mounted: function () { 
            if(this.value){
                this.input_value = this.value;
                this.setPaging(this.value);
            }

        }
    }
</script>
