<template>
    <section>
        <div class="coin-header">
            <div class="icon">
                <img :src="coin.image_url" :alt="coin.name">
            </div>
            <div class="name">
                <h1>{{ coin.name }}</h1>
                <h2>{{ coin.symbol }}</h2>
            </div>
            <div class="buttons">
                <div class="field has-addons">
                    <p class="control">
                        <b-button type="is-success">Buy</b-button>
                    </p>
                    <p class="control">
                        <b-button type="is-danger">Sell</b-button>
                    </p>
                </div>
            </div>
        </div>

        <div class="coin-content">
            <div class="columns">
                <div class="column is-two-thirds">
                    <div class="card">
                        <div class="card-content">
                            <div class="description">
                                <p>Bitcoin is a peer-to-peer electronic cash system that allows participants to
                                    digitally transfer units of bitcoin without a trusted intermediary. Bitcoin combines
                                    a public transaction ledger (blockchain), a decentralized currency issuance
                                    algorithm (proof-of-work mining), and a transaction verification system (transaction
                                    script). Bitcoin has a supply cap of 21 million bitcoin, 95% of which will be mined
                                    by the year 2025. Bitcoin relies on Nakamoto consensus, or consensus implied by the
                                    longest blockchain that has accumulated the most computational effort.</p>
                            </div>
                        </div>
                    </div>

                    <div class="columns">

                        <div class="column">
                            <div class="card">
                                <header class="card-header">
                                    <div class="card-header-title text-center">24h</div>
                                </header>
                                <div class="card-content">
                                    -0.87%
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="card">
                                <header class="card-header">
                                    <div class="card-header-title text-center">7d</div>
                                </header>
                                <div class="card-content">
                                    -0.87%
                                </div>
                            </div>
                        </div>

                        <div class="column">

                            <div class="card">
                                <header class="card-header">
                                    <div class="card-header-title text-center">30d</div>
                                </header>
                                <div class="card-content">
                                    -0.87%
                                </div>
                            </div>
                        </div>

                        <div class="column">

                            <div class="card">
                                <header class="card-header">
                                    <div class="card-header-title text-center">1y</div>
                                </header>
                                <div class="card-content">
                                    -0.87%
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <header class="card-header">
                            <div class="card-header-title">Price Chart</div>
                        </header>
                        <div class="card-content">
                            <market-price-chart market="3720"></market-price-chart>
                        </div>
                    </div>
                </div>
                <div class="column is-one-third">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Weiss Rankings &nbsp;
                                <b-tooltip
                                    label="This rating is powered by Weiss Crypto Ratings. Values range from A+ to F-."
                                    multilined
                                    position="is-right">
                                    <vue-fontawesome icon="info-circle"/>
                                </b-tooltip>
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="columns">
                                <div class="col"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                News & Discussions &nbsp;
                                <b-tooltip label="Latest news about the coin."
                                           multilined
                                           position="is-right">
                                    <vue-fontawesome icon="info-circle"/>
                                </b-tooltip>
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="columns" v-for="article in coin.articles" v-if="coin.articles.length > 0">
                                <div class="column is-one-third">
                                    <img :src="article.image_url" :alt="article.title">
                                </div>
                                <div class="column">
                                    <a :href="article.url" target="_blank"><h5>{{ article.title }}</h5></a>
                                    <small v-html="article.body" class="article-body-truncated"></small>
                                    <small class="article-tags"><span class="tag-label">Tags:</span> <span class="tag" v-for="tag in article.tags">{{ tag.tag }}</span></small>
                                </div>
                            </div>

                            <div v-else>
                                <p class="text-center">No articles found.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style lang="scss">
    .coin-header {
        color: white;
        display: flex;
        margin-bottom: 1.5rem;

        .icon {
            align-self: center;
            justify-self: center;
            margin-right: 1rem;
            min-width: 40px;

            img {
                width: 40px;
                height: 40px;
            }
        }

        > .buttons {
            flex: 1;
            justify-content: flex-end;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
        }

        h2 {
            font-size: 14px;
            font-weight: 500;
        }
    }

    .article-body-truncated {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .article-tags {
        .tag-label {
            font-weight: 700;
        }

        .tag {
            &:not(:last-child) {
                margin-right: 3px;
            }
        }
    }
</style>

<script>
    import gql from 'graphql-tag';
    import MarketPriceChart from "../../../components/Charts/MarketPriceChart";
    export default {
        apollo: {
            coin() {
                return {
                    query: gql`query CoinQuery($symbol: String!) {
                        coinBySymbol(symbol: $symbol) {
                            id
                            name
                            symbol
                            image_url
                            rank
                            num_markets
                            price_formatted
                            market_cap_formatted
                            articles {
                                id
                                title
                                body
                                url
                                image_url

                                tags {
                                    tag
                                }
                            }
                        }
                    }`,
                    variables() {
                        return {
                            symbol: this.$route.params.symbol
                        };
                    },
                    update: data => data.coinBySymbol
                }
            },
        },

        components: {MarketPriceChart},

        mounted() {
            console.log(this.$route.params);
        }
    }
</script>
