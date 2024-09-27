<template>
    <div>
        <modal-view ref="modal" :extended_width="true">
            <template slot="header" >
                TERMS AND CONDITIONS
            </template> 
            <template slot="body">  
                <summernote v-model="html_content" :height="500"></summernote>
            </template>
            <template slot="footer">
                <button type="button" class="btn btn-default mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button>
                <button type="button" class="btn btn-warning" @click="save">
                    UPDATE
                </button>
            </template>
        </modal-view>
        <swal ref="swal_prompt"></swal>
    </div>

</template>

<script> 
    import SummerNoteVue from '../../common/Summernote.vue'
    export default {
        props:[  ],
        components: {   
            "summernote":SummerNoteVue
        },
        data: function () {
            return {     
                html_content:""
            }
        },
        methods: {  
            show:function(){
                this.$refs.modal.show(); 
                this.loadTermsAndConditions();
            },
            save:function(){   
                var vm = this;
                var request = {
                    html: this.html_content
                }
                this.$refs.swal_prompt.alert(
                    'question',
                    "Update terms and conditions?", 
                    "Confirm" , 
                    "POST", 
                    "/manage/company-details/update-terms-and-conditions", 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){
                        //if(res.value.status == 1){
                        //    vm.$refs.modal.dismiss();
                        //}
                    }
                });
            },
            loadTermsAndConditions:function(){
                var vm = this;
                WebRequest2('GET', '/manage/company-details/get-terms-and-conditions').then(resp=>{
                    resp.json().then(data=>{
                        vm.html_content = data.html;
                    });
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