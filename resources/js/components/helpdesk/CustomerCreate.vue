<template>
    <div >
        <div class="rowmt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">CREATE TICKET</div>
                    <div class="card-body">
                        I'm an example component.
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
        props:[  ],
        components: { 
            "swal":SwalVue
        },
        data: function () {
            return {    
            }
        },
        methods: { 

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
