<template>
    <div>
        <div v-if="target_id" class="card my-1">
            <div class="card-header">
                <h5 v-text="galleryTitle"></h5>
            </div>
            <div class="card-body p-0">        
                <table class="w-100 table table-bordered" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <td></td>
                            <td>Name</td>
                            <td>Type</td>
                            <td>Default</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="files.length == 0">
                            <td colspan="6" class="text-center">
                                <code>No Image/File Found</code>
                            </td>
                        </tr>
                        <tr v-for="(targetItem, targetIndex) in files" v-bind:key="'file-'+targetItem.id+'-'+targetIndex">
                            <th v-text="targetIndex+1"></th>
                            <td class="p-1">
                                <img v-if="targetItem.file_type.split('/')[0] == 'image'" class="preview-image" @click="$refs.preview_image.preview($event)" :alt="targetItem.file_name" :src="targetItem.public_link" style="width:80px; heigth:auto;" />
                                <a :href="targetItem.public_link" v-else target="_blank">
                                    <img  class="preview-image" :alt="targetItem.file_name" :src="'/storage/images/0blank.png'" style="width:80px; heigth:auto;" />
                                </a>
                            </td>
                            <td>
                                <small v-if="!targetItem.is_show_full" :title="targetItem.file_name" @click="triggerClick(targetItem, 1);" v-text="limitName(targetItem.file_name)" style="cursor:pointer;"></small>
                                <small v-else @click="triggerClick(targetItem, 2);" :title="targetItem.file_name" v-text="targetItem.file_name" style="cursor:pointer;"></small>
                                <button-copy :base_color="'primary'" :base_icon="'fa fa-link'" :button_title="'Copy LINK'" :copied_message="'Link copied!'" :text_to_copy="targetItem.full_url"></button-copy>
                            
                            </td>
                            <td >
                                <small v-text="targetItem.file_type"></small>
                            </td>
                            <td>
                                <label v-if="targetItem.is_default == 1" class="btn btn-outline-primary m-0 btn-xs">Default</label>
                                <label @click="setDefault(targetItem.id)" v-else class="btn m-0 btn-xs">SET Default</label>
                            </td>
                            <td>
                                <button @click="removeFile(targetItem.id)" class="btn btn-danger btn-xs">
                                    <span class="fa fa-times"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <input  accept="image/*" class="w-100 form-control" type="file" @change="file_changed($event)" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div> 
            <swal2 ref="swal_prompt"></swal2>
            <preview-image ref="preview_image"></preview-image>
        </div>
    </div>
