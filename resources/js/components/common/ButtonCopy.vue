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
                is_copied:false
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
                navigator.clipboard.writeText(vm.text_to_copy);
                vm.$emit('button_clicked', vm.text_to_copy);
                setTimeout(()=>{
                    vm.is_copied = false;
                }, 1000);
            },
            copy_text:function(text_to_copy){

                var vm = this;
                vm.is_copied = true;
                navigator.clipboard.writeText(text_to_copy);
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
