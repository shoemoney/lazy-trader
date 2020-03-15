<template>
    <div>
        <nav-bar></nav-bar>
        <aside-menu :menu="menu"></aside-menu>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <notifications/>
    </div>
</template>

<script>
    import NavBar from '@/components/Layout/NavBar';
    import AsideMenu from "@/components/Layout/AsideMenu";
    import Notifications from "../../components/Layout/Notifications";

    export default {
        components: {
            AsideMenu,
            NavBar,
            Notifications
        },

        computed: {
            menu() {
                return [
                    {
                        to: {
                            name: 'dashboard'
                        },
                        icon: 'home',
                        label: 'Dashboard'
                    },
                    {
                        to: {
                            name: 'coins'
                        },
                        icon: ['fab', 'bitcoin'],
                        label: 'Coins'
                    },
                    {
                        to: {
                            name: 'portfolio'
                        },
                        icon: 'wallet',
                        label: 'Portfolio'
                    },
                    {
                        to: {
                            name: 'trading'
                        },
                        icon: 'exchange-alt',
                        label: 'Trading',
                        menu: [

                            {
                                to: {
                                    name: 'trading'
                                },
                                icon: 'tachometer-alt',
                                label: 'Overview'
                            },
                            {
                                to: {
                                    name: 'trading.strategy'
                                },
                                icon: 'sliders-h',
                                label: 'Strategies'
                            },
                            {
                                to: {
                                    name: 'trading.backtesting'
                                },
                                icon: 'history',
                                label: 'Backtesting'
                            },
                            {
                                to: {
                                    name: 'trading.bots'
                                },
                                icon: 'robot',
                                label: 'Bots'
                            },
                        ]
                    }
                ]
            }
        },

        mounted() {
            let htmlClassList = document.getElementsByTagName('html').item(0).classList;
            htmlClassList.add('has-aside-left');
            htmlClassList.add('has-aside-mobile-transition');
            htmlClassList.add('has-aside-expanded');
        },

        destroyed() {
            console.log('destroyed Admin Layout');

            let htmlClassList = document.getElementsByTagName('html').item(0).classList;
            htmlClassList.remove('has-aside-left');
            htmlClassList.remove('has-aside-mobile-transition');
            htmlClassList.remove('has-aside-expanded');
        }
    }
</script>
