<template>
    <div>
        <div v-if="has_print">
            <button class="btn btn-outline-primary text-nowrap my-1" @click="iframePrint"> <span class="fa fa-print"></span> PRINT</button>
        </div>
        <iframe :scrolling="scrolling == 'yes' ? 'yes': 'no'" class="w-100" :id="iframeId" :name="iframeId" :src="iframeSource" :style="'display:'+(iframeSource == null && !is_post ?'none':'')+';'+(frame_style?frame_style:'')"> 
        </iframe>
    </div>
</template>

<script>
    export default {
        props:[ "src", "has_print", "scrolling",'frame_style', "is_post", "post_data", "print_size", "is_print_url" ],
        components: { 
        },
        data: function () {
            return {    
                iframeId:'iframe-'+this._uid,
                iframeSource:null,
                current_height: 0,
            }
        },
        methods: { 
            printPage:function(url) {
                var printWindow = window.open(url, '_blank');
                printWindow.onload = function () {
                    setTimeout(()=>{
                        printWindow.focus();
                        printWindow.print();
                        printWindow.onafterprint = function () {
                            printWindow.close();
                        };
                    }, 2000);
                };
            },

            iframePrint:function(){
            
                if(this.is_print_url){
                    this.printPage(this.src);
                    return;
                }


                var frame_window =  window.document.querySelector('#'+this.iframeId).contentWindow;
                var style = null;
                var frame_doc = null;
                if(this.print_size){
                    const printSize = `
                    @page { size: `+this.print_size+`; background: white; }
                    body{ 
                        background: white !important;
                        color: black; 
                    }
                    `;
                    frame_doc = frame_window.document;
                    style = frame_doc.createElement('style');
                    style.innerHTML = printSize;
                    frame_doc.head.appendChild(style);
                    //console.log(style);
                }

                frame_window.print();

                if(style && frame_doc){
                    frame_doc.head.removeChild(style);
                    //console.log(style);
                }

            },
            iframeLoaded:function(){
                //console.log("frame loaded");
            },
            resizeIframe: function(iframe) {
                var vm = this; 
                if(iframe.contentWindow && iframe.contentWindow.document){
                    vm.current_height = iframe.contentWindow.document.body.scrollHeight;
                    iframe.height = vm.current_height + "px"; 
                }
            }

        },
        mounted:function(){     
            var vm = this;


            if(!this.is_post){
                window.iframeLoaded = this.iframeLoaded;
                document.querySelector('#'+this.iframeId).onload = function(){
                    vm.resizeIframe(this);
                    vm.$emit('frameloaded', vm.iframeId);
                    
                } 
                this.iframeSource = this.src;
            }
            else{
                var form = document.createElement('form');
                form.setAttribute('method', 'POST');
                var xcsrftoken = document.querySelector('meta[name="csrf-token"]');
                form.setAttribute('X-CSRF-TOKEN', xcsrftoken);
                form.setAttribute('action', this.src);
                form.setAttribute('target', this.iframeId);
                
                for (var key in this.post_data) {
                    if (this.post_data.hasOwnProperty(key)) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', key);
                        input.setAttribute('value', this.post_data[key]);
                        form.appendChild(input);
                    }
                }

                document.body.appendChild(form);
                form.submit();


            }
        },
        updated:function(){

        }
    }
</script>
