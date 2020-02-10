<template>

        <form method="POST" v-on:submit.prevent="register" @keydown="authErrors.clear($event.target.name)">

            <b-field label="Name">
                <b-input v-model="name" required autofocus></b-input>
            </b-field>

            <b-field label="Email Address">
                <b-input type="email"
                         required
                         v-model="email">
                </b-input>
            </b-field>

            <b-field label="Password">
                <b-input type="password" required v-model="password" />
            </b-field>

            <b-field label="Confirm Password">
                <b-input type="password" required v-model="password_confirmation" />
            </b-field>

            <b-message
                type="is-danger"
                has-icon
                v-if="authErrors.has('name') || authErrors.has('email') || authErrors.has('password')">
                <div v-text="authErrors.get('name')"></div>
                <div v-text="authErrors.get('email')"></div>
                <div v-text="authErrors.get('password')"></div>
            </b-message>

            <div class="buttons">
                <b-button type="is-primary" native-type="submit" expanded>Register</b-button>
            </div>

            Have an account? <router-link :to="{ name: 'login' }">Login</router-link>

        </form>

</template>


<script>
    export default {
        data(){
            return {
                'action':'register',
                'name':'',
                'email':'',
                'password':'',
                'password_confirmation':'',
            }
        },
        computed: {
            authErrors(){
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            register: function () {
                const { action, name, email, password, password_confirmation } = this;
                this.$store.dispatch('authRequest', { action, name, email, password, password_confirmation })
                    .then(() => {
                        this.$router.push('/dashboard')
                    })
            }
        }
    }
</script>
