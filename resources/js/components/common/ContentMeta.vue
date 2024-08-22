<template>
    <div v-if="source_id" class="card my-1">
        <div class="card-header">
            <h5 v-text="meta_title"></h5>
        </div>
        <div class="card-body">
            <file-upload v-model="file_target_id" :target_name="'meta-data-image'" :gallery_title="'Meta Data Image'"></file-upload>
        </div>
    </div>
</template>
<script> 
    import FileUploadsVue from './FileUploads.vue';
    export default {
        props:[ "source", "value", "meta_title", "group_id"],
        components: { 
            "file-upload":FileUploadsVue
        },
        watch: {
            value(newValue) {
                this.source_id = newValue;
                //this.load_uploads();
                this.file_target_id = this.group_id+'-'+this.source_id;
            },
        },
        data: function () {
            return {  
                source_id: 0,
                files:[],
                file_target_id: 0,
            }
        },
        methods: {
            triggerClick:function(item, s){
                var vm = this;
                setTimeout(()=>{
                    if(!item.is_show_full)
                        item.is_show_full = true;
                    else
                        item.is_show_full = false;
                    //console.log(item,s);
                }, 100);
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            }, 

        },
        mounted:function(){ 
            this.source_id = this.value;
            this.file_target_id = this.group_id+'-'+this.source_id;
        }
    }
</script>
