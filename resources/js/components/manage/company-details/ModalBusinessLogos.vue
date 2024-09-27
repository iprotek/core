<template>
    <div>
        <modal-view ref="modal" :extended_width="true">
            <template slot="header" >
                LOGO IMAGES
            </template> 
            <template slot="body"> 
                 <file-uploads :gallery_title="'Logo Image Gallery'"  :value="1" :target_name="'business_logos'"></file-uploads>
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
    import FileUploadsVue from '../../common/FileUploads.vue';
    export default {
        props:[  ],
        components: {  
            "file-uploads": FileUploadsVue
        },
        data: function () {
            return {     
            }
        },
        methods: {  
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