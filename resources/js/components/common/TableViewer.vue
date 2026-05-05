<template>
    <span>
        <label class="mt-4">{{title}}</label>
        <div v-if="tableData.length > 0">
            <table class="table-bordered" style="min-width:250px;">
                <template  v-for="(row, i) in tableData" v-bind:key="'table-row-'+_uid+'-'+i">
                    <tr v-if="i == 0">
                        <template v-for="(data, d) in row" v-bind:key="'table-data-'+_uid+'-'+d">
                            <th> <small><b> {{data}} </b></small> </th>
                        </template>
                    </tr>
                    <tr v-else>
                        <template v-for="(data, d) in row" v-bind:key="'table-data-'+_uid+'-'+i+'-'+d">
                            <td v-if="data === null"> <small class="text-secondary"> <i>NULL</i> </small> </td>
                            <td v-else> 
                                <small v-if="status" v-html="data"> </small> 
                                <code v-else-if="tableData[0][d] == 'message'"> <small v-html="data"> </small> </code> 
                                 <small v-else v-html="data"> </small> 
                            </td>
                        </template>
                    </tr>
                </template>
            </table>
        </div>
        <div v-else>
            <code>NO RESULT</code>
        </div>
    </span>
</template>

<script>
    import { getCurrentInstance } from 'vue';
    export default {
        props:[ "title", "table" ],
        $emits:[],
        watch: { 
        },
        components: { 
        },
        data: function () {
            let _uid = getCurrentInstance().uid;
            return {
                _uid:_uid,
                tableData:[],
                status:1

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
            this.status = this.table.status;
            this.tableData = this.table.data;
        },
        updated:function(){

        }
    }
</script>
