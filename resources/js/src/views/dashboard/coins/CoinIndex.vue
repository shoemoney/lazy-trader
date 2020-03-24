<template>
    <section>
        <title-bar :title-stack="titleStack"/>
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Coins
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <b-table :data="displayCoins" :columns="coinColumns" :striped="true" :hoverable="true"
                             :loading="isLoading" @click="onClickCoin">

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

                    <b-pagination
                        v-if="!isLoading"
                        :total="coins.paginatorInfo.total"
                        :per-page="coins.paginatorInfo.perPage"
                        :simple="true"
                        :current.sync="page"
                        aria-next-label="Next page"
                        aria-previous-label="Previous page"
                        aria-page-label="Page"
                        aria-current-label="Current page">
                    </b-pagination>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import gql from 'graphql-tag';

    export default {

        apollo: {
            coins() {
                return {
                    query: gql`query CoinQuery($page: Int!) {
                        coins(page: $page, orderBy: [{ field: "market_cap", order: DESC },{ field: "weiss_rating", order: ASC }]) {
                            data {
                                id
                                name
                                rank
                                symbol
                                num_markets
                                price_formatted
                                market_cap_formatted
                            }
                            paginatorInfo {
                                total
                                perPage
                                currentPage
                                lastPage
                            }
                        }
                    }`,
                    variables() {
                        return {page: this.page};
                    }
                }
            },
        },

        computed: {
            displayCoins() {
                if (this.isLoading)
                    return [];

                return this.coins.data
            },

            titleStack() {
                return [
                    'Coins'
                ]
            },

            isLoading() {
                return this.$apollo.queries.coins.loading;
            }
        },


        data() {
            return {
                coinColumns: [
                    {
                        field: 'rank',
                        label: 'Rank',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    },
                    {
                        field: 'num_markets',
                        label: '# of Markets',
                    },
                    {
                        field: 'price_formatted',
                        label: 'Current Agg Price',
                        numeric: true
                    },
                    {
                        field: 'market_cap_formatted',
                        label: 'Market Cap',
                        numeric: true
                    }
                ],
                page: 1
                // hello: ''
            }
        },


        methods: {
            onClickCoin(coin) {
                this.$router.push({name: 'coins.view', params: {symbol: coin.symbol}})
            }
        },
    }
</script>
