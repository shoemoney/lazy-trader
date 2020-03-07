<template>
    <section>
        <title-bar :title-stack="titleStack"/>

        <div class="card">
            <div class="card-header">
                <div class="card-header-title">Add New Trading Strategy</div>
            </div>
            <div class="card-content">
                <b-field label="Name">
                    <b-input placeholder="Configuration Name"></b-input>
                </b-field>

                <div class="columns">
                    <div class="column">
                        <b>Conditionals</b>
                    </div>
                    <div class="column text-right">
                        <b-button size="is-small" icon-left="plus" type="is-primary" @click="triggerConditionalModal">
                            Add Conditional
                        </b-button>
                    </div>
                </div>

                <div v-if="conditionals > 0">

                </div>
                <div v-else>
                    <h4 class="text-center">You must add a conditional to continue.</h4>
                    {{ isCardModalActive }}
                </div>

                <br>
                <div class="columns">
                    <div class="column">
                        <b-button type="is-default" @click="cancel">Cancel</b-button>
                    </div>
                    <div class="column text-right">
                        <b-button type="is-primary" @click="">Submit</b-button>
                    </div>
                </div>
            </div>
        </div>

        <b-modal :active.sync="isCardModalActive">
            <div class="card card-modal">

                <div class="card-header">
                    <div class="card-header-title">New Conditional</div>
                </div>

                <div class="card-content">
                    <b-field label="First Value">
                        <div class="columns">
                            <div class="column">
                                <b-select expanded placeholder="Select a name">

                                </b-select>
                            </div>
                            <div class="column">
                                <b-input placeholder="Configuration Name"></b-input>
                            </div>
                            <div class="column">
                                <b-input placeholder="Configuration Name"></b-input>
                            </div>
                        </div>
                    </b-field>
                    <b-field label="Operator">

                        <b-select expanded placeholder="Select a name">

                        </b-select>
                    </b-field>

                    <b-field label="Second Value">
                        <div class="columns">
                            <div class="column">
                                <b-select expanded placeholder="Select a name">

                                </b-select>
                            </div>
                            <div class="column">
                                <b-input placeholder="Configuration Name"></b-input>
                            </div>
                            <div class="column">
                                <b-input placeholder="Configuration Name"></b-input>
                            </div>
                        </div>
                    </b-field>

                    <div class="columns">
                        <div class="column">
                            <b-button type="is-default" @click="triggerConditionalModal">Close</b-button>
                        </div>
                        <div class="column text-right">
                            <b-button type="is-primary" @click="">Submit</b-button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </section>
</template>

<script>

    export default {

        computed: {
            titleStack() {
                return [
                    'Trading',
                    'Strategies',
                    'Create'
                ]
            },
        },

        data() {
            return {
                conditionals: [],
                isCardModalActive: false
            }
        },

        methods: {
            cancel() {
                this.$router.back();
            },

            triggerConditionalModal(){
                this.isCardModalActive = !this.isCardModalActive;
            }
        }
    }
</script>
