<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Event
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <jet-form-section @submitted="createNewEvent">
                        <template #title>
                            New Event
                        </template>

                        <template #description>
                            Create new event
                        </template>

                        <template #form>
                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-4">
                                <jet-label for="name" value="Name" />
                                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                                <jet-input-error :message="form.error('name')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <jet-label for="desc" value="Description" />
                                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" autocomplete="description" />
                                <jet-input-error :message="form.error('description')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <jet-label for="answer[]" value="Answers" />
                                <jet-input id="answer[]" type="text" class="mt-1 block w-full" v-model="form.answers[n]" autocomplete="answer[]"  v-for="n in totalAnswers" :key="n" />
                                <jet-input-error :message="form.error('answers')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <input id="deadline" type="checkbox" class="form-checkbox rounded-md shadow-sm" v-model="deadline"  autocomplete="deadline" ref="input">
                                <jet-label for="deadline" value="Enable Deadline" inline="1" />
                            </div>

                            <div class="col-span-6 sm:col-span-4" v-if="deadline">
                                <jet-label for="deadline_date" value="Deadline" inline="1" />
                                <datetime v-model="form.deadline" input-class="form-input" type="datetime"></datetime>
                                <jet-input-error :message="form.error('deadline_date')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <jet-checkbox id="qr" type="checkbox" class="" v-model="form.qr" autocomplete="qr" />
                                <jet-label for="qr" value="Generate QR?" inline="1" />
                                <jet-input-error :message="form.error('qr')" class="mt-2" />
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
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    import { Datetime } from 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css'

    export default {
        components: {
            AppLayout,
            Welcome,
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetCheckbox,
            JetSecondaryButton,
            Datetime
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: '',
                    description: '',
                    deadline: '',
                    qr: '',
                    answers: []
                }, {
                    bag: 'createNewEvent',
                    resetOnSuccess: true,
                    onSuccess: (page) => {},
                }),
                totalAnswers: 3,
                deadline: false
            }
        },
        mounted(){
            
        },
        methods: {
            createNewEvent() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('save_event'), {
                    preserveScroll: true
                });
            },
            toggleDeadline(){
                if(this.deadline === false)
                {
                    this.deadline = true;
                }else{
                    this.deadline = false;
                }
            }
        },
    }
</script>