<template>
    <label v-bind:class="{ block: !inline }" class="font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
    </label>
</template>

<script>
    export default {
        props: ['value', 'inline'],
    }
</script>
