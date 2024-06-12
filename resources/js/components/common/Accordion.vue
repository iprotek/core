<template> 
    <div class="accordion-item" >
        <h2 class="accordion-header" @click="accordion_click();$emit('click');">
            <button :class="'accordion-button '+(is_show? '':'collapsed')" type="button" data-bs-toggle="collapse" :data-bs-target="'#'+collapseId" :aria-expanded="is_show?'true':''" :aria-controls="'#'+collapseId">
              <slot name="header"></slot>
            </button>
        </h2>
        <div :id="collapseId" :class="'accordion-collapse collapse '+(is_show?  'show':'')" :aria-labelledby="'headingOne'" :data-bs-parent="'#'+accordionContainerId">
        <div class="accordion-body">
            <slot name="body"></slot>
            <!--<strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
            -->
        </div>
        </div>
    </div> 
</template>

<script>
    export default {
        props:[ "accordionContainerId","is_show" ],
        components: { 
        },
        data: function () {
            return {    
                collapseId: 'collapse'+this._uid
            }
        },
        methods: { 
            accordion_click:function(){
                var vm = this;
                var acc = document.querySelector('#'+this.collapseId);
                //console.log(acc);
                if(acc){
                    if(acc.classList.contains('show'))
                    { 
                        acc.classList.remove('show');
                    }
                }
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
