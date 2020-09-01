<template>
    <vueloading :active="loading" :is-full-page="isFullPage"></vueloading>
</template>

<script>
    // Import component
    import vueloading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "gsi_loading",
        components: {
            vueloading
        },
        props: {
            loading: {
                type: Boolean,
                default: false,
            },
            isFullPage: {
                type: Boolean,
                default: false,
            }
        }
    }
</script>

<style scoped>

</style>
