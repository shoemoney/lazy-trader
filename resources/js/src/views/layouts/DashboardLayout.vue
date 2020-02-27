<template>
    <div>
        <nav-bar></nav-bar>
        <aside-menu :menu="menu"></aside-menu>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import NavBar from '@/components/Layout/NavBar';
    import AsideMenu from "@/components/Layout/AsideMenu";

    export default {
        components: {
            AsideMenu,
            NavBar
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
                    }
                ]
            }
        },

        mounted() {
            console.log('mounted Admin Layout');
            //  class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded"
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
