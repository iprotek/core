<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Models</div>
                    <div class="card-body">
                        
                        <div class="input-group" >
                            <span class="input-group-text btn btn-success" title="Add Searchable" @click="$emit('show_add_model');"  > 
                                <span class="fa fa-plus"></span> 
                            </span>
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                            <input class="form-control" placeholder="Seach any keywords"  /> 
                            <button class="input-group-text" >Find</button>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td> Info </td>
                                        <td style="width:150px;">Type</td>
                                        <td style="width:100px;">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="isLoading">
                                        <th colspan="3" class="text-center">
                                            Loading Models..
                                        </th> 
                                    </tr>
                                    <tr v-else-if="pageDataList.length == 0">
                                        <th colspan="3" class="text-center">
                                            No model found.
                                        </th> 
                                    </tr>
                                    <tr v-for="(fieldItem, fieldIndex) in pageDataList" v-bind:key="'model-field-'+fieldItem.id+'-'+fieldIndex">
                                        <th v-text="fieldItem.name"></th>
                                        <td v-text="fieldItem.type"></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm"  @click="$emit('show_add_model', fieldItem.id, fieldItem)">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <page-footer  v-model="pageData" @page_changed="page_changed"></page-footer>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>

    import PageFooterVue from '../../PageFooter.vue'; 
    export default {
        props:[  ],
        components: { 
            "page-footer":PageFooterVue, 
        },
        data: function () {
            return {    
                search:'',
                pageData:'',
                pageDataList:'',
                current_page:1,
                isLoading:false
            }
        },
        methods: { 

            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
                this.loadModel();
                
            },
            loadModel:function(){
                var vm = this;
                vm.pageDataList = [];
                vm.isLoading = true;
                WebRequest2('GET', '/manage/projects-monitoring/model-fields/model/list?'+this.queryString({
                    search:this.search,
                    page:this.current_page,
                    items_per_page:10
                })).then(resp=>{
                    resp.json().then(data=>{
                        vm.pageData = data;
                        vm.pageDataList = data.data;
                        vm.isLoading = false; 
                        console.log(vm.pageDataList);
                    })
                });
            }
        },
        mounted:function(){     
            this.loadModel();
        },
        updated:function(){

        }
    }
</script>
