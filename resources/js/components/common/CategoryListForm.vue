<template>
    <div>
        <div v-if="!is_integrate">
            <button class="btn btn-outline-primary btn-xs" @click="add_sub_category(base_parent_id)">
                <span class="fa fa-plus"></span> ADD SUB CATEGORY
            </button>
        </div>
        <div class="category-container"> 
            <div class="p-1" style="border:1px solid black;" v-for="(cat,catIndex) in sub_categories.filter(a=>a.parent_id == base_parent_id)" v-bind:key="'cat-'+catIndex+'-'+cat.id" v-html="compileSubHTML( cat)">

            </div>
        </div>
        
        <div :id="subCategoryElID" style="display:none;">
            <div>
                <div>
                    <button v-if="!is_integrate" class="btn btn-outline-success btn-xs btn-add-sub">
                        <span class="fa fa-plus"></span>
                    </button>
                    <button v-if="!is_integrate" class="btn btn-outline-danger btn-xs">
                        <span class="fa fa-times"></span>
                    </button>
                    <input :class="'category-checkbox '+baseChkID" v-if="is_integrate" type="checkbox" />
                    <label class="category-name m-0"></label>
                    <div class="sub-category ml-4 mb-2"></div>
                </div>
            </div>
        </div>
        <swal-input ref="swal_input" ></swal-input>
    </div>
</template>
<script>
    import SwalInputVue from './SwalInput.vue'
    export default {
        props:[ "value", "modal","selected_values","is_integrate" ],
        components: {  
            "swal-input":SwalInputVue
        },
        data: function () {
            return {     
                base_parent_id: 0,
                sub_categories:[],
                subCategoryElID: 'sub-cat-el-id-'+this._uid,
                selected_categories:[],
                baseChkID: 'cat'+this._uid+'-'
            }
        },
        watch: {
            value(newValue) {
                this.base_parent_id = newValue * 1;   
                this.initLoad();

            },
        },
        methods: {  
            initLoad:function(){
                
                window.addSubCategory = this.add_sub_category;
                window.toggleSelectCategory = this.toggleSelectCategory;

                if(this.selected_values)
                    this.selected_categories = this.selected_values;
                
                this.loadSubCategories();
            },
            updateSelected(selected_categories = null){
                var vm = this;
                if(selected_categories)
                    this.selected_categories = selected_categories;
                
                //CLEAR OLD
                document.querySelectorAll('input.'+this.baseChkID).forEach((el)=>{
                    el.checked  = false;
                });
                
                this.selected_categories.forEach((val)=>{
                    var el = document.getElementById(vm.baseChkID+val);
                    if(el){
                        el.checked = true;
                    }
                });
            },
            toggleSelectCategory:function(evt, val){
                if(evt.target){
                    var el = evt.target;
                    if(el.checked){
                        if(this.selected_categories.filter(a=>a == val).length <= 0)
                            this.selected_categories.push(val);
                    }
                    else{
                        this.selected_categories = this.selected_categories.filter(a=>a != val);
                    }

                }
            },
            compileSubHTML:function(categoryInfo){
                //console.log(evt);
                var getClone = this.categoryHTML(categoryInfo);
                return getClone.innerHTML;
            },
            categoryHTML:function(categoryInfo){
                var vm = this;
                var node = document.getElementById(this.subCategoryElID);
                var clone = node.cloneNode(true);
                clone.querySelector('.category-name').append(categoryInfo.name);
                if(!vm.is_integrate){
                    clone.querySelector('.btn-add-sub').setAttribute("onclick","addSubCategory("+categoryInfo.id+")");
                }
                else{
                    var checkEl = clone.querySelector('.category-checkbox');
                    checkEl.setAttribute("data-id", categoryInfo.id);
                    checkEl.setAttribute("value", categoryInfo.id);
                    checkEl.value = categoryInfo.id;
                    checkEl.id = this.baseChkID + categoryInfo.id;
                    checkEl.setAttribute("onclick","toggleSelectCategory(event,"+categoryInfo.id+")");
                }
                this.sub_categories.filter(a=>a.parent_id == categoryInfo.id).forEach(cat=>{
                    clone.querySelector('.sub-category').append( vm.categoryHTML(cat) );
                });

                return clone.firstChild;
            },
            add_sub_category:function(id){
                var vm = this;
                var request = {
                    parent_id: id,
                    base_parent_id: this.base_parent_id
                }
                if(this.modal){
                    this.modal.dismiss();
                } 
                this.$refs.swal_input.alert(
                        'question',
                        "Add Sub Category", 
                        "Confirm" , 
                        "POST", 
                        "/manage/stock-fields/categories/add", 
                        request
                    ).then(res=>{
                        if(vm.modal){
                            vm.modal.show(vm.base_parent_id);
                        }
                        if(res.isConfirmed){
                            vm.loadSubCategories();
                        }
                    });
            },
            loadSubCategories:function(){
                var vm = this;
                  WebRequest2('GET', '/manage/stock-fields/categories/get-list').then(resp=>{
                    resp.json().then(data=>{
                        vm.sub_categories = data.data;
                        //console.log(data); 
                        setTimeout(()=>{
                            vm.updateSelected();
                        }, 100);
                    })
                });

            }
        },
        mounted:function(){     
            this.initLoad();
            //this.base_parent_id = this.value * 1;
            //window.addSubCategory = this.add_sub_category;
        
        },
        updated:function(){
            //this.base_parent_id = this.value * 1;
            //this.loadSubCategories();
        }
    }
</script>
