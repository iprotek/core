<template>
    <div>
        <modal-view ref="modal" :extended_width="true">
            <template slot="header" >
                LOGO IMAGES
            </template> 
            <template slot="body"> 
                 <file-uploads @files_loaded="files_loaded" :gallery_title="'Logo Image Gallery'"  :value="1" :target_name="'business_logos'"></file-uploads>
            </template>
            <template slot="footer">
                <button type="button" class="btn btn-default mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                <button type="button" class="btn btn-warning" @click="save"  >
                        UPDATE LOGO
                </button>
            </template>
        </modal-view>
        <swal ref="swal_prompt"></swal>
    </div>

</template>

<script> 
    import FileUploadsVue from './FileUploads.vue';
    import SwalVue from './Swal.vue';
    export default {
        props:[  ],
        components: {  
            "file-uploads": FileUploadsVue,
            "swal":SwalVue
        },
        data: function () {
            return {     
            }
        },
        methods: {  
            files_loaded:function(files, is_updated){
                //console.log("Files Loaded",files);
                var current_logo = files.filter(a=>a.is_default)[0];
                if(!current_logo)
                    current_logo = files[0];
                if(is_updated && current_logo){
                    var request = {
                        link: current_logo.link,
                        logo_type: current_logo.file_type
                    };
                    console.log("Request", request);
                    WebRequest2('POST', '/manage/company-details/update-logo-link', JSON.stringify(request)).then(resp=>{
                        resp.json().then(data=>{
                            console.log("Logo Updated",data);
                        })
                    });
                }
            },
            show:function(){
                this.$refs.modal.show(); 
            },
            save:function(){  
                var vm = this;
                this.$refs.swal_prompt.alert(
                        'question',
                        "Update logo from default?", 
                        "Confirm" , 
                        "POST", 
                        "/manage/company-details/update-logo-setting", 
                        '{}'
                    ).then(res=>{
                        if(res.isConfirmed){
                            if(res.value.status == 1){
                                vm.$refs.modal.dismiss();
                            }
                        }
                    });
            },
            savess:function(){
                var service_form = this.$refs.service_form; 
                var request = service_form.getData();
                var result = null;

                


                if(this.is_add){
                  result =  this.$refs.swal_prompt.alert(
                        'question',
                        "Add "+(this.type == 'service'?'Service':'Amenity'), 
                        "Confirm" , 
                        "POST", 
                        "/manage/amenities-and-services/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){

                        }
                    });
                }
                else{
                 result = this.$refs.swal_prompt.alert(
                        'question', 
                        "Update "+(this.type == 'service'?'Service':'Amenity'), 
                        "Confirm" , 
                        "PUT", 
                        "/manage/amenities-and-services/update/"+this.service_id, 
                        JSON.stringify(request)
                    );
                }

            }, 
            loadBackgroundSettings(){ 
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>