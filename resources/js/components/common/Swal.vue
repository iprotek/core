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
            alert(icon, title, buttonTitle,method, url, data, __error_callback=null,_contentType='application/json'){
                return  Swal.fire({
                            title: title, 
                            icon: icon,  
                            didOpen: () => {
                                const container = Swal.getPopup();//.getHtmlContainer(); // Get the SweetAlert container element
                                //console.log(container); // Logs the container element
                                if(container){
                                    if(!container.parentElement.style.zIndex)
                                        container.parentElement.style.zIndex = 100000;
                                }
                            },
                            showCancelButton: true,
                            confirmButtonText: buttonTitle,
                            showLoaderOnConfirm: true,
                            preConfirm: (deparment_name) => {
                            return WebRequest2(method, url, data, _contentType)
                                .then(response => {
                                    try{
                                    if (response.ok) 
                                        return response.json()
                                    //Initiator to stop from closing
                                    Swal.showValidationMessage( `ERROR` )
                                    return response.json().then(a=>{
                                        //console.log(a);
                                        //throw new Error(a.message);
                                        if(__error_callback){
                                            __error_callback(a);
                                        }
                                        Swal.showValidationMessage(
                                        `Request failed: ${this.extractValuesFromJson(a)}`
                                        )
                                        return a;
                                    })
                                    .catch(error => { 
                                        console.log(error);
                                        Swal.showValidationMessage(
                                        `Request failed: ${error}`
                                        )
                                        if(__error_callback){
                                            __error_callback(error);
                                        }
                                        return error;
                                    }); 
                                    }catch(err){
                                        respose.text().then(data=>{
                                            console.log(data);
                                        });
                                    }
                                }
                                );
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if(result.value.status == 1){
                                    Swal.fire({
                                        icon:'success',
                                        title: result.value.message,  
                                        didOpen: () => {
                                            const container = Swal.getPopup();//.getHtmlContainer(); // Get the SweetAlert container element
                                            //console.log(container); // Logs the container element
                                            if(container){
                                                if(!container.parentElement.style.zIndex)
                                                    container.parentElement.style.zIndex = 100000;
                                            }
                                        }
                                    }); 
                                    this.$emit('result', result);
                                }else{                                    
                                    Swal.fire({
                                        icon:'error',
                                        title: result.value.message,
                                        didOpen: () => {
                                            const container = Swal.getPopup();//.getHtmlContainer(); // Get the SweetAlert container element
                                            //console.log(container); // Logs the container element
                                            if(container){
                                                if(!container.parentElement.style.zIndex)
                                                    container.parentElement.style.zIndex = 100000;
                                            }
                                        }
                                    }); 
                                    this.$emit('result', result);
                                }
                            }
                            return result;
                        });
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
            if(!window.swal_prompt){
                window.swal_prompt = this;
            }
        } 
}
</script>