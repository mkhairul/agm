<template>
    <textarea row="10" class="form-textarea rounded-md shadow-sm" :value="value" @input="$emit('input', $event.target.value)" ref="textarea" />
</template>

<script>
    export default {
        props: ['value'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>