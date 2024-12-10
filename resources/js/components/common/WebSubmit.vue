<template>
    <span :class="el_class+' '+add_class" @click="submit">
        <i v-if="is_submit" class="fa fa-spinner fa-pulse"></i>
        <i v-else-if="status == 2" class="fa fa-times text-danger"></i>
        <i v-else-if="status == 1" class="fa fa-check text-success"></i>
        <span v-if="is_submit"> Submitting </span>
        <span v-else-if="status >= 1" v-text="message"></span>
        <span v-else v-html="label">
        </span>
    </span>
</template>

<script>
    export default {
        props:[ "el_class", "label","timeout", "action" ],
        components: { 
        },
        data: function () {
            return {
                is_submit:false,
                status:0, // 0-N/A, 1-Success, 2-failed,
                message:'',
                set_timeout:3000,
                add_class:''
            }
        },
        methods: { 
            submit:function(){
                var vm = this;
                if(vm.is_submit || vm.status > 0 || !vm.action){
                    return;
                }
                vm.add_class = 'disabled';
                vm.is_submit =  true;

                var result = vm.action();
                

                result.then(data=>{
                    
                    vm.is_submit = false;
                    if(!data){
                        vm.status = 2;
                        vm.message = "Error: No Result";
                    }
                    else if(data.status == 1){
                        vm.status = 1;
                        vm.add_class = 'disabled text-success';
                        vm.message = data.message;
                    }else { //if(data.status != null){
                        vm.status = 2;
                        vm.add_class = 'disabled text-danger';
                        vm.message = data.message ? data.message : "Validation Error";
                    }/*else{
                        vm.status = 2;
                        vm.add_class = 'disabled text-danger';
                        vm.message = `Request failed: ${vm.extractValuesFromJson(data)}`;
                    }*/
                    setTimeout(()=>{
                        vm.status = 0;
                        vm.add_class = '';
                    }, vm.set_timeout);




                });

            },
            extractValuesFromJson:function(obj) {
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
            if(this.timeout*1>0){
                this.set_timeout = this.timeout;
            }
        },
        updated:function(){

        }
    }
</script>
