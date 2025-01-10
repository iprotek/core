<template>
    <div>  
        <div class="card p-0">
            <div class="card-header">
                <h4>Custom Fields</h4>
            </div>
            <div class="card-body pt-0">
                <div class="input-group" >
                    <span class="input-group-text btn btn-outline-success bg-success" @click="$refs.modal_custom_field.show()" > 
                        <span class="fa fa-plus"></span>&nbsp;&nbsp;Add Custom Field
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
                                <h4 class="text-center">Loading Form Custom Fields</h4>
                            </td>
                        </tr>
                        <tr v-else-if="customList.length == 0">
                            <td colspan="2">
                                <h4 class="text-center">No Custom Fields Found.</h4>
                            </td>
                        </tr>
                        <tr v-for="(customItem, customIndex) in customList" v-bind:key="'custom-item-'+customIndex+'-'+_uid">
                            <td v-text="customItem.name"></td>
                            <td>
                                <button class="btn btn-outline-warning btn-sm" @click="$refs.modal_custom_field.show(customItem.id)" >
                                    <span class="fa fa-edit"></span> Edit
                                </button>
                                <button class="btn btn-outline-danger btn-sm" @click="$refs.modal_custom_field.remove(customItem.id)">
                                    <span class="fa fa-times"></span> Remove
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <page-footer v-model="pageFooterData" @page_changed="page_changed"></page-footer>
            </div>
        </div>
        <modal-custom-field ref="modal_custom_field" :group_id="group_id" @modal_updated="loadpageList"></modal-custom-field>
    </div>  
</template>

<script> 
    import PageFooterVue from '../../../common/PageFooter.vue'
    import ModalCustomFieldVue from './ModalCustomField.vue'
    export default {
        props:[ "group_id" ],
        components: {
            "page-footer":PageFooterVue,
            "modal-custom-field":ModalCustomFieldVue
        },
        data: function () {
            return {    
                pageFooterData:null,
                current_page:1,
                isLoad:false,
                customList:[],
                search:''
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
                this.customList = [];
                WebRequest2('GET', '/api/group/'+this.group_id+'/settings/custom-field/list?'+this.queryString({
                    search:this.search,
                    page: this.current_page,
                    items_per_page:10
                })).then(resp=>{
                    resp.json().then(data=>{
                        this.pageFooterData = data;
                        this.customList = data.data;
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
