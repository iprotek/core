<template>
    <button @click="copy_text_click()" :title="button_title" :class="'btn btn-sm btn-outline-'+(is_copied ? 'success':base_color)">
        <span v-if="is_copied == false" :class="base_icon" v-text="' '+button_title"></span>
        <label v-else class="p-0 m-0">
            <span class="fa fa-check"> </span> <span v-text="copied_message"></span>
        </label> 
    </button>
</template>

<script>
    export default {
        props:[ "text_to_copy","copied_message","base_icon", "button_title","base_color", "is_dynamic" ],
        components: { 
        },
        data: function () {
            return {    
                is_copied:false,
                navigator: window.navigator
            }
        },
        methods: { 
            copy_text_click:function(){
                //document.execCommand('copy',false, "Copy URL");
                var vm = this;
                if(vm.is_dynamic){

                    vm.$emit('button_clicked', this, vm.text_to_copy);
                    return;
                }




                vm.is_copied = true;
                this.copyText(vm.text_to_copy);
                vm.$emit('button_clicked', vm.text_to_copy);
                setTimeout(()=>{
                    vm.is_copied = false;
                }, 1000);
            },
            copy_text:function(text_to_copy){

                var vm = this;
                vm.is_copied = true;
                this.copyText(text_to_copy);
                setTimeout(()=>{
                    vm.is_copied = false;
                }, 1000);

            },
            copyText:function(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                } else {
                    const textarea = document.createElement("textarea");
                    textarea.value = text;

                    textarea.style.position = "fixed";
                    textarea.style.left = "-999999px";

                    document.body.appendChild(textarea);

                    textarea.focus();
                    textarea.select();

                    try {
                        document.execCommand("copy");
                    } finally {
                        textarea.remove();
                    }

                    return Promise.resolve();
                }
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
