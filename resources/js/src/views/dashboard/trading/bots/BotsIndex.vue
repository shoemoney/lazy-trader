<template>
    <section>
        <title-bar :title-stack="titleStack"/>


        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Running Bots</p>

                <div class="card-header-icon">
                    <b-button size="is-small" icon-left="plus" type="is-primary" @click="create">Create</b-button>
                </div>
            </div>
            <div class="card-content">
                <b-table :data="backtests" :columns="backtestColumns" :striped="true" :hoverable="true">

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>
                                    <vue-fontawesome icon="times" size="3x"></vue-fontawesome>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>

                </b-table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Paused Bots</p>
            </div>
            <div class="card-content">
                <b-table :data="backtests" :columns="backtestColumns" :striped="true" :hoverable="true">

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>
                                    <vue-fontawesome icon="times" size="3x"></vue-fontawesome>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>

                </b-table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Stopped Bots</p>
            </div>
            <div class="card-content">
                <b-table :data="backtests" :columns="backtestColumns" :striped="true" :hoverable="true">

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>
                                    <vue-fontawesome icon="times" size="3x"></vue-fontawesome>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>

                </b-table>
            </div>
        </div>
    </section>
</template>

<script>

    export default {

        computed: {
            titleStack() {
                return [
                    'Trading',
                    'Bots'
                ]
            },
        },


        data() {
            return {
                backtests: [],
                backtestColumns: [
                    {
                        field: 'name',
                        label: 'Name'
                    },
                    {
                        field: 'type',
                        label: 'Type',
                    },
                    {
                        field: 'market',
                        label: 'Market',
                    },
                    {
                        field: 'status',
                        label: 'Status'
                    },
                    {
                        field: 'actions',
                        label: 'Actions'
                    }
                ],
            }
        },


        methods: {
            create() {
                this.$router.push({name: 'trading.bots.create'});
            }
        }
    }
</script>
