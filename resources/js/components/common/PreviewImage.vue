<template>
    <div :id="previewModal" class="preview-image-modal">

        <!-- The Close Button -->
        <span @click="close" class="preview-image-close  mt-4 mr-4">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="preview-image-modal-content" :id="previewImage">

        <!-- Modal Caption (Image Text) -->
        <div :id="previeImageCaption" class="preview-image-caption"></div>
    </div> 
</template>

<script>
    import { getCurrentInstance } from 'vue';
    export default {
        props:[],
        components: {    
        },
        data: function () {
            let _uid = getCurrentInstance().uid;
            return {
                _uid: _uid,
                previewModal:'preview-modal-'+_uid,
                previewImage:'preview-image-'+_uid,
                previeImageCaption: 'preview-image-caption'+_uid
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
