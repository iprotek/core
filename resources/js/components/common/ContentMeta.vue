<template>
    <div v-if="source_id" class="card my-1">
        <div class="card-header">
            <h5 v-text="meta_title"></h5>
        </div>
        <div class="card-body pt-1">
            <div>
                <div class="py-1">
                    <button-copy @button_clicked="copy_clicked" :base_color="'primary'" :base_icon="'fa fa-link'" :button_title="'SAVE AND Copy for FB Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace FB Link'"></button-copy>
                </div>
                <div class="py-1">
                    <button-copy :base_color="'danger'" :base_icon="'fa fa-link'" :button_title="'SAVE AND Copy for Google Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Google Link'"></button-copy>
                </div>
                <div class="py-1">
                    <button-copy :base_color="'info'" :base_icon="'fa fa-link'" :button_title="'SAVE AND Copy for Twitter Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Twitter Link'"></button-copy>
                </div>
                <div class="py-1">
                    <button-copy :base_color="'secondary'" :base_icon="'fa fa-link'" :button_title="'SAVE AND Copy for OThers Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Others Link'"></button-copy>
                </div>
            </div>
            <user-input2 v-model="title" :input_style="'height:40px;'" :placeholder="'Title(30-50 Chars)'"  ></user-input2>
            <small class="text-secondary" v-text=" 'Text left ( '+ (50 - (title ? title.length : 0 )) +' ) - The title to be displayed for marketing'"></small>

            <user-input2 v-model="description" :input_style="'height:40px;'" :placeholder="'Description(120-130 Chars)'" ></user-input2>
            <small class="text-secondary" v-text=" 'Text left ( '+ (130 - (description ? description.length:0))+' ) - The descripton to be displayed for marketing'"></small>

            <user-input2 v-model="keywords" :input_style="'height:40px;'" :placeholder="'Keywords(separate by comma and not so relivant)'" :placeholder_description="'Keywords can be useful in other SEO platform.'" ></user-input2>
            <file-upload v-model="file_target_id" :target_name="'meta-data-image'" :gallery_title="'Meta Data Image'"></file-upload>

            <small class="text-secondary mt-2">SEO XML INDEX FILE FOR SUBMISSION:</small>
            <button class="btn btn-sm btn-outline-primary">
                <span class="fa fa-check"></span> Copy XML
            </button>
            <textarea v-model="seo_xml_index_file" style="min-height:200px;" class="w-100" readonly=""> 
            </textarea>

            <button class="btn btn-outline-primary btn-lg">
                <span class="fa fa-save"></span> SAVE META DETAILS
            </button>
        </div>
    </div>
</template>
<script> 
    import ButtonCopyVue from './ButtonCopy.vue';
    import FileUploadsVue from './FileUploads.vue';
    import UserInput2Vue from './UserInput2.vue';
    export default {
        props:[ "source", "value", "meta_title", "group_id"],
        components: { 
            "file-upload":FileUploadsVue,
            "user-input2":UserInput2Vue,
            "button-copy":ButtonCopyVue
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
                title:'',
                description:'',
                keywords:'',
                seo_xml_index_file:''
            }
        },
        methods: {
            copy_clicked:function(val){
                console.log(val);
            },
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
