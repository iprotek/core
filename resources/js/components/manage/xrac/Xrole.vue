<template>
    <div>
        <a class="btn btn-outline-primary mb-2" href="/manage/xrac/xrole/user-role-access"> USER ROLES </a>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <role-list :is_default_setting="true" />
            </div>
            <div class="col-md-8">
                <div class="card">
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
    import RoleListVue from './RoleList.vue';
    export default {
        props:[  ],
        components: {
            "role-list":RoleListVue
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
