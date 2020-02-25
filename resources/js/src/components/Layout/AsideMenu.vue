<template>
    <aside
        v-show="isAsideVisible"
        :class="['aside', 'is-placed-left', {
            'is-expanded': isAsideExpanded
        }]">
        <div class="image">
            <a href="/">
                <div v-if="isAsideExpanded"><img src="@assets/logo.png"></div>
                <div v-else><h4>L</h4></div>
            </a>
        </div>
        <div class="menu is-menu-main">
            <aside-menu-list
                @menu-click="menuClick"
                :menu="menu"/>
        </div>

        <a v-on:click="toggleAside()" class="toggle-button">
            <vue-fontawesome :icon="isAsideExpanded ? 'chevron-left' : 'chevron-right' " ></vue-fontawesome>
        </a>
    </aside>
</template>


<script>
    import {mapState} from 'vuex'
    import AsideMenuList from "./AsideMenuList";

    export default {
        name: 'AsideMenu',
        components: {
            AsideMenuList
        },
        props: {
            menu: {
                type: Array,
                default: () => []
            }
        },
        computed: {
            ...mapState([
                'isAsideVisible',
                'isAsideExpanded'
            ])
        },
        methods: {
            menuClick(item) {
                //
            },
            toggleAside() {
                this.$store.commit('asideExpandedStateToggle')
            }
        }
    }
</script>
