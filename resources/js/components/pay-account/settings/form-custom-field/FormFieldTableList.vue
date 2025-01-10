<template>
    <div>  
        <div class="card p-0">
            <div class="card-header">
                <h4>Forms</h4>
            </div>
            <div class="card-body pt-0"> 
                <div v-if="view == 'form-table'">
                    <div class="input-group" >
                        <span class="input-group-text btn btn-outline-success bg-success" @click="view = 'add-form'" > 
                            <span class="fa fa-plus"></span>&nbsp;&nbsp;Add Form Custom Field
                        </span> 
                        <span class="input-group-text">
                            <span class="fa fa-search"></span>
                        </span> 
                        <input placeholder="Search category keywords.. " v-model="search" style="text-align:left;" class="form-control" type="text" @keyup.enter="input_search" />      
                        <button class="input-group-text" @click="input_search">Find</button> 
                    </div> 
                    <table class="w-100 table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="isLoad">
                                <td colspan="2">
                                    <h4 class="text-center">Loading Forms</h4>
                                </td>
                            </tr>
                            <tr v-else-if="formCustomList.length == 0">
                                <td colspan="2">
                                    <h4 class="text-center">No Forms Found.</h4>
                                </td>
                            </tr>
                            <tr v-for="(formItem,formIndex) in formCustomList" v-bind:key="'form-item-'+formIndex+'-'+_uid">
                                <td v-text="formItem.name"></td>
                                <td>
                                    <button class="btn btn-outline-warning btn-sm" >
                                        <span class="fa fa-edit"></span> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" >
                                        <span class="fa fa-times"></span> Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
                </div>
                <div v-else-if="view == 'add-form'">
                    <button class="btn btn-outline-primary btn-sm" @click="view = 'form-table'">
                        Back
                    </button>
                    <add-form ref="add_form" :group_id="group_id" :form_custom_field_id="0"></add-form>
                </div>
            </div>
        </div> 
    </div>  
</template>

<script>
    import PageFooterVue from '../../../common/PageFooter.vue' 
    import AddFormVue from './AddForm.vue'
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue,
            "add-form":AddFormVue
        },
        data: function () {
            return {    
                pageFooterData:null,
                current_page:1,
                isLoad:false,
                formCustomList:[],
                search:'',
                view:'form-table'
            }
        },
        methods: {  
            input_search:function(){
                this.current_page = 1;
                this.loadpageList();
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            page_changed:function(page){
                this.current_page = page;
            },
            loadpageList:function(){
                
                this.isLoad = true;
                WebRequest2('GET', '/api/group/'+this.group_id+'/settings/form-custom-field/list?'+this.queryString({
                    search:this.search,
                    page: this.current_page,
                    items_per_page:10
                })).then(resp=>{
                    resp.json().then(data=>{
                        this.pageFooterData = data;
                        this.formCustomList = data.data;
                        this.isLoad = false;
                    })
                });
            }

        },
        mounted:function(){     
            this.loadpageList();
        },
        updated:function(){

        }
    }
</script>
