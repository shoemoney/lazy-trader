<template>
    <div>
        <title-bar :title-stack="titleStack"/>

        <div class="columns">
            <div class="column">
                <ProfileUpdateForm></ProfileUpdateForm>
            </div>
            <div class="column">

                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">Change Password</p>
                    </div>
                    <div class="card-content">
                        <user-avatar class="image has-max-width is-aligned-center"/>
                        <hr>
                        <b-field label="Name">
                            <b-input :value="userName" custom-class="is-static" readonly/>
                        </b-field>
                        <hr>
                        <b-field label="E-mail">
                            <b-input :value="userEmail" custom-class="is-static" readonly/>
                        </b-field>
                    </div>
                </div>
            </div>
        </div>
        <PasswordUpdateForm></PasswordUpdateForm>

        <section class="section is-main-section">
            <!--            <tiles>-->
            <!--                <profile-update-form class="tile is-child"/>-->
            <!--                <card-component title="Profile" icon="account" class="tile is-child">-->
            <!--                    <user-avatar class="image has-max-width is-aligned-center"/>-->
            <!--                    <hr>-->
            <!--                    <b-field label="Name">-->
            <!--                        <b-input :value="userName" custom-class="is-static" readonly/>-->
            <!--                    </b-field>-->
            <!--                    <hr>-->
            <!--                    <b-field label="E-mail">-->
            <!--                        <b-input :value="userEmail" custom-class="is-static" readonly/>-->
            <!--                    </b-field>-->
            <!--                </card-component>-->
            <!--            </tiles>-->
        </section>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import TitleBar from '@/components/Layout/TitleBar'
    import ProfileUpdateForm from '@/components/Profile/ProfileUpdateForm'
    import PasswordUpdateForm from '@/components/Profile/PasswordUpdateForm'
    import UserAvatar from '@/components/General/UserAvatar'

    export default {
        name: 'Profile',
        components: {UserAvatar, PasswordUpdateForm, ProfileUpdateForm, TitleBar},
        computed: {
            titleStack() {
                return [
                    'User',
                    'Profile'
                ]
            },
            ...mapState([
                'userName',
                'userEmail'
            ])
        }
    }
</script>
