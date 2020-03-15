<template>
    <div></div>
</template>


<script>
    import {mapState} from "vuex";
    import { SnackbarProgrammatic as Snackbar } from 'buefy';

    export default {
        computed: {
            ...mapState('User', [
                'profile'
            ])
        },

        methods: {
            /** Bind Channels **/
            bindChannels() {
                if (this.profile !== null) {
                    console.log('binding user models...');
                    Echo.private('App.Models.User.' + this.profile.id)
                        .notification((object) => {
                            Snackbar.open(object.data);
                        })
                }
            },
        },

        mounted() {
            this.bindChannels();
        },

        watch: {
            // whenever question changes, this function will run
            profile: function (newQuestion, oldQuestion) {
                this.bindChannels();
            }
        },
    }
</script>
