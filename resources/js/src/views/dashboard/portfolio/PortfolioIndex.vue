<template>
    <section>
        <title-bar :title-stack="titleStack"/>

        <div class="columns">
            <div class="column is-one-third">

                <div class="card">

                    <div class="card-header">
                        <div class="card-header-title">Portfolio Overview</div>
                    </div>
                    <div class="card-content">
                        <h2 class="text-center">Portfolio Change</h2>
                        <h4 class="text-center">+50%</h4>
                        <hr>
                        <div class="columns">
                            <div class="column">
                                <h2 class="text-center">Portfolio MIN</h2>
                                <h4 class="text-center">$1,928.07</h4>
                            </div>
                            <div class="column">
                                <h2 class="text-center">Portfolio MAX</h2>
                                <h4 class="text-center">$3,129.08</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="columns">
                            <div class="column">
                                <h2 class="text-center">Least Profitable</h2>
                                <h4 class="text-center">EOS $-1,000.78</h4>
                            </div>
                            <div class="column">
                                <h2 class="text-center">Most Profitable</h2>
                                <h4 class="text-center">ADA $ 589.69</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="columns">
                            <div class="column">
                                <h2 class="text-center">Worst Crypto</h2>
                                <h4 class="text-center">EOS (-13.78%)</h4>
                            </div>
                            <div class="column">
                                <h2 class="text-center">Best Crypto</h2>
                                <h4 class="text-center">ADA (85.90%)</h4>
                            </div>
                        </div>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur assumenda consequuntur
                            eius eos laudantium. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur
                            assumenda consequuntur eius eos laudantium.</p>
                    </div>
                </div>
            </div>
            <div class="column is-two-thirds">

                <div class="columns">
                    <div class="column">
                        <div class="card card-tight card-outline">
                            <div class="card-header">
                                <div class="card-header-title card-header-title-sm">Acquisition Cost</div>
                            </div>
                            <div class="card-content">
                                <p>$ 3,068.51</p>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card card-tight card-outline">
                            <div class="card-header">
                                <div class="card-header-title card-header-title-sm">Realized P/L</div>
                            </div>
                            <div class="card-content">
                                <p>$ 3,824.08</p>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card card-tight card-outline">
                            <div class="card-header">
                                <div class="card-header-title card-header-title-sm">Profit / Loss</div>
                            </div>
                            <div class="card-content">
                                <p>$ -2,550.22</p>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card card-tight card-outline">
                            <div class="card-header text-center">
                                <div class="card-header-title card-header-title-sm">Holdings</div>
                            </div>
                            <div class="card-content">
                                <p>$ 518.29</p>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card card-tight card-outline">
                            <div class="card-header">
                                <div class="card-header-title card-header-title-sm">24H Profit / Loss</div>
                            </div>
                            <div class="card-content">
                                <p>$ 20.62</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <div class="card-header-title">Portfolio 1 Day Chart</div>
                    </div>
                    <div class="card-content">

                        <img src="@assets/portfolio-chart.png" alt="Portfolio Chart">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Portfolio Breakdown
                </p>

                <a href="#" class="card-header-icon">
                    <b-button size="is-small" icon-left="plus" type="is-primary">Add Coin</b-button>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                    <b-table :data="portfolio" :columns="portfolioColumns" :striped="true" :hoverable="true">

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
        </div>
    </section>
</template>

<script>

    export default {

        computed: {

            titleStack() {
                return [
                    'Portfolio'
                ]
            },
        },


        data() {
            return {
                portfolio: [],
                portfolioColumns: [
                    {
                        field: 'logo',
                        label: '#',
                        width: '40'
                    },
                    {
                        field: 'title',
                        label: 'Coin/Date',
                    },
                    {
                        field: 'price',
                        label: 'Price',
                    },
                    {
                        field: 'total_value',
                        label: 'Total Value'
                    },
                    {
                        field: 'profit_loss',
                        label: 'Profit/Loss'
                    },
                    {
                        field: 'change',
                        label: 'Change'
                    }
                ],
            }
        },


        methods: {}
    }
</script>
