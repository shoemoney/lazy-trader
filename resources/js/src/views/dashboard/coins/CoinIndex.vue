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
                        :total="coins ? coins.length : 0"
                        :per-page="pagination.limit"
                        :simple="true"
                        :current.sync="pagination.page"
                        @change="changePage"
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
    import {mapGetters, mapState} from 'vuex';

    export default {

        computed: {
            displayCoins() {

                if(!this.coins)
                    return [];

                let start = (this.pagination.page - 1) * this.pagination.limit;
                let end = start + this.pagination.limit;

                return this.coins.slice(
                    start,
                    end,
                )
            },

            titleStack() {
                return [
                    'Coins'
                ]
            },
            ...mapState('Coins', [
                'coins',
                'pagination'
            ]),
            ...mapGetters('Coins', [
                'isLoading'
            ])
        },


        data() {
            return {
                coinColumns: [
                    {
                        field: 'id',
                        label: 'ID',
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
                        field: 'price',
                        label: 'Current Agg Price'
                    }
                ]
            }
        },


        methods: {
            changePage(page) {
                this.$store.commit('Coins/setCoinPage', page);
            },

            onClickCoin(coin) {
                this.$router.push({name: 'coins.view', params: {symbol: coin.symbol}})
            }
        },

        mounted() {
            if (!this.coins) {
                this.$store.dispatch('Coins/coinsRequest');
            }
        }
    }
</script>
