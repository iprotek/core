<template>
    <div>
        <div class="card" v-if="source_id">
            <div class="card-header" v-text="title">    </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-2" v-if="is_add_edit == false">
                    <div class="input-group text-sm input-group-sm">
                        <span class="input-group-text btn-outline-warning" title="Add list item">
                            <small class="fa fa-edit"></small>
                        </span> 
                        <span class="input-group-text btn btn-danger bg-red text-white">
                            <small class="fa fa-minus"></small>
                        </span> 
                        <label class="input-group-text form-control "> TITLE NAME ~ self::$data->prop   </label> 
                        <small class="input-group-text">[REF:MODEL]</small> <!----> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-down"></small>
                        </span> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-up"></small>
                        </span> 
                        <span title="Move" class="input-group-text btn btn-info">
                            <small class="fa fa-arrows-alt"></small>
                        </span>
                    </div>
                    <div class="input-group text-sm input-group-sm">
                        <span class="input-group-text btn-outline-warning" title="Add list item">
                            <small class="fa fa-edit"></small>
                        </span> 
                        <span class="input-group-text btn btn-danger bg-red text-white">
                            <small class="fa fa-minus"></small>
                        </span> 
                        <label class="input-group-text form-control ">Contractors - DEFAULT: YES NO</label> 
                        <small class="input-group-text">[INPUT:TEXT]</small> <!----> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-down"></small>
                        </span> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-up"></small>
                        </span> 
                        <span title="Move" class="input-group-text btn btn-info">
                            <small class="fa fa-arrows-alt"></small>
                        </span>
                    </div>
                    <div class="input-group text-sm input-group-sm">
                        <span class="input-group-text btn-outline-warning" title="Add list item">
                            <small class="fa fa-edit"></small>
                        </span> 
                        <span class="input-group-text btn btn-danger bg-red text-white">
                            <small class="fa fa-minus"></small>
                        </span> 
                        <label class="input-group-text form-control ">TITLE NAME ~ self::$variable</label> 
                        <small class="input-group-text">[REF:VAR]</small> <!----> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-down"></small>
                        </span> 
                        <span class="input-group-text btn btn-info">
                            <small class="fa fa-arrow-up"></small>
                        </span> 
                        <span title="Move" class="input-group-text btn btn-info">
                            <small class="fa fa-arrows-alt"></small>
                        </span>
                    </div>
                </ul>
                <ul class="list-group list-group-flush" v-if="is_add_edit == false"> 
                    <button class="btn btn-sm btn-outline-primary" @click="is_add_edit=true">
                        <span class="fa fa-plus"></span> ADD FIELD
                    </button>
                </ul>
                <ul class="list-group list-group-flush" v-else>
                    <add-delegate-data @cancel="cancel_add_edit" v-model="new_data" :source_id="source_id" :source_name="source_name" :view_scale="'80%'" ></add-delegate-data>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import AddEditSourceDataVue from './AddEditSourceData.vue';
    export default {
        props:[ "value", "source_name", "title" ],
        watch: {
            value(newValue) {
                this.source_id = newValue;
            },
        },
        components: { 
            "add-delegate-data":AddEditSourceDataVue
        },
        data: function () {
            return {
                is_add_edit:false,
                new_data:0,
                source_id:0
            }
        },
        methods: { 
            cancel_add_edit:function(){
                this.is_add_edit = false;
            }
        },
        mounted:function(){     
            this.source_id = this.value
        },
        updated:function(){

        }
    }
</script>
