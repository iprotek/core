<template>
    <div>
        <div>
            <button class="btn btn-outline-info btn-sm">
                RESTORE FROM BACKUP
            </button>
            <input type="file" accept=".sql">
        </div>
        <table class="mt-1 table custom-red-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Created</th>
                    <th>is Restored</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
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
