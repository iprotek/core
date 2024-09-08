<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card" v-if="data">
                    <div class="card-header text-center" >
                        <span>Ticket#</span>
                        <b v-text="ticketNumbering(data.id)"></b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="text-right" style="width:200px;">Ticket Number</td>
                                    <th class="text-primary" v-text="ticketNumbering(data.id)"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >Title</td>
                                    <th v-text="data.title"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >APP NAME</td>
                                    <th v-text="data.app_name"></th>
                                </tr>
                                <tr>
                                    <td class="text-right"  >APP URL</td>
                                    <th v-text="data.app_url"></th>
                                </tr>
                                <tr>
                                    <td class="text-right align-top"  >
                                        <span>Message </span>
                                     </td>
                                    <th v-text="data.details"></th>

                                </tr>
                                <tr v-if="!data.cater_by_name">
                                    <td colspan="2" class="text-center">
                                        <button class="btn btn-outline-primary btn-xl" @click="caterTicket()"> CATER THIS TICKET? </button>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal>
    </div>
</template>

<script>
    import SwalVue from '../common/Swal.vue';
    export default {
        props:[ "data" ],
        components: { 
            "swal":SwalVue
        },
        data: function () {
            return {    
            }
        },
        methods: { 
            caterTicket:function(){
                var  vm = this;
                var request = {
                    action:'cater'
                };
                this.$refs.swal_prompt.alert(
                    'question',
                    "Cater Now?", 
                    "Confirm" , 
                    "POST", 
                    vm.data.response_url, 
                    JSON.stringify(request)
                ).then(res=>{
                    if(res.isConfirmed){ 
                        var val = res.value; 
                        //if(val && val.status == 1){ 
                        //}
                        setTimeout(()=>{
                            window.location.reload();
                        }, 2000);
                    }
                });
            },
            ticketNumbering:function(number){
                number = number+'';
                if(number.length > 7)
                    return number;


                number = "0000000"+number;
               return  number.substring(number.length - 7);
            },

        },
        mounted:function(){   
            console.log("Data",this. data);  
        },
        updated:function(){

        }
    }
</script>
