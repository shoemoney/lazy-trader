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
        state.rankedCoins = resp;
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
