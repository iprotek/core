<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label> PAY SMS SENT HISTORY</label>
                    </div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "group_id", "branch_id", "type", "sys_notify_schedule_sms_triggers_id" ],
        $emits:[],
        watch: { 
            sys_notify_schedule_sms_triggers_id:function(val){

            }
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
