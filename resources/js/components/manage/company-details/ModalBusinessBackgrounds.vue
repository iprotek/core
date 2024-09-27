<template>
    <div>
        <modal-view ref="modal" :extended_width="true">
            <template slot="header" >
                BACKGROUND IMAGES
            </template> 
            <template slot="body"> 
                <!--
                <div class="mb-3 row" v-if="service_id != 0">
                    <label class="col-sm-4 col-form-label text-right">Order#:</label>
                    <div class="col-sm-8">
                        <input type="number" v-model="order_no" class="form-control">
                    </div>
                </div>
                --> 
                <div> 
                    SHUFFLE BACKGROUND
                    <switch2 v-model="allow_shuffle" :off_color="'danger'" :on_color="'success'"></switch2>
                </div>
                <user-input2 v-if="allow_shuffle" v-model="shuffle_duration" :disabled="false" :input_style="'height:35px;'" :placeholder="'Shuffle Duration(seconds)'" :placeholder_description="'Duration'" :placeholder_focus_color="'blue'" :placeholder_style="''" :type="'text'"></user-input2>
                <file-uploads :gallery_title="'Background Image Gallery'"  :value="1" :target_name="'business_backgrounds'"></file-uploads>
                
            </template>
            <template slot="footer">
                <button type="button" class="btn btn-default mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                 <button type="button" class="btn btn-warning" @click="save"  >
                        UPDATE
                </button>
                
            </template>
        </modal-view>
        <swal ref="swal_prompt"></swal>
    </div>
</template>

<script> 
    import BoostrapSwitch2Vue from '../../common/BoostrapSwitch2.vue';
    import UserInput2Vue from '../../common/UserInput2.vue';
    import FileUploadsVue from '../../common/FileUploads.vue';
    export default {
        props:[  ],
        components: {  
            "switch2" : BoostrapSwitch2Vue,
            "user-input2": UserInput2Vue,
            "file-uploads": FileUploadsVue
        },
        data: function () {
            return {    
                allow_shuffle: false,
                shuffle_duration: 120,
                is_add:true,
                service_id:0,
                type :'service'
            }
        },
        methods: {  
            show:function(){
                this.$refs.modal.show();
                this.loadBackgroundSettings();
            },
            save:function(){
                var req = {
                    allow_shuffle: this.allow_shuffle ? 1:0,
                    shuffle_duration: this.shuffle_duration * 1
                }
                console.log(req);
                this.$refs.swal_prompt.alert(
                        'question',
                        "Update Background Settings", 
                        "Confirm" , 
                        "POST", 
                        '/manage/company-details/update-background-setting', 
                        JSON.stringify(req)
                    ).then(res=>{
                        if(res.isConfirmed){

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
                var vm = this;
                WebRequest2('GET', '/manage/company-details/get-background-setting').then(resp=>{
                    resp.json().then(data=>{
                        vm.allow_shuffle = data.allow_shuffle == 1;
                        vm.shuffle_duration = data.shuffle_duration ? data.shuffle_duration: 120;
                    });
                });
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>