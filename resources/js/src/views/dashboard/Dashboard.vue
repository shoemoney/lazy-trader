<template>
    <div>
        <title-bar :title-stack="titleStack"/>

        <ticker-bar></ticker-bar>


        <div class="columns">
            <div class="column is-two-thirds">
                <portfolio-overview></portfolio-overview>
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Recent Trades
                        </p>
                    </header>
                    <div class="card-content">

                    </div>
                </div>
            </div>
            <div class="column is-one-third">
                <watch-list></watch-list>
                <div class="columns">
                    <div class="column">
                        <div class="card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    Running Bots
                                </p>
                            </header>
                            <div class="card-content">
                                15 bots
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    Total Bots
                                </p>
                            </header>
                            <div class="card-content">
                                25 bots
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Active Orders
                        </p>
                    </header>
                    <div class="card-content">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import TickerBar from '@/components/Dashboard/TickerBar';
    import WatchList from '@/components/Dashboard/WatchList';
    import PortfolioOverview from '@/components/Dashboard/PortfolioOverview';

    export default {
        components: {
            'TickerBar': TickerBar,
            'WatchList': WatchList,
            'PortfolioOverview': PortfolioOverview
        },
        computed: {
            titleStack() {
                return [
                    'Dashboard'
                ]
            }
        },

    }
</script>
