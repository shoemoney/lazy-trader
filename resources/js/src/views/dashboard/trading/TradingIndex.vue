<template>
    <section>
        <title-bar :title-stack="titleStack"/>


        <div class="card">

            <div class="card-header">
                <div class="card-header-title">Trading Overview</div>
            </div>
            <div class="card-content">
                <div class="columns">
                    <div class="column text-center">
                        <h2>0</h2>
                        <h4>Running Bots</h4>
                    </div>
                    <div class="column text-center">
                        <h2>0</h2>
                        <h4>Configurations</h4>
                    </div>
                </div>


                <h2>Recent Buys</h2>
                <b-table :data="recentBuys" :columns="recentBuyColumns" :striped="true" :hoverable="true">

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

                <br>
                <h2>Recent Sells</h2>
                <b-table :data="recentSells" :columns="recentSellColumns" :striped="true" :hoverable="true">

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
                    'Trading'
                ]
            },
        },


        data() {
            return {
                recentBuys: [],
                recentBuyColumns: [
                    {
                        field: 'market',
                        label: 'Market'
                    },
                    {
                        field: 'configuration',
                        label: 'Configuration',
                    },
                    {
                        field: 'uuid',
                        label: 'UUID',
                    },
                    {
                        field: 'quantity',
                        label: 'Quantity'
                    },
                    {
                        field: 'price',
                        label: 'Price'
                    },
                    {
                        field: 'total_value',
                        label: 'Total Value'
                    }
                ],
                recentSells: [],
                recentSellColumns: [
                    {
                        field: 'market',
                        label: 'Market'
                    },
                    {
                        field: 'configuration',
                        label: 'Configuration',
                    },
                    {
                        field: 'uuid',
                        label: 'UUID',
                    },
                    {
                        field: 'quantity',
                        label: 'Quantity'
                    },
                    {
                        field: 'price',
                        label: 'Price'
                    },
                    {
                        field: 'total_value',
                        label: 'Total Value'
                    }
                ]
            }
        },


        methods: {}
    }
</script>
