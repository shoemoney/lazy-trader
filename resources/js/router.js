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
import PortfolioIndex from "./src/views/dashboard/portfolio/PortfolioIndex";
import TradingIndex from "./src/views/dashboard/trading/TradingIndex";
import StrategyIndex from "./src/views/dashboard/trading/strategies/StrategyIndex";
import StrategyCreate from "./src/views/dashboard/trading/strategies/StrategyCreate";
import BacktestingIndex from "./src/views/dashboard/trading/backtesting/BacktestingIndex";
import BacktestingView from "./src/views/dashboard/trading/backtesting/BacktestingView";
import BacktestingCreate from "./src/views/dashboard/trading/backtesting/BacktestingCreate";
import BotsView from "./src/views/dashboard/trading/bots/BotsView";
import BotsCreate from "./src/views/dashboard/trading/bots/BotsCreate";
import BotsIndex from "./src/views/dashboard/trading/bots/BotsIndex";
import Profile from "./src/views/dashboard/profile/Profile";

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
                    component: CoinIndex
                },

                {
                    path: 'coins/:symbol',
                    name: 'coins.view',
                    component: CoinView
                },

                {
                    path: 'portfolio',
                    name: 'portfolio',
                    component: PortfolioIndex
                },

                {
                    path: 'trading',
                    name: 'trading',
                    component: TradingIndex
                },
                {
                    path: 'trading/strategy',
                    name: 'trading.strategy',
                    component: StrategyIndex
                },
                {
                    path: 'trading/strategy/create',
                    name: 'trading.strategy.create',
                    component: StrategyCreate
                },
                {
                    path: 'trading/backtesting',
                    name: 'trading.backtesting',
                    component: BacktestingIndex
                },
                {
                    path: 'trading/backtesting/create',
                    name: 'trading.backtesting.create',
                    component: BacktestingCreate
                },
                {
                    path: 'trading/backtesting/:id',
                    name: 'trading.backtesting.view',
                    component: BacktestingView
                },
                {
                    path: 'trading/bots',
                    name: 'trading.bots',
                    component: BotsIndex
                },
                {
                    path: 'trading/bots/create',
                    name: 'trading.bots.create',
                    component: BotsCreate
                },
                {
                    path: 'trading/bots/:id',
                    name: 'trading.bots.view',
                    component: BotsView
                },
                {
                    path:'user/profile',
                    name: 'user.profile',
                    component: Profile
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
