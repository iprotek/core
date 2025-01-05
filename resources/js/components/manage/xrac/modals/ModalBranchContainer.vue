<template>
    <div> 
        <modal-branch-list  ref="modalBranch"/>
    </div>
</template>

<script>
    import ModalBranchListVue from './ModalBranchList.vue';
    export default {
        props:[  ],
        components: {
            "modal-branch-list":ModalBranchListVue
        },
        watch: { 
        },
        data: function () {
            return {    
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

        },
        mounted:function(){     
            window.ModalBranchView = this.$refs.modalBranch;
        },
        updated:function(){

        }
    }
</script>
