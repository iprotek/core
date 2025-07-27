<template>
    <div>
        <page-search-container 
            ref="page_search"
            :searchText="search_text" 
            :currentPage="current_page"
            :isLoading="is_loading"
            :itemList="items"
            :search_placeholder="search_placeholder" 
            :is_use_top_search="is_use_top_search"

            @update:currentPage="current_page = $event"
            @update:searchText="search_text = $event"
            @update:isLoading="isLoading = $event"
            @update:itemList="itemList = $event"
            
            :fn_plus_click="fn_plus_click"
            :fn_web_request2="loadList"
            
            >
            <slot></slot>
        </page-search-container>
    </div>
</template>

<script>
    import PageSearchContainerVue from './PageSearchContainer.vue';
    export default {
        props:[ "method", "url", "items", "is_loading", "json_filter", "search_placeholder", "is_use_top_search", "fn_plus_click"  ],
        $emits:["update:items", "update:is_loading"],
        watch: {
            url:function(newVal){
                this.reloadPage();
            },
            json_filter:function(newVal){
                this.reloadPage();
            }
        },
        components: { 
            "page-search-container":PageSearchContainerVue
        },
        data: function () {
            return { 
                search_text:'',
                current_page:'',
                isLoading:false,
                itemList:[],
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

            isJsonObject:function(value) {
                return value !== null && typeof value === 'object' && !Array.isArray(value);
            },
            reloadPage:function(){
                this.$refs.page_search.search_now();
            },
            loadList:function(){

                var vm = this;

                var method = "GET";
                if(vm.method){
                    method = vm.method;
                }

                var obj1 = {
                    page: this.current_page,
                    search_text: this.search_text,
                    branch_id: this.branch_id,
                    items_per_page:10
                };
                var obj2 = {};
                if(this.isJsonObject(vm.json_filter)){
                    obj2 = vm.json_filter;
                }

                var merged = Object.assign({},obj1, obj2 );
                vm.$emit("update:items", []);
                vm.$emit("update:is_loading", true); 
                return WebRequest2(method, vm.url+'?'+this.queryString(merged)).then(resp=>{
                    return resp.json().then(data=>{
                        vm.$emit("update:items", data.data);
                        vm.$emit("update:is_loading", false);
                        return data;
                    });
                });
            }

        },
        mounted:function(){     
            this.itemList = this.items;
        },
        updated:function(){

        }
    }
</script>
