<template>
    <div>
        <div v-if="source_id" class="card my-1">
            <div class="card-header">
                <h5 v-text="meta_title"></h5>
            </div>
            <div class="card-body pt-1">
                <div class="btn-group w-100">
                    <button type="button" :class="'btn '+( view == 'current-meta' ? 'btn-primary':'btn-default')" @click="view='current-meta';loadInfo();">CURRENT META INFO</button> 
                    <button type="button" :class="'btn '+( view == 'custom-meta' ? 'btn-primary':'btn-default')" @click="view='custom-meta'">SET CUSTOM META</button>
                </div>
                <div class="row">
                    <div class="col-sm-12" v-if="meta_data_id && 'custom-meta' != view">
                        <div class="py-1 my-2">
                            
                            <button-copy :is_dynamic="true" @button_clicked="copy_fb_clicked" :base_color="'primary my-1'" :base_icon="'fa fa-link'" :button_title="'Click to Copy for FB Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace FB Link'"></button-copy>
                        
                            <button-copy :is_dynamic="true" @button_clicked="copy_google_clicked" :base_color="'danger my-1'" :base_icon="'fa fa-link'" :button_title="'Click to Copy for Google Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Google Link'"></button-copy>
                        
                            <button-copy :is_dynamic="true" @button_clicked="copy_twitter_clicked" :base_color="'info my-1'" :base_icon="'fa fa-link'" :button_title="'Click to Copy for Twitter Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Twitter Link'"></button-copy>
                        
                            <button-copy :is_dynamic="true" @button_clicked="copy_other_clicked" :base_color="'secondary my-1'" :base_icon="'fa fa-link'" :button_title="'Click to Copy for OThers Link'" :copied_message="'Link Copied!'" :text_to_copy="'Replace Others Link'"></button-copy>
                        </div>
                    </div> 
                </div>
                <div v-if="'custom-meta' == view">
                    <div class="row">
                        <div class="col-sm-12 pt-2">
                            <button class="btn btn-outline-primary btn-lg float-right" @click="save">
                                <span class="fa fa-save"></span> SAVE META DETAILS
                            </button>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-sm-12">
                            <user-input2 v-model="title" :input_style="'height:40px;'" :placeholder="'Title(30-50 Chars)'"  ></user-input2>
                            <small class="text-secondary" v-text=" 'Text left ( '+ (50 - (title ? title.length : 0 )) +' ) - The title to be displayed for marketing'"></small>

                            <user-input2 v-model="description" :input_style="'height:40px;'" :placeholder="'Description(120-130 Chars)'" ></user-input2>
                            <small class="text-secondary" v-text=" 'Text left ( '+ (130 - (description ? description.length:0))+' ) - The descripton to be displayed for marketing'"></small>

                            <user-input2 v-model="keywords" :input_style="'height:40px;'" :placeholder="'Keywords(separate by comma and not so relivant)'" :placeholder_description="'Keywords can be useful in other SEO platform.'" ></user-input2>
                        </div>
                    </div>
                    <file-upload v-if="source && source_id" v-model="file_target_id" :target_name="'meta-data-image'" :gallery_title="'Meta Data Image'" :group_id="group_id" ></file-upload>
                    <small class="text-secondary mt-2">SEO XML SITEMAP FOR SUBMISSION:</small>
                    <button-copy :base_color="'primary'" :base_icon="'fa fa-sitemap'" :button_title="'Copy XML'" :copied_message="'XML Copied!'" :text_to_copy="seo_xml_index_file"></button-copy>
                    <textarea v-model="seo_xml_index_file" style="min-height:250px;" class="w-100" readonly=""> 
                    </textarea>
                </div>
                <div v-else>
                    <h3 class="text-center text-danger" v-if="meta_data_id == 0"> -- THERE IS NO CURRENTLY META ON THIS -- </h3>  
                    <div v-else> 
                        <table class="table-bordered w-100">
                            <tr>
                                <td style="width:120px;" class="text-right"><span>Title:</span></td>
                                <td class="px-2"><label class="m-0 text-primary"  v-text="title"></label></td>
                            </tr>
                            <tr>
                                <td class="text-right"><span>Description:</span></td>
                                <td class="px-2"><label class="m-0 text-info"  v-text="description"></label></td>
                            </tr>
                            <tr>
                                <td class="text-right"><span>Keywords:</span></td>
                                <td class="px-2"><label class="m-0 text-danger"  v-text="keywords"></label></td>
                            </tr>
                            <tr>
                                <td class="text-right text-nowrap align-top"><span>Preview Image:</span></td>
                                <td><img v-if="image_url" :src="image_url" style="max-width:800px; width:100%;" /></td>
                            </tr>
                        </table>  
                    </div>
                </div>
            </div>
            <swal ref="swal_prompt"></swal> 
        </div>
    </div>
