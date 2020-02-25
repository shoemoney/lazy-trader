<template>
    <section>
        <title-bar :title-stack="titleStack"/>
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Total Strategies
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <b-table :data="coins" :columns="coinColumns" :striped="true" :hoverable="true" :loading="isLoading">

                        <template slot="empty">
                            <section class="section">
                                <div class="content has-text-grey has-text-centered">
                                    <p>
                                        <b-icon
                                            icon="times"
                                            size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>Nothing here.</p>
                                </div>
                            </section>
                        </template>

                    </b-table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {

        computed: {
            titleStack() {
                return [
                    'Coins'
                ]
            }
        },

        data() {
            return {
                coins: [],
                coinColumns: [
                    {
                        field: 'id',
                        label: 'ID',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    },
                    {
                        field: 'num_markets',
                        label: '# of Markets',
                    },
                    {
                        field: 'price',
                        label: 'Current Agg Price'
                    }
                ],
                isLoading: true
            }
        }

    }
</script>
