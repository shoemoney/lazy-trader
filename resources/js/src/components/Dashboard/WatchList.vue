<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Cryptocurrency Watch List
            </p>
        </header>
        <div class="card-content">
            <b-table :data="watchList" :columns="watchListColumns" :striped="true" :hoverable="true" :loading="isLoading">

                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon
                                    icon="times"
                                    size="is-large">
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

<script>
    export default {
        data() {
            return {
                watchList: [
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
                watchListColumns: [
                    {
                        field: 'asset',
                        label: 'Asset',
                    },
                    {
                        field: 'price',
                        label: 'Current Price',
                    },
                    {
                        field: 'percent_change',
                        label: '% Change (24h)'
                    }
                ],
                isLoading: false
            }
        }
    }
</script>
