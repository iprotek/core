<template>
    <div>
        <div class="card">
            <table class="table m-0">
                <thead class="sticky-top text-nowrap" style="background-color:white;  box-shadow: gray 0px 1px 0px;">
                    <tr>
                        <th style="width: 50px;"></th>
                        <th style="width: 50px;"></th>
                        <th>CONTROL ACCESS - DESCRIPTION</th> 
                        <th :class=" is_default_setting ? 'btn btn-outline-primary btn-lg':''" style="border-radius:0px;">
                            <div v-if="is_default_setting"> 
                                <span class="fa fa-save"></span> SAVE CURRENT ROLE DEFAULTS 
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" />
                        </td>
                        <td colspan="3"> Control1  <small class="text-secondary"> cont1 </small>
                        </td> 
                    </tr> 
                    <tr>
                        <td></td>
                        <td class="text-center ml-2" >
                            <input type="checkbox" />
                        </td>
                        <td colspan="2"> ACCESS 1  <small class="text-secondary"> access desc </small>
                        </td> 
                    </tr> 
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "is_default_setting", "role_id", "app_account_id" ],
        components: { 
        },
        watch: { 
        },
        data: function () {
            return {    
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
        },
        updated:function(){

        }
    }
</script>
