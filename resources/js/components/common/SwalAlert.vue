<template>
    
</template>
<script>
export default {
     
        props:[ ],
        components: {  
        },
        data: function () {
            return {     
            }
        },
        methods: {  
            confirm(question, confirmText="Yes", denyText="Cancel", showDenyButton=false, showCancelButton=true){ 
                return Swal.fire({
                    title: question,
                    showDenyButton: showDenyButton,
                    icon:"question",
                    showCancelButton: showCancelButton,
                    confirmButtonText: confirmText,
                    denyButtonText: denyText
                });
            },
            error( message){ 
                return Swal.fire({
                    title: message,
                    icon:'error',
                    showDenyButton:false,
                    showCancelButton:false,
                    confirmButtonText: "OK"
                });
            },
        },
        mounted:function(){     
        } 
}
</script>