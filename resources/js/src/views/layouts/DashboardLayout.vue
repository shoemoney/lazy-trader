<template>
    <div>
        <nav-bar></nav-bar>
        <aside-menu :menu="menu"></aside-menu>
        <router-view></router-view>
    </div>
</template>

<script>
    import NavBar from '@/components/NavBar';
    import AsideMenu from "@/components/AsideMenu";

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
                    }
                ];
            }
        },

        mounted() {
            console.log('mounted Admin Layout');
            //  class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded"
            let htmlClassList = document.getElementsByTagName('html').item(0).classList;
            htmlClassList.add('has-aside-left');
            htmlClassList.add('has-aside-mobile-transition');
            htmlClassList.add('has-navbar-fixed-top');
            htmlClassList.add('has-aside-expanded');
        },

        destroyed() {
            console.log('destroyed Admin Layout');

            let htmlClassList = document.getElementsByTagName('html').item(0).classList;
            htmlClassList.remove('has-aside-left');
            htmlClassList.remove('has-aside-mobile-transition');
            htmlClassList.remove('has-navbar-fixed-top');
            htmlClassList.remove('has-aside-expanded');
        }
    }
</script>
