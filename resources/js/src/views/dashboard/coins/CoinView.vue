<template>
    <section>
        <div class="coin-header">
            <div class="icon">
                <img src="https://www.cryptocompare.com/media/19633/btc.png" alt="Bitcoin">
            </div>
            <div class="name">
                <h1>Bitcoin</h1>
                <h2>BTC</h2>
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
                            <img src="@assets/coin-price-graph.jpg" alt="Price Graph" style="width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="column is-one-third">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Weiss Rankings
                            </p>
                        </header>
                        <div class="card-content">

                        </div>
                    </div>
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                News & Discussions
                            </p>
                        </header>
                        <div class="card-content">

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
</style>

<script>
    export default {}
</script>
