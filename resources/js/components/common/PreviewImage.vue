<template>
    <div :id="previewModal" class="preview-image-modal">

        <!-- The Close Button -->
        <span @click="close" class="preview-image-close">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="preview-image-modal-content" :id="previewImage">

        <!-- Modal Caption (Image Text) -->
        <div :id="previeImageCaption" class="preview-image-caption"></div>
    </div> 
</template>

<script>
    export default {
        props:[],
        components: {    
        },
        data: function () {
            return {
                previewModal:'preview-modal-'+this._uid,
                previewImage:'preview-image-'+this._uid,
                previeImageCaption: 'preview-image-caption'+this._uid
            }
        },
        methods: {  
            close:function(){
                document.querySelector('#'+this.previewModal).style.display = 'none';
            },
            preview:function(evt){
                document.querySelector('#'+this.previewModal).style.display = 'block';
                document.querySelector('#'+this.previewModal).style.zIndex = 2000;
                document.querySelector('#'+this.previewImage).src = evt.target.src;
                document.querySelector('#'+this.previeImageCaption).innerHTML = evt.target.alt;
            }
        },
        mounted:function(){   
            setTimeout(function(){
                document.querySelector('body').append(document.querySelector('#'+this.previewModal));
            }, 100);
        }
    }

</script>
