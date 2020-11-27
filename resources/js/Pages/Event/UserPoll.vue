<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Poll
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="mt-8 xl:text-4xl">
                                <h1 class="text-center">{{ data.name }}</h1>
                            </div>

                            <div class="mt-1">
                                <p class="text-center">Deadline: <span class="text-gray-500">{{ deadlineToNow }}</span></p>
                            </div>

                            <div class="mt-6 xl:text-2xl text-gray-500 text-center">
                                {{ data.description }}
                            </div>
                        </div>

                        <jet-form-section @submitted="submitPoll" class="poll-content" rounded="0" single="true" v-if="voted === 0">
                            <template #form>
                                <div class="grid" v-bind:class="{ 'md:grid-cols-2': data.qr }">
                                    <div class="poll-qr" v-if="data.qr"><img :src="'data:image/png;base64,' + data.qrcode" /></div>
                                    <div class="p-6 bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1">
                                        <div class="p-6 my-0 mx-auto xl:w-6/12">
                                            <div>Choose one:</div>
                                            <div class="" v-for="(answer, index) in data.answers" :key="index">
                                                <input type="radio" class="form-checkbox rounded-md shadow-sm" :value="answer" v-model="form.user_answer"  autocomplete="answer" ref="input">
                                                <jet-label for="deadline" :value="answer" inline="1" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template #actions>
                                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                                    Saved.
                                </jet-action-message>

                                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save
                                </jet-button>
                            </template>
                        </jet-form-section>
                        <div class="p-6 bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1" v-else>
                            <div class="p-6 my-0 mx-auto xl:w-6/12 text-2xl text-center">
                                You have already voted.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
.poll-qr{
    width: 150px;
    margin: 0 auto;
    /* position: absolute;
    left: 8.5em; bottom: 4.5em; */
}
.poll-content{ position: relative; }
</style>

<script>
    import AppLayout from '@/Layouts/PollLayout'
    import formatDistanceToNow from 'date-fns/formatDistanceToNow'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetCheckbox,
            JetSecondaryButton,
        },
        props: ['data', 'voted'],
        data(){
            return {
                form: this.$inertia.form({
                    '_method': 'POST',
                    user_answer: '',
                    external_id: this.data.external_id,
                }, {
                    bag: 'submitPoll',
                    resetOnSuccess: true,
                    onSuccess: (page) => {
                        window.location.reload()
                    },
                }),
                user_answer: '',
            }
        },
        computed: {
            deadlineToNow: function () {
                // `this` points to the vm instance
                return formatDistanceToNow(new Date(this.data.deadline))
            }
        },
        mounted(){
            
        },
        methods: {
            submitPoll() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('submit_poll'), {
                    preserveScroll: true
                });
            },
        }
    }
</script>
