<template>
    <div > 
        <div class="card mt-3">
            <div class="card-header text-center text-success">
               <b>  - - Auto-detected linked Projects - - </b>
            </div> 
            <div class="card-body">
                <code v-if="project_items.length == 0">No projects found.</code>
                <div class="input-group text-sm mb-1" v-for="(dItem,dIndex) in project_items"  v-bind:key="'ditem-'+_uid+'-'+dItem.id+'-'+dIndex"> 
                    
                    <span :class="'input-group-text btn success'"  > 
                         <small >project</small>  
                    </span>
                    <label class="input-group-text text-info form-control">
                        <b v-text="dItem.name"></b>
                    </label>  
                      
                    <span  class="input-group-text btn btn-info" title="View" @click="view_data_field(dItem)" >
                        <small class="fa fa-eye"></small>
                    </span>

                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    export default {
        props:[ "data_id" ],
        components: { 
        },
        data: function () {
            return { 
                project_items:[]
            }
        },
        methods: { 
            view_data_field:function(dataItem){
                //$refs.data_form.show(dataItem.id,dataItem)
                window.DataFormView.show(dataItem.id, dataItem);
            },
            loadProjects:function(){
                var vm = this;
                vm.project_items = [];
                WebRequest2('GET', '/manage/projects-monitoring/searches/data/contact-projects/'+this.data_id).then(resp=>{
                    resp.json().then(data=>{
                        vm.project_items = data;
                    });
                })
            }
        },
        mounted:function(){
            this.loadProjects();
        },
        updated:function(){

        }
    }
</script>
