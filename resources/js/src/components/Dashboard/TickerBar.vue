<template>
    <div class="card card-tight">
        <div class="card-content">
            <div class="ticker-wrapper">
                <div class="tickers" v-if="!isLoading">
                    <router-link class="ticker" v-for="coin in rankedCoins" :to="{name: 'coins.view', params: {symbol: coin.symbol}}" v-bind:key="coin.id">
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
    import {mapGetters, mapState} from "vuex";

    export default {

        computed: {
            ...mapState('TickerBar', [
                'rankedCoins'
            ]),
            ...mapGetters('TickerBar', [
                'isLoading'
            ])
        },

        mounted() {
            if(!this.rankedCoins) {
                this.$store.dispatch('TickerBar/rankedCoinsRequest');
            }
        }

    }
</script>
