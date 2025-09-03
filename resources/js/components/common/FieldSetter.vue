<template>
    <div>
        <div class="card">
            <div class="card-header">
                FIELDS SETTINGS
            </div>
            <div class="card-body">
                <div  >
                    <ol class="draggable-list" style="width:100%;" >
                        <li v-for="(item) in fieldList" 
                            v-bind:key="'item-field-'+_uid+'-'+item.order_no" draggable="true" 
                        >
                            <div class="input-group input-group-sm">
                                <span class="btn btn-default">
                                    <icheck :checked="item.is_active" @update:checked="(a,b)=>{ item.is_active = a } " />
                                </span>
                                <label class="mb-0 form-control">
                                    {{ item.description }}
                                </label>
                            </div>
                        </li>
                    </ol>
                    <button class="btn btn-outline-primary btn-sm" @click="checkFieldList">
                        <span class="fa fa-save"></span> SAVE
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import iCheckVue from './iCheck.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id", "fields", "target_name", "target_id" ],
        $emits:[],
        watch: {

        },
        components: { 
            "icheck":iCheckVue
        },
        data: function () {
            return {
                dragItem: null,
                fieldList:[ ]
            }
        },
        methods: {
            checkFieldList:function(){
                var vm = this;
                setTimeout(()=>{
                    let newList = [];
                    let List = vm.$el.querySelectorAll('li');
                    for(var i = 0; i < List.length; i++){
                        let field = List[i].field;
                        field.order_no = (i+1);
                        newList.push( field);
                    }
                    vm.setFieldElements(newList);
                }, 50);
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
            loadDragSetting:function(){
                var vm = this;
                const list =  this.$el.querySelector(".draggable-list");
                this.setFieldElements( [
                    {
                        is_active:true,
                        name:"test",
                        order_no:1,
                        description:'Item 1'
                    },
                    {
                        is_active:true,
                        name:"test",
                        order_no:2,
                        description:'Item 2'
                    },
                    {
                        is_active:true,
                        name:"test",
                        order_no:3,
                        description:'Item 3'
                    }
                ]);


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
            this.loadDragSetting();
        },
        updated:function(){

        }
    }
</script>
