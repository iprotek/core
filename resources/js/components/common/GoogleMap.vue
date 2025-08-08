<template>
    <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')" >
    </div>
</template>

<script>
    export default {
        props:[ "height", "width", "google_map_api_key" ],
        $emits:[],
        watch: { 
        },
        components: { 
        },
        data: function () {
            return {
                google_map_id: 'google-map-'+this._uid
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
