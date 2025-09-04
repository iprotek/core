<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ title ? title : 'FIELDS SETTINGS' }}
            </div>
            <div class="card-body">
                <div  >
                    <ol class="draggable-list" style="width:100%;" >
                        <li v-for="(item) in fieldList" 
                            v-bind:key="'item-field-'+_uid+'-'+item.order_no" draggable="true" 
                         class="py-1">
                            <div class="input-group input-group-sm ml-1">
                                <span class="btn btn-default" title="Show/Hide">
                                    <icheck :checked="item.is_active" @update:checked="(a,b)=>{ item.is_active = a } " />
                                </span>
                                <span class="btn btn-default" title="Drag and Drop">
                                    <span :class="`fa ${moveIcon}`"></span>
                                </span>
                                <label class="mb-0 form-control form-control-sm">
                                    {{ item.description }}
                                </label>
                            </div>
                        </li>
                    </ol>
                    <web-submit :action="saveField" el_class="btn btn-outline-primary btn-sm" icon_class="fa fa-save" label="SAVE" :timeout="3000" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import iCheckVue from './iCheck.vue';
    import WebSubmitVue from './WebSubmit.vue';
    export default {
        props:[ "theme_info","move_icon", "title" ,"group_id", "branch_id", "target_name", "target_id" ],
        $emits:['updated_fields'],
        watch: {

        },
        components: { 
            "icheck":iCheckVue,
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {
                dragItem: null,
                fieldList:[ ],
                moveIcon:'fa-arrows'
            }
        },
        methods: {

            saveField:function(){
                var vm = this;
                return this.checkFieldList().then(data=>{
                    //console.log(data);
                    let req = {
                        target_name:vm.target_name,
                        target_id:vm.target_id,
                        data:data
                    }
                    return WebRequest2('POST', '/api/group/'+this.group_id+'/settings/set-field-setter', JSON.stringify(req) ).then(resp=>{
                        return resp.json().then(data=>{
                            if(data.status == 1){
                                vm.fieldList = req.data;
                                vm.$emit('updated_fields', vm.fieldList);
                            }
                            return data;
                        });
                    });
                });
            },
            checkFieldList:function(){
                var vm = this;
                var _resolve = null;
                var promise = new Promise(resolve=>{
                    _resolve = resolve
                });
                setTimeout(()=>{
                    let newList = [];
                    let List = vm.$el.querySelectorAll('li');
                    for(var i = 0; i < List.length; i++){
                        let field = List[i].field;
                        field.order_no = (i+1);
                        newList.push( field);
                    }
                    vm.setFieldElements(newList);
                    _resolve(newList);
                }, 50);
                return promise;
            },
            setFieldElements:function(newVal){
                var vm = this;
                vm.fieldList = [];           
                setTimeout(()=>{         
                    vm.fieldList = JSON.parse( JSON.stringify( newVal ) );
                    setTimeout(()=>{
                        let List = vm.$el.querySelectorAll('li');
                        for(var i = 0; i < List.length; i++){
                            List[i].field = vm.fieldList[i];
                        }
                    }, 20);
                }, 10);
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadDragSetting:function(base_fields){
                var vm = this;
                return  WebRequest2('GET', '/api/group/'+vm.group_id+'/settings/get-field-setter?'+vm.queryString({
                    target_id: vm.target_id,
                    target_name: vm.target_name
                })).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    });
                }).then(data=>{


                    //FIXING INTO DEFAULT
                    if(data && data.length > 0 ){
                        //COMPARING AND SETTINGS
                        for(let i = 0; i < base_fields.length; i++){
                            //CHECK IN DATA IF HAS THAT SETTING
                            data.forEach((field)=>{
                                //console.log("triggered", field);
                                if(base_fields[i].name == field.name){
                                    base_fields[i] = field;
                                }
                            });
                        }
                    }

                    //REORDERING BASE ON field order no
                    let sorted = base_fields.sort((a, b) => a.order_no - b.order_no);

                    //Final order_no by unique no
                    let count = 0;
                    sorted.forEach((field)=>{
                        field.order_no = count+1;
                        count++;
                    });

                    vm.setFieldElements(sorted);


                    return sorted;

                });

            },

            loadElementDragging:function(){
                var vm = this;
                const list =  this.$el.querySelector(".draggable-list");

                list.addEventListener("dragstart", e => {
                    const li = e.target.closest("li");
                    if (!li) return;
                    //e.dataTransfer.effectAllowed = "move";
                    //e.dataTransfer.setData("text/plain", "");
                    li.classList.add("dragging");

                    //SET ELEMENT
                });

                list.addEventListener("dragend", e => {
                    const li = e.target.closest("li");
                    if (li) li.classList.remove("dragging");
                    
                });

                list.addEventListener("dragover", e => {
                    e.preventDefault();
                    const dragging = vm.$el.querySelector(".dragging");
                    const afterElement = vm.getDragAfterElement(list, e.clientY);
                    if (!afterElement) {
                        list.appendChild(dragging);
                    } else {
                        list.insertBefore(dragging, afterElement);
                    }

                });
            },
            
            getDragAfterElement:function(container, y) {
                const elements = [...container.querySelectorAll("li:not(.dragging)")];
                let closest = null;
                let closestOffset = Number.NEGATIVE_INFINITY;

                elements.forEach(child => {
                    const box = child.getBoundingClientRect();
                    const offset = y - (box.top + box.height / 2);
                    if (offset < 0 && offset > closestOffset) {
                    closestOffset = offset;
                    closest = child;
                    }
                });

                return closest;
            }

        },
        mounted:function(){ 
            this.loadElementDragging();
            if(this.move_icon){
                this.moveIcon = this.move_icon;
            }
            //this.loadDragSetting(this.base_fields);
        },
        updated:function(){

        }
    }
</script>
