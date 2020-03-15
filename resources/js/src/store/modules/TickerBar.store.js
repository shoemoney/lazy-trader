const state = {
    rankedCoins: null,
    status: ''
};

const getters = {
    isLoading: state => state.status === 'loading'
};

const actions = {
    rankedCoinsRequest: ({commit, dispatch}) => {
        commit('rankedCoinsRequest')
        axios.get('/coins/top')
            .then((resp) => {
                commit('rankedCoinsSuccess', resp.data);
            })
            .catch((err) => {
                console.log(err);
                commit('rankedCoinsError');
            })
    },
};

const mutations = {
    rankedCoinsRequest: (state) => {
        state.status = 'loading';
    },
    rankedCoinsSuccess: (state, resp) => {
        state.status = 'success';

        // // Leave all existing ranked coin channels.
        // if (state.rankedCoins !== null) {
        //     state.rankedCoins.forEach((coin) => {
        //         console.log(coin);
        //         Echo.leave('AggregatePriceChange.' + coin.id);
        //     });
        // }

        state.rankedCoins = resp;
        //
        // // Join new ranked coin channels.
        // state.rankedCoins.forEach((coin, key) => {
        //     Echo.private('AggregatePriceChange.' + coin.id)
        //         .listen('AggregatePriceChange', (e) => {
        //             console.log('update for ' + key + ' => ' + e.price);
        //             state.rankedCoins[key].price = e.price;
        //         });
        // });
    },
    rankedCoinsError: (state) => {
        state.status = 'error';
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
