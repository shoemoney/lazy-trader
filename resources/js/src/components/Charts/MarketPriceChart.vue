<template>
    <div>
        <apexchart
            :type="chartType === 'candle' ? 'candlestick' : 'line'"
            :options="chartOptions"
            :series="series"
            v-if="!isLoading"
        />
        <div class="columns" v-if="showOptions">
            <div class="column">
                <div class="field has-addons">
                    <p class="control" v-for="option in rangeOptions">
                        <b-button :type="range === option ? 'is-primary' : 'is-default'" size="is-small"
                                  @click.prevent="range = option">{{ option }}
                        </b-button>
                    </p>
                </div>
                <div class="field has-addons">
                    <p class="control" v-for="option in typeOptions">
                        <b-button :type="type === option ? 'is-primary' : 'is-default'" size="is-small"
                                  @click.prevent="type = option">{{ option }}
                        </b-button>
                    </p>
                </div>
            </div>
            <div class="column text-right">
                <div class="field has-addons">
                    <p class="control" v-for="option in chartTypeOptions">
                        <b-button :type="chartType === option ? 'is-primary' : 'is-default'" size="is-small"
                                  @click.prevent="chartType = option">{{ option }}
                        </b-button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import gql from 'graphql-tag';
    import VueApexCharts from 'vue-apexcharts';

    export default {
        apollo: {
            pricing() {
                return {
                    query: gql`query MarketQuery($id: ID!, $minTimestamp: Int, $range: String, $type: String) {
                        pricingByMarket(market: $id, minTimestamp: $minTimestamp, range: $range, type: $type) {
                            ts
                            open
                            high
                            low
                            close
                            volume
                        }
                    }`,
                    variables() {
                        return {
                            id: this.market,
                            range: this.range,
                            type: this.type
                        };
                    },
                    update: data => data.pricingByMarket
                }
            },
        },

        components: {
            apexchart: VueApexCharts,
        },

        computed: {
            chartOptions() {
                return {
                    chart: {
                        type: this.chartType === 'candle' ? 'candlestick' : 'line',
                    },
                    title: {
                        text: 'CandleStick Chart',
                        align: 'left'
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: {
                        tooltip: {
                            enabled: true
                        }
                    }
                };
            },

            series() {
                if (this.isLoading || !this.pricing)
                    return [];

                return [{
                    data: this.pricing.map((x) => {
                        return {
                            x: new Date(x.ts * 1000),
                            y: this.chartType === 'candle' ? [x.open, x.high, x.low, x.close] : [(x.open + x.high + x.low + x.close) / 4]
                        };
                    })
                }];
            },

            isLoading() {
                return this.$apollo.queries.pricing.loading;
            }
        },
        data() {
            return {
                chartType: 'candle',
                chartTypeOptions: ['candle', 'line'],
                showOptions: true,
                range: '5d',
                rangeOptions: ['1d', '5d', '1m', '3m', '6m', '1y', '3y'],
                type: 'hourly',
                typeOptions: ['minute', 'hourly', 'daily', 'weekly', 'monthly'],
            }
        },

        mounted() {
            console.log(this.market);
        },

        props: ['market']
    }
</script>
