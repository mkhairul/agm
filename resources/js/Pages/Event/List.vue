<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Show Events
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-6 gap-1">
                <div class="event-item border-b border-gray-200 p-5" v-for="item in data" :key="item.id">
                    <div>
                        <h3><a :href="route('user_poll', { id: item.external_id })">{{ item.name }}</a></h3>
                        <p v-for="(answer, index) in item.answers" :key="index">{{ answer }}: {{ item[answer] ? item[answer]:0 }}</p>
                        <a :href="route('user_poll', { id: item.external_id })" v-if="item.qr">
                            <img :src="'data:image/png;base64,' + item.qrcode" />
                        </a>
                        <div class="delete-btn" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
.delete-btn{
    position: absolute;
    width: 25px;
    bottom: 0;
    right: 0;
    margin-bottom: 5px;
    margin-right: 5px;
    display: none;
}
.event-item:hover .delete-btn{ display: block; }
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

.event-item{
    animation: fadein 2s;
    min-height: 250px;
    background-color: #fff;
    cursor: pointer;
    position: relative;
}
.event-item:hover{
    background-color: #9f9f9f;
}
.event-item h3{
    font-size: 2em;
}
@keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}
</style>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        components: {
            AppLayout,
            
        },
        props: ['user', 'data'],
        data() {
            return {
                events: []
            }
        },
        mounted(){
            var self = this;
            console.log(this.data)
        }
    }
</script>