</template>
<script> 
    import ButtonCopyVue from './ButtonCopy.vue';
    import FileUploadsVue from './FileUploads.vue';
    import SwalVue from './Swal.vue';
    import UserInput2Vue from './UserInput2.vue';
    export default {
        props:[ "source", "value", "meta_title", "group_id"],
        components: { 
            "file-upload":FileUploadsVue,
            "user-input2":UserInput2Vue,
            "button-copy":ButtonCopyVue,
            "swal":SwalVue
        },
        watch: {
            value(newValue) {
                this.source_id = newValue;
                //this.load_uploads();
                this.file_target_id = this.group_id+'-'+this.source_id+'-'+this.source;
            },
        },
        data: function () {
            return {  
                view:"current-meta",
                meta_data_id:0,
                source_id: 0,
                files:[],
                file_target_id: 0,
                title:'',
                description:'',
                keywords:'',
                image_url:'',
                seo_xml_index_file:`<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://www.example.com/</loc>
    <lastmod>2024-08-23</lastmod>
    <changefreq>monthly</changefreq>
    <priority>1.0</priority>
  </url
</urlset>`
            }
        },
        methods: {
            copy_fb_clicked:function(el, val){
                //console.log(val);
                this.$emit('copy_button_link_click', el, {
                    title: this.title,
                    description: this.description,
                    keywords: this.keywords,
                    link_source: 'facebook'
                } );
            },
            copy_google_clicked:function(el, val){
                //console.log(val);
                this.$emit('copy_button_link_click', el, {
                    title: this.title,
                    description: this.description,
                    keywords: this.keywords,
                    link_source: 'google'
                } );
            },
            copy_twitter_clicked:function(el, val){
                //console.log(val);
                this.$emit('copy_button_link_click', el, {
                    title: this.title,
                    description: this.description,
                    keywords: this.keywords,
                    link_source: 'twitter'
                } );
            },
            copy_other_clicked:function(el, val){
                //console.log(val);
                this.$emit('copy_button_link_click', el, {
                    title: this.title,
                    description: this.description,
                    keywords: this.keywords,
                    link_source: 'other'
                } );
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
            save:function(){
                var  vm = this;
                var request = {
                    source_id: this.source_id,
                    source: this.source,
                    title: this.title,
                    description: this.description,
                    meta_data:{
                        title : this.title,
                        description: this.description,
                        keywords: this.keywords
                    }
                }
                this.$refs.swal_prompt.alert(
                    'question', 
                    "SET META DATA CUSTOM?", 
                    "Confirm" , 
                    "POST", 
                    "/api/group/"+this.group_id+"/meta-data/add", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){
                            vm.$emit('content_updated');
                        }
                    }
                }); 
            },
            loadInfo:function(){
                var vm = this;
                vm.title = "";
                vm.description = "";
                vm.keywords = "";
                vm.meta_data_id = 0; 
                vm.image_url = '';
                WebRequest2('GET', '/api/group/'+this.group_id+'/meta-data/get-info/'+this.source_id+'?&source='+this.source).then(resp=>{
                    resp.json().then(data=>{
                        if(!data) return;

                        vm.meta_data_id = data.id;
                        if(data.meta_data){

                            vm.title = data.meta_data.title;
                            vm.description = data.meta_data.description;
                            vm.keywords = data.meta_data.keywords;

                        }
                        if(data.meta_image){
                            vm.image_url = data.meta_image.full_url;
                        }
                    });
                });
            }

        },
        mounted:function(){ 
            this.source_id = this.value;
            this.file_target_id = this.group_id+'-'+this.source_id+'-'+this.source;
            this.loadInfo();
        }
    }
</script>