</template>
<script>
    import SwalVue from './Swal.vue';
    import PreviewImageVue from './PreviewImage.vue';
    import ButtonCopy from './ButtonCopy.vue';
    export default {
        props:[ "target_name" , "value", "gallery_title", "group_id"],
        components: { 
            "swal2":SwalVue,
            "preview-image":PreviewImageVue,
            "button-copy": ButtonCopy
        },
        watch: {
            value(newValue) {
                this.target_id = newValue;
                this.load_uploads();
            },
        },
        data: function () {
            return {  
                file_selected:"",
                target_id: 0,
                files:[],
                galleryTitle:'Image Gallery'
            }
        },
        methods: {
            triggerClick:function(item, s){
                var vm = this;
                setTimeout(()=>{
                    if(!item.is_show_full)
                        item.is_show_full = true;
                    else
                        item.is_show_full = false;
                    //console.log(item,s);
                }, 100);
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            imageCompression:function(evt){ 

                var vm = this;
                return new Promise((resolve, reject)=>{
                    const imageInput = evt.target;// document.getElementById('imageInput');
                    if (imageInput.files.length === 0) {
                        alert('Please select an image first.');
                        return;
                    }

                    const file = imageInput.files[0];
                    const reader = new FileReader();

                    reader.onload = function (event) {
                        const img = new Image();

                        img.onload = function () {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // Calculate the desired width and height for the compressed image
                            const maxWidth = 2000; // Adjust this as needed
                            const maxHeight = 2000; // Adjust this as needed
                            let width = img.width;
                            let height = img.height;
                            if(width <= maxHeight && height<=maxHeight){
                                resolve(file);
                            }

                            if (width > maxWidth) {
                                height *= maxWidth / width;
                                width = maxWidth;
                            }

                            if (height > maxHeight) {
                                width *= maxHeight / height;
                                height = maxHeight;
                            }

                            canvas.width = width;
                            canvas.height = height;

                            // Draw the image on the canvas with the desired dimensions
                            ctx.drawImage(img, 0, 0, width, height);

                            // Convert the canvas content back to a compressed image (JPEG format)
                            const compressedImage = canvas.toDataURL('image/jpeg', 0.7); // Adjust the quality (0.0 to 1.0) as needed
                            const compressedFile = vm.dataURItoFile(compressedImage, file.name+'-compressed.jpg');
                            // Here, you can upload the compressedImage to your server using AJAX or any other method.
                            // For demonstration purposes, we'll just log the data URI.
                            resolve(compressedFile);
                            //console.log('Compressed Image:', compressedImage);
                        };

                        img.src = event.target.result;
                    };

                    reader.readAsDataURL(file);
                });
            
            },
            dataURItoFile(dataURI, fileName) {
                const byteString = atob(dataURI.split(',')[1]);
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                return new File([ab], fileName, { type: 'image/jpeg' });
            },
            file_changed:function(evt){ 
                var vm = this;
                const formData = new FormData();
                var file = evt.target.files[0];
                var file_ext = this.getFileExt(file.name);
                
                /*
                console.log(evt.target);
                console.log({
                    target_name: vm.target_name,
                    target_id: vm.target_id,
                    file: file.name,
                    file_type: file.type,
                    file_ext: file_ext,
                    group_id: vm.group_id
                });
                return null; 
                */

                if(file.type.split('/')[0] == "image"){
                    this.imageCompression(evt).then(comp=>{

                        var url = "/manage/file-uploads/add";
                        if(vm.group_id){
                            url = "/api/group/"+vm.group_id+"/file-upload/add";
                        }

                        formData.append('target_name', vm.target_name);
                        formData.append('target_id', vm.target_id); 
                        formData.append('file', comp);
                        formData.append('file_name', file.name);
                        formData.append('file_type', file.type);
                        formData.append('file_ext', file_ext);
                         
                        vm.$refs.swal_prompt.alert(
                                'question', 
                                "Add Image", 
                                "Confirm" , 
                                "POST", 
                                url, 
                                formData,
                                null,
                                "multipart/form-data"
                            ).then(resp=>{ 
                                    evt.target.value = "";
                                    if(resp.isConfirmed )
                                        vm.load_uploads(true); 
                            });
                    });
                }
                else{
                    formData.append('target_name', vm.target_name);
                    formData.append('target_id', vm.target_id); 
                    formData.append('file', file);
                    formData.append('file_name', file.name);
                    formData.append('file_type', file.type);
                    formData.append('file_ext', file_ext);

                    var url = "/manage/file-uploads/add";
                    if(vm.group_id){
                        url = "/api/group/"+vm.group_id+"/file-upload/add";
                    }
                    
                    vm.$refs.swal_prompt.alert(
                            'question', 
                            "Add File", 
                            "Confirm" , 
                            "POST", 
                            url, 
                            formData,
                            null,
                            "multipart/form-data"
                        ).then(resp=>{
                            evt.target.value = "";
                            if(resp.isConfirmed )
                                vm.load_uploads(true); 
                        });
                }
            },
            limitName:function(filename, limitLength = 40){
                if(!filename)
                    return '';
                if(filename.length > limitLength){
                    return filename.substring(0,limitLength)+'...';
                }
                return filename;

            },
            getFileExt:function(filename){
                return filename.split('.').pop();
            },
            value_changed:function(){
                this.$emit("input", this.target_id);
            }, 
            load_uploads:function(is_update =false){
                var vm = this; 
                var req = this.queryString({
                    target_name : this.target_name,
                    target_id : this.target_id
                });
                vm.files = [];
                WebRequest2('GET', '/manage/file-uploads/get-list?'+req).then(resp=>{
                    resp.json().then(data=>{
                        data.data.forEach((item)=>{
                            item.is_show_full = false;
                        });
                        vm.files = data.data;
                        vm.$emit('files_loaded', vm.files, is_update);
                   })
                })
            },
            setDefault:function(file_upload_id){
                //
                var vm = this;
                var req = {
                    target_name: this.target_name,
                    target_id : this.target_id
                }
                WebRequest2('POST', '/manage/file-uploads/set-default/'+file_upload_id, JSON.stringify(req)).then(resp=>{
                    resp.json().then(data=>{
                        if(data.status == 1)
                            vm.load_uploads(true);
                    });
                });
            },
            removeFile:function(file_upload_id){
                var vm = this;
                this.$refs.swal_prompt.alert(
                        'warning', 
                        "DELETE Image", 
                        "Confirm" , 
                        "DELETE", 
                        '/manage/file-uploads/remove/'+file_upload_id
                    ).then(confirm=>{
                        //console.log(confirm);  
                        vm.load_uploads(true); 
                    }); 
            }


        },
        mounted:function(){     
            if(this.gallery_title){
                this.galleryTitle = this.gallery_title;
            }
            if(this.value){
                this.target_id = this.value;
            }
            this.load_uploads();

        }
    }
</script>
