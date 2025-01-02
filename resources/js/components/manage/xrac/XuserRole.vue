<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole"></a>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <label> USER </label>
                    </div> 
                    <div class="input-group text-sm">
                        <span class="btn btn-default">
                            <small title="Show" class="fa fa-search text-primary"></small>
                        </span>
                        <input type="text" class="form-control" placeholder="Search user.." />
                    </div> 
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="radio" />
                                </td>
                                <td colspan="2"><a >OR9842</a>
                                    <div>
                                        <small class="text-secondary" >Text Description</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-outline-primary m-2"> 
                        SAVE & SYNC USER XRAC
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <label> ROLES </label>
                    </div> 
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="radio" />
                                </td>
                                <td colspan="2"><a >OR9842</a>
                                    <div>
                                        <small class="text-secondary" >Text Description</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-outline-primary m-2"> 
                        SAVE & SYNC XRAC
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <input type="radio"/> DEFAULT  
                        <input type="radio"/> CUSTOM  
                    </div>
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th style="width: 50px;"></th>
                                <th>CONTROL DESCRIPTION</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" />
                                </td>
                                <td colspan="2"> Control1  <small class="text-secondary"> cont1 </small>
                                </td> 
                            </tr> 
                            <tr>
                                <td></td>
                                <td class="text-center ml-2" >
                                    <input type="checkbox" />
                                </td>
                                <td> ACCESS 1  <small class="text-secondary"> access desc </small>
                                </td> 
                            </tr> 
                        </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[  ],
        components: { 
        },
        data: function () {
            return {    
            }
        },
        methods: { 

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
