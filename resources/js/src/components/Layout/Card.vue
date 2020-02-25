<template>
    <div class="card">
        <div class="card-header" v-if="hasCardHeader">
            <div class="card-title">
                {{ cardTitle }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            cardTitle: {
                type: String,
                default: null
            }
        },

        computed: {
            hasCardHeader() {
                return this.cardTitle;
            }
        }
    }
</script>
