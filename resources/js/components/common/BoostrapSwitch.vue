<template>  
    <input :id="switchID" type="checkbox" data-bootstrap-switch :data-off-color="off_color" :data-on-color="on_color">
</template>
<script> 
    
    export default {
        props:["off_color","on_color"],
        components: {    
        },
        data: function () {
            return {
                switchID:'switch-id-'+this._uid   
            }
        },
        methods: { 
            setOn:function(check){
                $('#'+this.switchID).bootstrapSwitch('state', check);
            },
            setState:function(check){
                $('#'+this.switchID).bootstrapSwitch('state', check);
            },
            getState:function(){
                return $('#'+this.switchID).bootstrapSwitch('state');
            }

        },
        mounted:function(){     
            var vm = this;
            setTimeout(()=>{
                $('#'+this.switchID).bootstrapSwitch('state', false);
            }, 100);
        }
    }

</script>
