<template>

    <form method="POST" v-on:submit.prevent="resetPassword">

        <b-field label="Email">
            <b-input type="email" required v-model="email" autofocus />
        </b-field>

        <input type="hidden" name="token" required v-model="token">

        <b-field label="Password">
            <b-input type="password" required v-model="password" />
        </b-field>

        <b-field label="Confirm Password">
            <b-input type="password" required v-model="password_confirmation" />
        </b-field>

        <b-message
            type="is-danger"
            has-icon
            v-if="authErrors.any()">
            <div v-text="authErrors.get('email')"></div>
            <div v-text="authErrors.get('password')"></div>
        </b-message>

        <div class="buttons">
            <b-button type="is-primary" native-type="submit" :disabled="disableSubmit" expanded>Send Email</b-button>
        </div>

    </form>

</template>

<script>
    export default {
        data() {
            return {
                'action': 'password-reset',
                'email': '',
                'password': '',
                'password_confirmation': '',
                'token': ''
            }
        },
        computed: {
            authErrors() {
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            resetPassword: function () {
                const {action, email, password, password_confirmation, token} = this;
                this.$store.dispatch('authRequest', {action, email, password, password_confirmation, token})
                    .then(() => {
                        this.$router.push('/dashboard')
                    })
            }
        },
        mounted() {
            let token = this.$route.params.token;
            if (!token) {
                this.$router.push('/');
            }
            this.token = token;
        }
    }
</script>
