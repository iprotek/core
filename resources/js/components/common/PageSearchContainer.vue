<template>
    <div>
        <div v-if="is_use_top_search !== true"> 
            <div class="input-group text-nowrap mb-2">
                <button v-if="typeof fn_plus_click === 'function'" @click="fn_plus_click()" class="btn btn-default text-primary">
                    <span class="fa fa-plus"></span>
                </button>
                <button class="btn btn-default"><span class="fa fa-search"></span></button> 
                <input v-model="search_text" type="text" :placeholder="search_placeholder" @keyup.enter="text_changed" class="form-control">
                <button class="btn btn-default" @click="text_changed">FIND</button> 
            </div>
        </div>
        <div>
            <slot></slot>
        </div>
        <page-footer v-model="pagefooterData"  @page_changed="page_chaged"/>
    </div>
</template>

<script>
    import PageFooter from './PageFooter.vue';
    export default {
       
        props:[ 
            "currentPage", 
            "isLoading", 
            "searchText",
            "itemList",
            "fn_plus_click", 
            "search_placeholder", 
            "fn_web_request2",
            "is_use_top_search"
         ],
        emits: [
            'update:currentPage', 
            'update:searchText', 
            'update:isLoading',
            'update:itemList',
            'search_text_changed',
            'page_changed'
        ],
        components: {
            "page-footer":PageFooter 
        },
        watch: {
            currentPage:function(newVal){
                this.current_page = newVal;
                this.search_now();
            },
            searchText:function(newVal){
                this.search_text = newVal;
                this.search_now();
            }
        },
        data: function () {
            return {
                current_page:1,
                search_text:'',
                pagefooterData:null
            }
        },
        methods: { 

            text_changed:function(){
                var vm = this;
                this.$emit('update:currentPage', 1);
                vm.$emit('update:searchText', vm.search_text); 
                vm.$emit('search_text_changed', vm.search_text );
            },

            search_now:function(){
                var vm = this;
                if(typeof this.fn_web_request2 === 'function'){
                    vm.$emit('update:isLoading', true); 
                    vm.$emit('update:itemList', []); 
                    vm.fn_web_request2().then(pageData=>{
                        vm.pagefooterData = pageData;
                        vm.$emit('update:isLoading', false); 
                        vm.$emit('update:itemList', pageData.data);
                    });
                }
            },
            page_chaged:function(val){
                this.$emit('update:currentPage', val);
                this.$emit('page_changed', val);
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

        },
        mounted:function(){
            this.current_page = this.currentPage;
            this.search_now();
            
            var vm = this;
            
            if(vm.is_use_top_search){
                var search_input_el = document.querySelector('#search-text-input');
                if(search_input_el){
                    if(vm.search_placeholder)
                        search_input_el.placeholder = vm.search_placeholder;
                    search_input_el.onkeyup = function(evt){
                        //console.log(evt);
                        if(evt.keyCode == 13){
                            vm.search_text = evt.target.value;
                            vm.$emit('update:searchText', vm.search_text); 
                            vm.search_now();
                            //console.log("Top trigger", vm.search_text);
                        }
                        else if(evt.keyCode == 27){
                            var parent = evt.target.closest('.navbar-search-block.navbar-search-open');
                            if(parent){
                                parent.setAttribute("class", "navbar-search-block");
                                parent.style.display = "none";
                            }
                        }
                    }
                }
            }
                
        },
        updated:function(){

        }
    }
</script>
