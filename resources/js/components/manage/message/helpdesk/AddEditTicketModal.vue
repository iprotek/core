<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="60">
            <template slot="header" >
                <h3 v-if="id == 0">
                    SUBMIT HELPDESK
                </h3>
                <h3 v-else>
                    HELPDESK INFO
                </h3>
            </template> 
            <template slot="body" >     
                <div class="text-right mt-2">
                    <button class="btn btn-outline-primary">
                        CHATS (0) <span class="fa fa-arrow-right"></span>
                    </button>
                </div>
                <div>
                    <user-input2 v-model="title" :input_style="'height:40px;'" :placeholder="'Title'" :placeholder_description="'title of the help for helpdesk'"></user-input2>
                
                    <summernote v-model="description" :height="'300px'" :placeholder="'Descriptive Details'"  :is_local="false"  ></summernote>
                
                </div>
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
    import SummernoteVue from '../../../common/Summernote.vue';
    import UserInput2Vue from '../../../common/UserInput2.vue';
    export default {
        props:[  ],
        components: {
            "user-input2":UserInput2Vue,
            "summernote":SummernoteVue
        },
        data: function () {
            return {
                id:0,
                title:'',
                description:'',
                ticket_type:'system-support'
           }
        },
        methods:{ 
            reset:function(){
                this.id = 0;
                this.title = '';
                this.description = '';
            },
            show:function(id = 0){ 
                this.reset();
                this.id = id;
                this.$refs.modal.show();
            },

        },
        mounted:function(){      
            window.add_edit_ticket_modal = this;
        },
        updated:function(){

        }
    }
</script>
