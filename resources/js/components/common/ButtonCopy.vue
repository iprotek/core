<template>
    <button @click="copy_text_click()" :title="button_title" :class="'btn btn-sm btn-outline-'+(is_copied ? 'success':base_color)">
        <span v-if="is_copied == false" :class="base_icon"></span>
        <label v-else class="p-0 m-0">
            <span class="fa fa-check"> </span> <span v-text="copied_message"></span>
        </label> 
    </button>
</template>

<script>
    export default {
        props:[ "text_to_copy","copied_message","base_icon", "button_title","base_color" ],
        components: { 
        },
        data: function () {
            return {    
                is_copied:false
            }
        },
        methods: { 
            copy_text_click:function(){
                //document.execCommand('copy',false, "Copy URL");
                var vm = this;
                vm.is_copied = true;
                navigator.clipboard.writeText(vm.text_to_copy);
                setTimeout(()=>{
                    vm.is_copied = false;
                }, 1000);
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
