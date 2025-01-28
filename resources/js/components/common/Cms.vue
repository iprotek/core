<template>
    <div>
        <div  class="card">
            <div v-if="title" class="card-header">
                {{title}}
            </div> 
                <summernote v-model="content" :is_image_upload="true" :placeholder="'Content'" :is_local="true" :group_id="group_id" :height="height"  />
            <div class="card-footer p-0">
                <button class="btn btn-outline-primary btn-sm" @click="$refs.web_submit.submit()"> 
                    <!--<span class="fa fa-save"></span> SAVE-->
                    <web-submit ref="web_submit" :action="saveContent"  :icon_class="'fa fa-save'"  :label="'Save'"  />
                </button>
                <button class="btn btn-outline-secondary btn-sm" @click="loadContent()"> 
                    <span class="fa fa-save"></span> REVERT
                </button>
            </div>
        </div>
        <swal ref="swal_prompt"></swal>
    </div>
</template>
<script>
    import SwalVue from './Swal.vue';   
 
    import {CmsType} from './enums/common_const';
    import SummernoteVue from './Summernote.vue';
    import WebSubmitVue from './WebSubmit.vue';


    export default { 
        props:[ "group_id" ,"target_name", "title", "target_id", "type", "height"  ],
        components: {  
            "swal":SwalVue,
            "summernote":SummernoteVue,
            "web-submit":WebSubmitVue
        },
        watch: {
            target_id:function(val){
                this.targetId = val;
            }, 
        },
        data: function () {
            return {
                CmsType:CmsType,
                targetId:0,
                content:'',
                prev_content:''
 
            }
        },
        methods: {
            saveContent:function(){
                var req = {
                    type: this.type,
                    content: this.content,
                    target_name: this.target_name,
                    target_id: this.target_id,
                };
                return WebRequest2('POST','/api/group/'+this.group_id+'/cms/save', JSON.stringify(req)).then(resp=>{
                   return  resp.json().then(data=>{
                        if(!resp.ok){
                            return;
                        }
                        return data;
                    })
                });
            },

            loadContent:function(){
                var vm = this;
                var req = {
                    type: this.type,
                    target_name: this.target_name,
                    target_id: this.target_id,
                };
                WebRequest2('POST','/api/group/'+this.group_id+'/cms/get-content', JSON.stringify(req)).then(resp=>{
                   return  resp.json().then(data=>{
                        if(data){
                            vm.content = data.content;
                        }
                    })
                });
            }
        },
        mounted:function(){   
            this.loadContent();
        }
    }
</script>
