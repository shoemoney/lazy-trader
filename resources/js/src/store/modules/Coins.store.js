const state = {
    coins: null,
    pagination: {
        page: 1,
        limit: 50,
        total: 0,
    },
    status: ''
};

const getters = {
    isLoading: state => state.status === 'loading'
};

const actions = {
    coinsRequest: ({commit, dispatch}) => {
        commit('coinsRequest')
        axios.get('/coins')
            .then((resp) => {
                commit('coinsSuccess', resp.data);
            })
            .catch((err) => {
                commit('coinsError');
            })
    },
};

const mutations = {
    coinsRequest: (state) => {
        state.status = 'loading';
    },
    coinsSuccess: (state, resp) => {
        state.status = 'success';
        state.coins = resp;
    },
    coinsError: (state) => {
        state.status = 'error';
    },

    setCoinPage(state, data) {
        state.pagination.page = data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
