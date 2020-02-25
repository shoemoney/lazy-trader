import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import user from './modules/user'
import auth from './modules/auth'

export default new Vuex.Store({
    state: {
        /* NavBar */
        isNavBarVisible: true,

        /* Aside */
        isAsideVisible: true,
        isAsideExpanded: true,
        isAsideMobileExpanded: false
    },
    mutations: {
        /* A fit-them-all commit */
        basic (state, payload) {
            state[payload.key] = payload.value
        },

        /* Aside Mobile */
        asideMobileStateToggle (state, payload = null) {
            const htmlClassName = 'has-aside-mobile-expanded'

            let isShow

            if (payload !== null) {
                isShow = payload
            } else {
                isShow = !state.isAsideMobileExpanded
            }

            if (isShow) {
                document.documentElement.classList.add(htmlClassName)
            } else {
                document.documentElement.classList.remove(htmlClassName)
            }

            state.isAsideMobileExpanded = isShow
        },

        /* Aside Desktop Expanded */
        asideExpandedStateToggle (state, payload = null) {
            const htmlClassName = 'has-aside-expanded'

            let isShow

            if (payload !== null) {
                isShow = payload
            } else {
                isShow = !state.isAsideExpanded
            }

            if (isShow) {
                document.documentElement.classList.add(htmlClassName)
            } else {
                document.documentElement.classList.remove(htmlClassName)
            }

            state.isAsideExpanded = isShow
        }
    },
    modules: {
        user,
        auth
    }
})
