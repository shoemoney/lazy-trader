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
                    <nav-bar-menu class="has-user-avatar">
                        <user-avatar/>
                        <div class="is-user-name">
                            <span>{{ profile ? profile.email : '' }}</span>
                        </div>

                        <div slot="dropdown" class="navbar-dropdown">
                            <router-link class="navbar-item" :to="{name: 'user.profile'}">
                                <b-icon icon="user" custom-size="default"></b-icon>
                                <span>My Profile</span>
                            </router-link>
                            <hr class="navbar-divider">
                            <a class="navbar-item" >
                                <b-icon icon="sign-out-alt" custom-size="default"></b-icon>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </nav-bar-menu>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
    import NavBarMenu from '@/components/Layout/NavBarMenu'
    import {mapState} from 'vuex';
    import UserAvatar from "../General/UserAvatar";

    export default {
        name: 'NavBar',
        components: {
            NavBarMenu, UserAvatar
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
            ...mapState('Dashboard', [
                'isNavBarVisible',
                'isAsideMobileExpanded'
            ]),
            ...mapState('User', [
                'profile'
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
        },
        mounted() {
            if(!this.profile) {
                this.$store.dispatch('User/userRequest');
            }
        }
    }
</script>
