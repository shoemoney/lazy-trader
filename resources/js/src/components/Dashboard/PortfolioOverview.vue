<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Portfolio Overview
            </p>
        </header>
        <div class="card-content">
            <div class="columns">
                <div class="column">
                    <h3>Net Worth</h3>
                    <p>$7,895</p>
                </div>
                <div class="column">
                    <h3>Value of Coins</h3>
                    <p>$5,987</p>
                </div>
                <div class="column">
                    <h5>Todays Gain</h5>
                    <p>5.07% ($382)</p>
                </div>
                <div class="column">
                    <h5>7d Gain</h5>
                    <p>4% ($678)</p>
                </div>
            </div>

            <h2>Holdings</h2>

            <div class="holdings">
                <div class="bar" :style="{backgroundColor: 'green', width: '50%'}"></div>
                <div class="bar" :style="{backgroundColor: 'blue', width: '25%'}"></div>
                <div class="bar" :style="{backgroundColor: 'red', width: '15%'}"></div>
                <div class="bar" :style="{backgroundColor: 'gray', width: '10%'}"></div>
            </div>

            <b-table :data="portfolio" :columns="portfolioColumns" :striped="true" :hoverable="true" :loading="isLoading">

                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon
                                    icon="times"
                                    custom-size="is-large">
                                </b-icon>
                            </p>
                            <p>Nothing here.</p>
                        </div>
                    </section>
                </template>

            </b-table>
        </div>
    </div>
</template>

<style lang="scss">
    .holdings {
        border-radius: 8px;
        display: flex;
        height: 8px;
        width: 100%;
        margin-bottom: 1rem;

        > .bar {
            height: 100%;

            &:first-child {
                border-top-left-radius: 8px;
                border-bottom-left-radius: 8px;
            }

            &:last-child {
                border-top-right-radius: 8px;
                border-bottom-right-radius: 8px;
            }
        }
    }
</style>

<script>
    export default {
        data() {
            return {
                portfolio: [
                    {
                        asset: 'BTC',
                        price: '$9,987',
                        percent_change: '4.98%'
                    },

                    {
                        asset: 'ETH',
                        price: '$9,987',
                        percent_change: '4.98%'
                    },

                    {
                        asset: 'LTC',
                        price: '$9,987',
                        percent_change: '4.98%'
                    },

                    {
                        asset: 'ADA',
                        price: '$9,987',
                        percent_change: '4.98%'
                    }
                ],
                portfolioColumns: [
                    {
                        field: 'asset',
                        label: 'Asset',
                    },
                    {
                        field: 'amount',
                        label: 'Amount'
                    },
                    {
                        field: 'value',
                        label: 'Value'
                    },
                    {
                        field: 'price',
                        label: 'Current Price',
                    },
                    {
                        field: 'percent_change',
                        label: '% Change (24h)'
                    },
                    {
                        field: 'roi',
                        label: 'ROI'
                    }
                ],
                isLoading: false
            }
        }
    }
</script>
