<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Import Data</div>
                    <div class="card-body"> 
                        <button v-if="has_close" class="btn btn-outline-secondary" @click="$emit('close_file_import_data')">
                            <span class="fa fa-arrow-left"></span>  CLOSE 
                        </button>

                        <button @click="selected_status_id = -1" :class="'btn btn'+(selected_status_id == -1 ? '':'-outline')+'-primary btn-sm'"> ALL </button>
                        <button @click="selected_status_id = 0" :class="'btn btn'+(selected_status_id == 0 ? '':'-outline')+'-warning btn-sm'"> PENDING </button>
                        <button @click="selected_status_id = 1" :class="'btn btn'+(selected_status_id == 1 ? '':'-outline')+'-success btn-sm'"> SUCCESS </button>
                        <button @click="selected_status_id = 2" :class="'btn btn'+(selected_status_id == 2 ? '':'-outline')+'-danger btn-sm'"> FAILED </button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <th>
                                <small>ReF#</small>
                            </th>
                            <th>
                                <small>BatchID</small>
                            </th>
                            <th>
                                <small>TargetField</small>
                            </th>
                            <th>
                                <small>Data</small>
                            </th>
                            <th>
                                <small>Status</small>
                            </th>
                            <th>
                                <small>Status Info</small>
                            </th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "file_import_batch_id", "status_id", "has_close" ],
        components: { 
        },
        watch: { 
        },
        data: function () {
            return { 
                selected_status_id: -1,
                current_page:1,
                pageData:null,
                importDataList:[],
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
            loadFileImportList:function(){
                
            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
