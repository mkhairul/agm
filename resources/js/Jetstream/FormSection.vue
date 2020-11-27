<template>
    <div class=" md:gap-6" v-bind:class="{ 'md:grid md:grid-cols-3': !single }">
        <jet-section-title v-if="!single">
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </jet-section-title>

        <div class="mt-5 md:mt-0" v-bind:class="{ 'md:col-span-2': !single }">
            <form @submit.prevent="$emit('submitted')">
                <div class="shadow overflow-hidden" v-bind:class="{ 'sm:rounded-md': !rounded }">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="" v-bind:class="{ 'grid grid-cols-6 gap-6': !single }">
                            <slot name="form"></slot>
                        </div>
                    </div>

                    <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6" v-if="hasActions">
                        <slot name="actions"></slot>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle'

    export default {
        components: {
            JetSectionTitle,
        },
        props: ['rounded', 'single', 'results'],
        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
