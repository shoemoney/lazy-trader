<template>
    <section>
        <title-bar :title-stack="titleStack"/>


        <div class="card">

            <div class="card-header">
                <div class="card-header-title">Trading Strategies</div>


                <div class="card-header-icon">
                    <b-button size="is-small" icon-left="plus" type="is-primary" @click="create">Add New</b-button>
                </div>
            </div>
            <div class="card-content">
                <b-table :data="strategies" :columns="strategyColumns" :striped="true" :hoverable="true">

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
                    'Strategies'
                ]
            },
        },


        data() {
            return {

                strategies: [],
                strategyColumns: [
                    {
                        field: 'name',
                        label: 'Name'
                    },
                    {
                        field: 'configuration',
                        label: 'Configuration',
                    },
                    {
                        field: 'updated_at',
                        label: 'Last Updated',
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
                this.$router.push({name: 'trading.strategy.create'});
            }
        }
    }
</script>
