<template>

    <form method="POST" v-on:submit.prevent="login">

        <b-message v-if="authErrors.has('invalid_credentials')">
            {{ authErrors.get('invalid_credentials') }}
        </b-message>

        <b-field label="Email">
            <b-input type="email" required v-model="email">
            </b-input>
        </b-field>

        <b-field label="Password">
            <b-input type="password" required v-model="password" />
        </b-field>

        <b-checkbox v-model="remember">Remember Me</b-checkbox>

        <div class="buttons">
            <b-button type="is-primary" native-type="submit" expanded>Sign In</b-button>
        </div>


        <router-link :to="{ name: 'password-email' }">Forgot Password</router-link>

        <p>
            Don't have an account? <router-link :to="{ name: 'register' }">Register</router-link>
        </p>

    </form>

</template>

<script>
    export default {
        data() {
            return {
                'email': '',
                'password': '',
                'remember': false,
            }
        },
        computed: {
            authErrors() {
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            login: function () {
                const {email, password, remember} = this;
                this.$store.dispatch('authRequest', {email, password, remember})
                    .then(() => {
                        this.$router.push('/dashboard')
                    })
            }
        }
    }
</script>
