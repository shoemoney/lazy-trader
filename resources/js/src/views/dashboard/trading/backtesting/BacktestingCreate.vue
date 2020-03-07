<template>
    <section>
        <title-bar :title-stack="titleStack"/>

        <div class="card">
            <div class="card-header">
                <p class="card-header-title">{{ name }}</p>
            </div>
            <div class="card-content">
                <b-steps
                    v-model="activeStep"
                    :animated="isAnimated"
                    :has-navigation="hasNavigation"
                    :icon-prev="prevIcon"
                    :icon-next="nextIcon">
                    <b-step-item label="Account" :clickable="isStepsClickable">
                        <h1 class="title has-text-centered">General</h1>
                        <b-field label="Name">
                            <b-input></b-input>
                        </b-field>

                        <b-field label="Market">
                            <b-autocomplete placeholder="Select a trade configuration" expanded :data="[
                                                        'Binance (BTC/USD)',
                                                        'Binance (ETH/USD)',
                                                        'Binance (LTC/USD)',
                                                        'Binance (XLM/USD)',
                                                        'Binance (XRP/USD)',
                                                    ]"
                                            @select="option => selected = option"></b-autocomplete>
                        </b-field>
                        <b-field label="Start Amount">
                            <b-input></b-input>
                        </b-field>

                        <b-field label="Type">
                            <div class="columns">
                                <div class="column">
                                    <div class="card"
                                         :class="[type === 'trade-configuration' ? 'is-primary' : 'is-default', 'is-clickable']"
                                         @click="changeType('trade-configuration')">
                                        <div class="card-header">
                                            <p class="card-header-title card-header-title-sm">Trade Strategy
                                                Backtest</p>
                                        </div>
                                        <div class="card-content">
                                            <ul>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="card"
                                         :class="[type === 'swing' ? 'is-primary' : 'is-default', 'is-clickable']"
                                         @click="changeType('swing')">
                                        <div class="card-header">
                                            <p class="card-header-title card-header-title-sm">Swing
                                                Backtest</p>
                                        </div>
                                        <div class="card-content">
                                            <ul>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                                <li>Lorem ipsum dolor sit amet</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-field>
                    </b-step-item>

                    <b-step-item label="Profile" :clickable="isStepsClickable" :type="{'is-success': isProfileSuccess}">
                        <h1 class="title has-text-centered">Swing Backtest Setup</h1>
                        Lorem ipsum dolor sit amet.
                    </b-step-item>

                    <b-step-item :visible="showSocial" label="Social" :clickable="isStepsClickable">
                        <h1 class="title has-text-centered">Notification</h1>
                        Lorem ipsum dolor sit amet.
                    </b-step-item>


                    <template
                        v-if="customNavigation"
                        slot="navigation"
                        slot-scope="{previous, next}">
                        <b-button
                            outlined
                            type="is-danger"
                            icon-pack="fas"
                            icon-left="backward"
                            :disabled="previous.disabled"
                            @click.prevent="previous.action">
                            Previous
                        </b-button>
                        <b-button
                            outlined
                            type="is-success"
                            icon-pack="fas"
                            icon-right="forward"
                            :disabled="next.disabled"
                            @click.prevent="next.action">
                            Next
                        </b-button>
                    </template>
                </b-steps>
            </div>
        </div>
    </section>
</template>

<script>

    export default {

        computed: {
            titleStack() {
                return [
                    'Trading',
                    'Backtesting',
                    'Create'
                ]
            },
        },


        data() {
            return {
                type: 'trade-configuration'
            }
        },


        methods: {
            create() {
                this.$router.push({name: 'trading.strategy.create'});
            },

            changeType(type) {
                this.type = type;
            }
        }
    }
</script>
