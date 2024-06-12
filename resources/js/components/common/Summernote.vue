<template>
    <div> 
        <div :id="summernoteId"></div>
    </div>
</template>

<script>
    export default {
        props:[ "value", "height", "placeholder", "group_id"],
        components: { 
        },
        data: function () {
            return {    
                summernoteId: 'summernote-'+this._uid,
                content : ''
            }
        },
        watch: {
            value(newValue) {
                var vm = this;
                if(vm.content != newValue){
                    vm.content = newValue; 
                    $('#'+vm.summernoteId).summernote('code', newValue );
                }
            },
        },
        methods: { 
            getFileExt:function(filename){
                return filename.split('.').pop();
            },

        },
        mounted:function(){     
            var vm = this; 
            setTimeout(function(){
                var height = 200;
                if(vm.height){
                    height = vm.height
                }
                var callback_json = {
                    onChange: function(contents, $editable) {
                        vm.content = contents;
                        vm.$emit("input", contents);
                        //document.querySelector('#intro').value = contents;
                    }
                };
                if(vm.group_id > 0 ){
                    //console.log("Hello Uploading..");
                    callback_json.onImageUpload = function(files){
                        //console.log(files, FileList.length); 
                        for( var i= 0; i <files.length; i++)
                        {
                            var file = files[i]; 
                            const formData = new FormData(); 
                            var file_ext = vm.getFileExt(file.name);
                            formData.append('target_name', "summernote-uploads");
                            formData.append('target_id', vm.group_id); 
                            formData.append('file', file);
                            formData.append('file_name', file.name);
                            formData.append('file_type', file.type);
                            formData.append('file_ext', file_ext);

                            WebRequest2('POST', "/api/group/"+vm.group_id+"/file-upload/add", formData, "multipart/form-data").then(resp=>{
                                resp.json().then(data=>{
                                    //console.log(data, vm.summernoteId);
                                    $('#'+vm.summernoteId).summernote('insertImage', data.url);
                                })
                            } );
                            
                        }
                    }
                }


                $('#'+vm.summernoteId).summernote( { 
                    placeholder: vm.placeholder ? vm.placeholder : '',
                    height: height,
                    callbacks: callback_json
                });
                if(vm.value){
                    vm.content = vm.value;
                    $('#'+vm.summernoteId).summernote('code', vm.content);
                }

                setTimeout(function(){
                    document.querySelector('.note-icon-question').closest('button').remove();
                    //document.querySelector('.note-icon-picture').closest('button').remove();
                    //document.querySelector('.note-icon-video').closest('button').remove();
                    //document.querySelector('.note-icon-link').closest('button').remove();
                }, 300);
            }, 100);
        },
        updated:function(){

        }
    }
</script>
