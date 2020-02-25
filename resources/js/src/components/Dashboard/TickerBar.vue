<template>
    <div class="card card-tight">
        <div class="card-content">
            <div class="tickers">
                <router-link class="ticker" v-for="i in 20" :to="'coins'" v-bind:key="i">
                    <vue-fontawesome :icon="['fab', 'bitcoin']"/>
                    <span>Bitcoin</span>
                    <span>$9,872</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

