<template>
    <div :id="previewModal" class="preview-image-modal">


        <!-- Modal Content (The Image) -->
        <div class="preview-image-caption p-0" style="height:auto;">
            <label :id="previeImageCaption" v-text="data.name+' ( '+(imageIndex+1)+' / '+data.images.length+' )'"></label>
        </div>
        <div v-if="data.images && data.images.length>0" class="preview-image-modal-content">
            <img style="position:relative; width:100%;" :id="previewImage" :src="data.images[imageIndex].public_link">
            <button @click="imageIndex++" v-if="(imageIndex + 1) < data.images.length" class="btn btn-outline-primary" style="position:fixed; right:2%;top:35%; vertical-align:middle; height:35%;">
                <span class="fa fa-arrow-right"></span>
            </button>
            <button @click="imageIndex--" v-if="imageIndex>0" class="btn btn-outline-primary" style="position:fixed; left:2%;top:35%; vertical-align:middle; height:35%;">
                <span class="fa fa-arrow-left"></span>
            </button>

        </div>
        <div v-else  class="preview-image-modal-content">
            <h2>No preview image available</h2>
        </div>

        <!-- Modal Caption (Image Text) -->
        <!-- The Close Button -->
        <span @click="close" class="preview-image-close" style="position:fixed;">&times;</span>
        <div class="preview-image-caption">
            <p v-text="data.description"></p>
        </div>
    </div> 
</template>

<script>
    export default {
        props:[],
        components: {    
        },
        data: function () {
            return {
                previewModal:'preview-data-modal-'+this._uid,
                previewImage:'preview-data-image-'+this._uid,
                previeImageCaption: 'preview-data-image-caption-'+this._uid,
                imageIndex:0,
                data:{
                    name:'',
                    description:'',
                    images:[]
                }
            }
        },
        methods: {  
            close:function(){
                document.querySelector('#'+this.previewModal).style.display = 'none';
            },
            preview:function(data){
                document.querySelector('#'+this.previewModal).style.display = 'block';
                document.querySelector('#'+this.previewModal).style.zIndex = 2000;
                this.imageIndex = 0;
                this.data = {
                     name: data.name,
                     description: data.description,
                     images: data.images
                }
                //if(data)
                //if(data.images.length>0)
                //    document.querySelector('#'+this.previewImage).src = data.images[0].public_link;
                //document.querySelector('#'+this.previeImageCaption).innerHTML = data.name;
            }
        },
        mounted:function(){   
            setTimeout(function(){
                document.querySelector('body').append(document.querySelector('#'+this.previewModal));
            }, 100);
        }
    }

</script>
