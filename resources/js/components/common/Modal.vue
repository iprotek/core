<template>
    <div class="modal fade" :id="modal" :style="(zIndex? 'z-index:'+ zIndex+';':'') ">
        <div :style="'margin-top:55px; '+(mh ? 'max-height:'+mh+';' : '')+(vw? 'min-width:'+vw+'vw;':'')" :class="(extended_width===true?'modal-dialog modal-lg ':'modal-dialog ') + (is_scrollable === true ? 'modal-dialogue modal-dialog-scrollable' : '') ">
            <div class="modal-content">
                <slot>
                    <div class="modal-header pb-0">
                        <h4 class="modal-title">
                            <slot name="header"></slot>
                        </h4>
                        <button v-if="has_close !== false" type="button" class="close btn btn-outline-danger" @click.prevent="dismiss()">
                            <span class="fa fa-times"></span>
                        </button>
                    </div>
                    <slot name="header-below"></slot>
                    <template v-if="has_success_message">
                        <div class="alert alert-success p-2 border-0 mx-2 mb-0">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <span v-html="message"></span>
                        </div>
                    </template>

                    <div :class="'modal-body pt-0 '+(body_class ? body_class : '')">
                        <slot name="body"></slot>
                    </div>
                    <slot name="body-below"></slot>
                    <template v-if="has_error_message">
                        <div class="alert alert-danger p-2 border-0 mx-2 mb-0">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <span v-html="message"></span>
                        </div>
                    </template>
                    <div class="modal-footer" v-if="hasFooterSlot">
                        <slot name="footer"></slot>
                    </div>
                    <slot name="footer-below"></slot>
                </slot>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['name', 'extended_width', 'prevent', 'body_class', 'vw', "mh", 'zIndex', "has_close", "is_scrollable"],
        data: function () {
            return {
                modal: null,
                message: '',
                has_error_message: false,
                has_success_message: false,
            }
        },
        computed: {
            hasFooterSlot: function() {
                return !!this.$slots['footer']
            }
        },
        methods: {
            show: function () {
                if (this.prevent === true) {
                    $("#" + this.modal).modal({ 
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#" + this.modal).modal('show');
                } else {
                    $("#" + this.modal).modal('show');
                }
            },
            dismiss: function (submit=true) {
                $("#" + this.modal).modal('toggle');
                //console.log("clossed", submit);
                if(submit === true)
                    this.$emit('modal_dismiss', this.modal);
            },
            raised_exit: function () {
                var vm = this;
                $("#" + this.modal).on('hidden.bs.modal', function () {
                    vm.$emit('hidden');
                    // Check for overlaying modal, it will fix the unscrollable issue of overlaying modal
                    $('.modal').each(function () {
                        if ($(this).is(':visible') === true)
                            $('body').addClass('modal-open');
                    });
                });
            },
            show_success: function (message) {
                // Show success message
                this.has_success_message = true;
                this.has_error_message = false;
                this.message = message;
            },
            show_error: function (message) {
                // Show error message
                this.has_success_message = false;
                this.has_error_message = true;
                this.message = message;
            }
        },
        mounted: function () {
            this.modal = (this.name ? this.name : 'modal-'+ this._uid);
            this.$nextTick(function () {
                this.raised_exit();
            });

        }
    }
</script>
