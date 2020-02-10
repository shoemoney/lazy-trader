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
    </section>
</template>

<script>
    export default {

    }
</script>
