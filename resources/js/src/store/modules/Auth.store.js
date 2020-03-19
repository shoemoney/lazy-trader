import Cookies from 'js-cookie'
import Errors from '@base/modules/errors';

console.log(Cookies.get('access_token'));

const state = {
    access_token: Cookies.get('access_token') || '',
    status: '',
    hasLoadedOnce: false,
    errors: new Errors()
};

const getters = {
    isAuthenticated: state => !!state.access_token,
    authStatus: state => state.status,
    authErrors: state => state.errors,
};

const actions = {
    authRequest: ({commit, dispatch}, payload) => {
        let actionUrl = '/login';
        let remember = payload.remember ? payload.remember : false;
        let data = {
            'email': payload.email,
            'password': payload.password
        };

        if (payload.action === 'register') {
            actionUrl = '/register';
            data = {
                'name': payload.name,
                'email': payload.email,
                'password': payload.password,
                'password_confirmation': payload.password_confirmation
            }
        }
        if (payload.action === 'password-reset') {
            actionUrl = '/password/reset';
            data = {
                'token': payload.token,
                'email': payload.email,
                'password': payload.password,
                'password_confirmation': payload.password_confirmation
            }
        }


        return new Promise((resolve, reject) => {
            commit('authRequest');
            axios.post(actionUrl, data)
                .then((resp) => {
                    let access_token = 'Bearer ' + resp.data.access_token;

                    console.log('access token: ' + access_token);
                    Cookies.set('access_token', access_token, {expires: remember ? 365 : 1});
                    axios.defaults.headers.common['Authorization'] = access_token;

                    commit('authSuccess', access_token);
                    dispatch('User/userRequest', {}, {root: true});
                    resolve(access_token);
                })
                .catch((err) => {
                    console.log('auth failure');
                    commit('authError', err.response.data);
                    Cookies.remove('access_token');
                    reject(err);
                })
        })
    },
    authLogout: ({commit, dispatch}) => {
        Cookies.remove('access_token');
        return new Promise((resolve, reject) => {
            axios.post('/logout')
                .then((resp) => {
                    commit('authLogout');
                    resolve();
                })
                .catch((err) => {
                    commit('authError', err.response.data);
                    reject(err);
                });
        })
    }
};

const mutations = {
    authRequest: (state) => {
        state.status = 'loading';
    },
    authSuccess: (state, access_token) => {
        state.status = 'success';
        state.access_token = access_token;
        state.hasLoadedOnce = true;
    },
    authError: (state, err) => {
        let errors = err.errors ? err.errors : {};
        if (err.error === 'invalid_credentials' || err.error === 'invalid_grant') {
            errors.invalid_credentials = ['The user credentials were incorrect.'];
        }

        state.status = 'error';
        state.hasLoadedOnce = true;
        state.errors.record(errors);
    },
    authLogout: (state) => {
        state.access_token = '';
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
