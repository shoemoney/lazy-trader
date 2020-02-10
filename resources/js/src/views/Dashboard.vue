<template>
    <div>
        <title-bar :title-stack="titleStack"/>
        Dashboard :)
        <a href="#">Test</a>
    </div>
</template>

<script>
    export default {
        created() {
            console.log('We are dsahboard :)');
        },
        computed: {
            titleStack () {
                return [
                    'Dashboard'
                ]
            }
        },
    }
</script>
