<template>
    <div class="content-wrapper">
        <nav v-show="isNavBarVisible" id="navbar-main" class="navbar">
            <div class="navbar-brand">
                <a class="navbar-item is-hidden-desktop" @click.prevent="menuToggleMobile">
                    <b-icon :icon="menuToggleMobileIcon"/>
                </a>
            </div>
            <div class="navbar-brand is-right">
                <div class="navbar-item navbar-item-menu-toggle is-hidden-desktop">
                    <a @click.prevent="menuNavBarToggle">
                        <b-icon :icon="menuNavBarToggleIcon"/>
                    </a>
                </div>
            </div>
            <div class="navbar-menu fadeIn animated faster" :class="{'is-active':isMenuNavBarActive}">
                <div class="navbar-end">
                    <nav-bar-menu class="has-divider">
                        <b-icon icon="bars"/>
                        <span>Sample Menu</span>
                        <div slot="dropdown" class="navbar-dropdown">
                            <a class="navbar-item">
                                <b-icon icon="sign-out-alt"/>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </nav-bar-menu>
                    <a class="navbar-item" title="Log out" @click="logout">
                        <b-icon icon="sign-out-alt"/>
                        <span>Log out</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
    import NavBarMenu from '@/components/Layout/NavBarMenu'
    import {mapState} from 'vuex';

    export default {
        name: 'NavBar',
        components: {
            NavBarMenu
        },
        data() {
            return {
                isMenuNavBarActive: false
            }
        },
        computed: {
            menuNavBarToggleIcon() {
                return (this.isMenuNavBarActive) ? 'times' : 'ellipsis-v'
            },
            menuToggleMobileIcon() {
                return this.isAsideMobileExpanded ? 'bars' : 'bars'
            },
            ...mapState([
                'isNavBarVisible',
                'isAsideMobileExpanded'
            ])
        },
        methods: {
            menuToggleMobile() {
                this.$store.commit('asideMobileStateToggle')
            },
            menuNavBarToggle() {
                this.isMenuNavBarActive = (!this.isMenuNavBarActive)
            },
            logout() {
                this.$store.dispatch('authLogout')
                    .then(() => {
                        this.$router.push('/')
                    })
            }
        }
    }
</script>
