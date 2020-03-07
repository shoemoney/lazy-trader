<template>
    <section>
        <title-bar :title-stack="titleStack"/>

        <div class="card">
            <div class="card-header">
                <p class="card-header-title">{{ name }}</p>


                <div class="card-header-icon">
                    <b-button size="is-small" icon-left="copy" type="is-primary" @click="create">Duplicate</b-button>
                    <b-button size="is-small" icon-left="sync" type="is-primary" @click="create">Retest</b-button>
                </div>
            </div>
            <div class="card-content">
                <div class="columns">
                    <div class="column">
                        <h4>Market</h4>
                        <p>Binance BTC/USD</p>
                        <h4>Type</h4>
                        <p>Trade Configuration</p>
                    </div>
                    <div class="column">
                        <h4>Market</h4>
                        <p>Binance BTC/USD</p>
                        <h4>Type</h4>
                        <p>Trade Configuration</p>
                    </div>
                    <div class="column">
                        <h4>Total Profit/Loss</h4>
                        <p>$ 1,256.56</p>
                        <h4>Type</h4>
                        <p>Trade Configuration</p>
                    </div>
                </div>

                <h3>Order History</h3>
                <b-table :data="orders" :columns="orderColumns" :striped="true" :hoverable="true">

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>
                                    <vue-fontawesome icon="times" size="3x"></vue-fontawesome>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>

                </b-table>
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
                    this.name
                ]
            },
        },


        data() {
            return {
                name: '1yr - Binance BTCUSD - Boss Method',

                orders: [],
                orderColumns: [
                    {
                        field: 'type',
                        label: 'Buy/Sell'
                    },
                    {
                        field: 'amount',
                        label: 'Amount',
                    },
                    {
                        field: 'price',
                        label: 'Price',
                    },
                    {
                        field: 'value',
                        label: 'Total Value'
                    },
                    {
                        field: 'profit_loss',
                        label: 'P/L'
                    }
                ],
            }
        },


        methods: {
            create() {
                this.$router.push({name: 'trading.strategy.create'});
            }
        }
    }
</script>
