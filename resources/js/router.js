import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from "@/views/dashboard/Dashboard";
import CoinIndex from "@/views/dashboard/coins/CoinIndex";
import CoinView from "@/views/dashboard/coins/CoinView";
import DashboardLayout from "@/views/layouts/DashboardLayout";
import Login from "@/views/auth/Login";
import Register from "@/views/auth/Register";
import PasswordEmail from "@/views/auth/password/PasswordEmail";
import PasswordReset from "@/views/auth/password/PasswordReset";
import store from '@/store/store';
import AuthLayout from "./src/views/layouts/AuthLayout";

Vue.use(Router)

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return
    }
    next('/dashboard')
};

const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
        return
    }
    next('/')
};


const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'auth-layout',
            component: AuthLayout,
            beforeEnter: ifNotAuthenticated,
            children: [
                {
                    path: '/',
                    name: 'login',
                    component: Login,
                    beforeEnter: ifNotAuthenticated,
                },
                {
                    path: '/register',
                    name: 'register',
                    component: Register,
                    beforeEnter: ifNotAuthenticated,
                },
                {
                    path: '/password/reset/',
                    name: 'password-email',
                    component: PasswordEmail,
                    beforeEnter: ifNotAuthenticated,
                },
                {
                    path: '/password/reset/:token',
                    name: 'password-reset',
                    component: PasswordReset,
                    beforeEnter: ifNotAuthenticated,
                },
            ]
        },

        {
            path: '/',
            name: 'dashboard-layout',
            component: DashboardLayout,
            beforeEnter: ifAuthenticated,
            children: [
                {
                    path: 'dashboard',
                    name: 'dashboard',
                    component: Dashboard,
                },
                {
                    path: 'coins',
                    name: 'coins',
                    component: CoinIndex,
                    meta: {
                        auth: true
                    }
                },

                {
                    path: 'coins/:symbol',
                    name: 'view',
                    component: CoinView
                }
            ]
        }
    ]
});

router.afterEach(() => {
    // Loading screen
    const appLoading = document.getElementById('loading-bg');
    if (appLoading) {
        appLoading.style.display = 'none';
    }
});

export default router;
