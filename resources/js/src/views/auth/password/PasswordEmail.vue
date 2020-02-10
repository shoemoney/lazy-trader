<template>


    <form method="POST" v-on:submit.prevent="sendPasswordResetEmail" v-if="seconds>4">

        <b-field label="Email">
            <b-input type="email" required v-model="email">
            </b-input>
        </b-field>

        <div class="buttons">
            <b-button type="is-primary" native-type="submit" :disabled="disableSubmit" expanded>Send Email</b-button>
        </div>

        <p>
            Go back to
            <router-link :to="{ name: 'login' }">login</router-link>
            page.
        </p>
    </form>


    <div v-else>

        <h2 class="text-success">Password recovery email sent!</h2>
        <b-progress type="is-success" :value="progress"></b-progress>

        <h3 class="text-center">Redirecting in {{ seconds }} seconds</h3>

    </div>


</template>

<script>
    export default {
        data() {
            return {
                'disableSubmit': false,
                'email': '',
                'seconds': 5
            }
        },
        computed: {
            progress: function () {
                return (20 * Math.abs(5 - this.seconds));
            }
        },
        methods: {
            sendPasswordResetEmail: function () {
                self = this;
                self.disableSubmit = true;
                axios.post('/password/email', {'email': self.email})
                    .then((resp) => {
                        self.countdownRedirect();
                    })
                    .catch(() => {
                        self.disableSubmit = false;
                        self.seconds = 5;
                    })
            },
            countdownRedirect: function () {
                self = this;
                setInterval(() => {
                    self.seconds = self.seconds - 1;
                    if (self.seconds === 0) {
                        self.$router.push('/');
                    }
                }, 1000);

            },
        }
    }
</script>
