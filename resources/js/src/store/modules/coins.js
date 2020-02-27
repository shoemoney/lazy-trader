const state = {
    coins: null,
    pagination: {
        page: 1,
        limit: 50,
        total: 0,
    }
};

const getters = {
    getCoins() {
        return state.coins;
    },
    getCoinPagination() {
        return state.pagination;
    }
};

const mutations = {
    setCoins(state, data) {
        state.coins = data;
        state.pagination.total = data.length;
    },

    setCoinPage(state, data) {
        state.pagination.page = data;
    }
};

const actions = {
     fetchCoins: async ({commit, dispatch}) => {
        return axios.get('/coins').then((response) => {
            console.log('CACHE BUST')
            commit('setCoins', response.data);
        });
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
