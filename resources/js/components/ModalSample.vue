<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'">
            <template slot="header" >
                Contact Tags
            </template> 
            <template slot="body" >     
            
            </template>
            <template slot="footer">
                <div>
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt"></swal> 
    </div>

</template>

<script>    
    
    export default {
        props:[  ],
        components: {   
        },
        data: function () {
            return {        
           }
        },
        methods:{ 
            reset:function(){

            },
            show:function(){ 
                this.$refs.modal.show();
            },

        },
        mounted:function(){      
        },
        updated:function(){

        }
    }
</script>
