<template>
    <small v-if="is_small">
        <span v-if="errors[field]!==undefined" class="help-block text-red">
            <span v-for="(value,key) in errors[field]" v-bind:key="'item-key'+key" v-html="value"></span><br/>
        </span>
    </small>
    <span v-else-if="errors[field]!==undefined" class="help-block text-red">
        <span v-for="(value,key) in errors[field]" v-bind:key="'item-key'+key" v-html="value"> </span><br/>
    </span>
</template>
<script>
    export default {
        props: {
            errors: {
                required: true,
                default: function () {
                    return []
                }
            },
            field: {
                required: true,
                default: function () {
                    return '';
                }
            },
            is_small:true
        }

    }

</script>
