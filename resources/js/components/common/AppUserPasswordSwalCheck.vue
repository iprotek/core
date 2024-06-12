<template>
    <div></div>
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
        alert(){
           return  Swal.fire({
                title: 'Verify Password',
                //icon : 'info',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Check',
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    var req = {
                        password:password
                    }
                    return WebRequest2('POST', '/apps/password-check', req, 'application/json')
                    .then(response => {
                        if (response.ok) 
                            return response.json()
                        //Initiator to stop from closing
                        Swal.showValidationMessage( `ERROR` )
                        response.json().then(a=>{
                            //console.log(a);
                            //throw new Error(a.message);
                            
                            Swal.showValidationMessage(
                            `Request failed: ${this.extractValuesFromJson(a)}`
                            )
                        })
                        .catch(error => { 
                            console.log(error);
                            Swal.showValidationMessage(
                            `Request failed: ${error}`
                            )
                        }); 
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
                });/*.then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                        icon:'success',
                        title: result.value.message
                        }); 
                        this.$emit('result', result);
                    }


                });*/
        },
        extractValuesFromJson(obj) {
            let values = [];

            // Helper function to traverse the object
            function traverse(obj) {
                for (let key in obj) {
                if (typeof obj[key] === 'object' && obj[key] !== null) {
                    traverse(obj[key]); // Recursively traverse nested objects
                } else {
                    values.push(obj[key]+'\n'); // Add the value to the array
                }
                }
            }

            traverse(obj); // Start traversing the JSON object
            return values;
        }

    },
    mounted:function(){     
    } 
}

</script>
