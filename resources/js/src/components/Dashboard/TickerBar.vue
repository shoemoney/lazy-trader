<template>
    <div class="card card-tight">
        <div class="card-content">
            <div class="ticker-wrapper">
                <div class="tickers" v-if="!isLoading">
                    <router-link class="ticker" v-for="coin in coins.data"
                                 :to="{name: 'coins.view', params: {symbol: coin.symbol}}" v-bind:key="coin.id">
                        <img :src="coin.image_url" :alt="coin.full_name">
                        <span>{{ coin.name }}</span>
                        <span>${{ coin.price }}</span>
                    </router-link>
                </div>
                <div class="text-center" v-else>
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import gql from 'graphql-tag';

    export default {
        apollo: {
            coins: gql`query {
                        coins(
                            page: 1,
                            orderBy: [{ field: "market_cap", order: DESC },{ field: "weiss_rating", order: DESC }]
                        ) {
                            data {
                                id
                                name
                                symbol
                                image_url
                                full_name
                                price
                            }
                            paginatorInfo {
                                total
                                perPage
                                currentPage
                                lastPage
                            }
                        }
                    }`

            ,
        },
        computed: {
            isLoading() {
                return this.$apollo.queries.coins.loading;
            }
        }

    }
</script>
