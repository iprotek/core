<template>    
    <div :id="select2Selector" class="w-100">
        <div v-if="setUp">
            <select :id="selectElID" class="form-control form-select" style="width: 100%;" :multiple="allow_multiple">
                <option v-if="has_clear === true" :value="-1" selected="selected" v-text="placeholder"></option>
                <option v-if="item.id != 0" :value="item.id" selected="selected" v-text="item.text"></option>
            </select>
        </div>
    </div>
</template>
<script>
    
    export default {
        props:[ "value" ,"allow_multiple", "minimum_input_length" ,"is_remain_focus_on_enter", "is_clean_after_select", "url", "custom_data" , "default_theme", "append_data", "placeholder", "query_filters" ,"filters", "modal_selector", "allowtag", "has_clear", "disabled", "search_param", "display_items_no", "select_data", "select_template" ],
        emits: {
            data_result:(data)=>{},
            selected:(item, tags)=>{}
        },
        components: { 
        },
        watch: {
            value(newValue) {
                this.item = newValue;
                if(this.item){
                    this.selectedText = this.item.text;
                    this.selectedItem = this.item;
                }
                if(this.item && this.item.id ==0)
                    this.loadElement();
                //this.loadElement();
            },
        },
        data: function () {
            return { 
                remain_focus:false,
                select2Selector: 'select2-item-container-'+this._uid,
                setUp:false,
                selectElID: 'select-el-'+this._uid,
                item:{
                    id:0,
                    text:''
                },
                selectedText: '',
                itemList:[],
                selectedItem:null, 
                items_per_page : 10,
                select_tags:[]
            }
        },
        methods: { 
            loadElement:function(){
                var vm = this;
                this.setUp = false;
                setTimeout(()=>{
                    vm.setUp = true;
                    setTimeout(()=>{                    
                        vm.loadSelect();
                    }, 20);
                }, 20);

            },
            loadSelect:function(){
                var vm = this;
                var jSON = {
                    minimumInputLength: vm.minimum_input_length ? vm.minimum_input_length : 0
                }; 
                
                if(vm.select_template){
                    jSON.templateResult = function(data, container) {
                       return vm.select_template(data, container, vm.itemList);
                    }
                    jSON.templateSelection = function(data, container){ 
                       return  vm.select_template(data, container, vm.itemList); 
                    }
                    jSON.escapeMarkup = function(m) {
                        return m; 
                    }
                    //jSON.formatResult = vm.select_template;
                    //jSON.formatSelection = vm.select_template;
                }
                if(vm.url){
                    jSON.ajax = {
                        type: 'GET',
                        url: vm.url,
                        data: function (params) {
                            var query = {
                                filters: vm.filters,
                                page:1,
                                items_per_page: (vm.items_per_page*1)
                            } 
                            if(vm.query_filters && typeof vm.query_filters === 'object' && vm.query_filters !== null){
                                const keysArray = Object.keys(vm.query_filters);
                                keysArray.forEach((el)=>{
                                    query[el] = vm.query_filters[el];
                                });
                            }


                            if(vm.search_param){
                                query[vm.search_param]  = !params.term ? '':params.term;
                            }
                            else{
                                query.search_text = params.term;
                            }
                            // Query parameters will be ?search=[term]&type=public
                            return query;
                        },
                        dataType: 'json',
                        processResults: function (result) { 
                            var data = result.data; 

                            vm.$emit('data_result', result);
                            if(vm.append_data && vm.append_data.length > 0){
                                data = vm.append_data.concat(data);
                            }
                            if(vm.has_clear){
                                data = [{
                                    id:-1,
                                    text: vm.placeholder
                                }].concat(data);
                            }
                            vm.itemList = data;
                            if(vm.custom_data){
                                var item_list = vm.itemList.filter((item)=>item.id>0);
                                vm.custom_data(item_list);
                            }

                            return {
                                results: vm.itemList
                            };
                        },
                        cache: true
                    }
                }
                else if(vm.select_data){
                    jSON.data = vm.select_data;
                }


                
                if(vm.allowtag === true){
                    jSON.tags = true;
                }
                if(vm.placeholder){
                    jSON.placeholder = vm.placeholder;
                }                
                //THEME
                if(vm.default_theme == false ){ 
                }
                else if(!vm.default_theme || vm.default_theme === true ){
                    jSON.theme = "bootstrap4";
                }
                else jSON.theme = vm.default_theme;
                if(vm.modal_selector)
                    jSON.dropdownParent = $('#'+vm.select2Selector); //$(vm.modal_selector);
                
                $('#'+this.selectElID).select2(jSON).on('change', function (e) { 
                    //vm.itemSelected(this.value);
                    var selectedItem = vm.itemList.filter(a=>a.id == this.value)[0];

                    if(!selectedItem && !vm.url && vm.select_data){ 
                        selectedItem = vm.select_data.filter(a=>a.id == this.value)[0];
                    } 
                    
                    if(selectedItem){
                        vm.selectedItem = selectedItem;
                        vm.selectedText = selectedItem.text; 
                    }
                    else{
                        vm.selectedItem = {
                            id: 0,
                            text: this.value
                        }
                        vm.selectedText = this.value;
                    }
                    vm.item = {
                        id: vm.selectedItem.id,
                        text: vm.selectedItem.text
                    }
                    vm.$emit("input", vm.selectedItem);
                    if(vm.allowtag && vm.allow_multiple){
                        vm.sanitizeDuplicate();
                    }
                    vm.$emit("selected", vm.selectedItem, vm.select_tags);

                    if(vm.is_clean_after_select === true){        
                        
                        //Prevent looping
                        if(vm.item && vm.item.id <= 0)
                            return;

                        vm.selectedItem = null;

                        vm.item = {
                            id:0,
                            text: vm.placeholder
                        } 
                        setTimeout(()=>{ 
                            $('#'+vm.selectElID).trigger('input');
                            $('#'+vm.selectElID).val(null).trigger('change');
                        }, 100);
                    }
                }); 
                $('#'+this.selectElID).on('select2:unselect', function (e) {
                    //console.log("UNSELECT");
                    //console.log(e);
                    if (e.params.originalEvent != null && e.params.originalEvent.handleObj.type == "mouseup") {
                        $(this).append('<option value="' + e.params.data.id + '">' + e.params.data.text + '</option>');
                        let vals = $(this).val();
                        vals.push(e.params.data.id);
                        $(this).val(vals).trigger('change');
                        $(this).select2('close');
                    } else if (e.params.data.element != null) {
                        e.params.data.element.remove();
                    }
                });
                vm.remain_focus = vm.is_remain_focus_on_enter;
                if(vm.is_remain_focus_on_enter){
                    $('#'+this.selectElID).on('keypress', function(e) {
                        if (e.which === 13 && vm.remain_focus) { // Enter key code
                            e.preventDefault(); // Stop default behavior
                        }
                    });
                }
                
                if(this.disabled === true){
                    $('#'+this.selectElID).prop('disabled', true);
                }
            },
            sanitizeDuplicate:function(){
                //FIXING
                var vm = this;
                if(vm.default_theme != 'bootstrap-5')
                    return;
                var select2 = $('#'+this.selectElID);
                var items = select2.val(); 
                const uniqueValues = [...new Set(items)];
                vm.select_tags = uniqueValues;
                if(vm.selectedItem){
                    vm.selectedItem.tags = uniqueValues;
                }
                if(items.length == 1){
                    select2.val([items[0],items[0]]);
                }

            },
            focus:function(){
                $('#'+this.selectElID).focus();
            }
            
        },
        mounted:function(){   
            if(this.value){
                this.item = this.value;
            } 
            if(this.display_items_no){
                this.items_per_page = display_items_no;
            }
            this.loadElement();   
        }
    }
</script>