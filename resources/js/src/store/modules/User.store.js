const state = {
    status: '',
    profile: null
};

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name,
};

const actions = {
    userRequest: ({commit, dispatch}) => {
        commit('userRequest')
        axios.get('/user')
            .then((resp) => {
                commit('userSuccess', resp.data);
            })
            .catch((err) => {
                commit('userError');
                // if resp is unauthorized, logout, to
                dispatch('Auth/authLogout', {}, {root: true})
            })
    },
};

const mutations = {
    userRequest: (state) => {
        state.status = 'loading';
    },
    userSuccess: (state, resp) => {
        state.status = 'success';
        state.profile = resp;
    },
    userError: (state) => {
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
