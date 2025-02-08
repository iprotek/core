<template>
    <div >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Devices</div>
                    <div class="card-body">
                        <button></button>
                        <table class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <small> # </small>
                                    </th>
                                    <th>
                                        <small> Name </small>
                                    </th>
                                    <th>
                                        <small> Type </small>
                                    </th>
                                    <th>
                                        <small> Host </small>
                                    </th>
                                    <th>
                                        <small> User </small>
                                    </th>
                                    <th>
                                        <small> Port </small>
                                    </th>
                                    <th>
                                        <small> Branches </small>
                                    </th>
                                    <th >
                                        <small> Is Active </small>
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "set_branch_source", "set_branch_source_url" ],
        components: { 
        },
        data: function () {
            return { 
                branch_source:"branches",
                branch_source_url:""
            }
        },
        methods: { 

            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
        },
        mounted:function(){ 
            if(this.set_branch_source){
                this.branch_source = this.set_branch_source;
            } 
        },
        updated:function(){

        }
    }
</script>
