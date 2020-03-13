<template>
    <section class="auth-layout">
        <div class="auth-form">

            <div class="text-center">
                <router-link :to="{ name: 'login' }">
                    <img src="@assets/logo-primary.png" alt="" width="300" height="72">
                </router-link>
            </div>

            <router-view></router-view>

        </div>

        <div class="auth-loading-overlay" v-if="authStatus === 'loading'">
            <div class="has-text-grey has-text-centered">
                <p>
                    <vue-fontawesome icon="sync" size="3x" spin></vue-fontawesome>
                </p>
                <br>
                <p>Loading</p>
            </div>
        </div>
    </section>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        computed: {
            ...mapState('Auth', [
                'authStatus'
            ])
        }
    }
</script>
