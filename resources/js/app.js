import Vue from 'vue';
import Buefy from 'buefy';
import axios from 'axios';
import Cookies from 'js-cookie';
import TitleBar from "@/components/Layout/TitleBar";
import AsideMenuList from "@/components/Layout/AsideMenuList";

Vue.prototype.$http = window.axios = axios.create({
    baseURL: '/api/'
});

let bearer = Cookies.get('access_token');

if (bearer) {
    window.axios.defaults.headers.common['Authorization'] = bearer;
}
import Pusher from "pusher-js";
import Echo from "laravel-echo";
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '161c2a08817d4bee83e7', // TODO: Get from page.
    cluster: 'us2',
    forceTLS: true,
    auth:        {
        headers: {
            Authorization: bearer,
        },
    },
});

import { library } from '@fortawesome/fontawesome-svg-core'
// internal icons
import { faCheck, faCheckCircle, faInfoCircle, faExclamationTriangle, faExclamationCircle,
    faArrowUp, faAngleRight, faAngleLeft, faAngleDown,
    faEye, faEyeSlash, faCaretDown, faCaretUp, faUpload, faHome, faEllipsisV, faBars, faSignOutAlt, faChevronDown, faChevronUp, faTimes, faWallet, faChevronRight, faChevronLeft, faPlus, faExchangeAlt, faSlidersH, faRobot, faHistory, faSync, faCopy, faTachometerAlt, faUser, faCog, faEnvelope } from "@fortawesome/free-solid-svg-icons";
import { faBitcoin } from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faCheck, faCheckCircle, faInfoCircle, faExclamationTriangle, faExclamationCircle,
    faArrowUp, faAngleRight, faAngleLeft, faAngleDown,
    faEye, faEyeSlash, faCaretDown, faCaretUp, faUpload, faHome, faBitcoin, faEllipsisV, faBars, faSignOutAlt, faChevronDown, faChevronUp, faTimes, faWallet, faChevronRight, faChevronLeft, faPlus, faExchangeAlt, faSlidersH, faRobot, faHistory, faSync, faCopy, faTachometerAlt, faUser, faCog, faEnvelope);

import WebFont from 'webfontloader';

WebFont.load({
    google: {
        families: ['Montserrat:300,500,700']
    }
});
Vue.filter('currency', function (value) {
    return '$' + parseFloat(value).toFixed(2);
});

Vue.component('vue-fontawesome', FontAwesomeIcon);
Vue.component('title-bar', TitleBar);
Vue.component('aside-menu-list', AsideMenuList);

Vue.config.productionTip = false;

Vue.use(Buefy, {
    defaultIconComponent: 'vue-fontawesome',
    defaultIconPack: 'fas',
});

import VueApollo from 'vue-apollo'

Vue.use(VueApollo)

import router from './router';
import store from './src/store/store';
import App from '@/App';
import apolloProvider from './src/client/client';

new Vue({
    router,
    store,
    apolloProvider,
    el: '#app',
    created() {
        if (this.$store.getters.isAuthenticated) {
            this.$store.dispatch('userRequest');
        }
    },
    render: x => x(App)
});
