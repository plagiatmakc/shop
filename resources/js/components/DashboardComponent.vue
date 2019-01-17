<template>
    <div class="container">
        Dashboard
        <router-link to="/dashboard/orders">My orders</router-link>

        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "DashboardComponent"
    }
</script>

<style scoped>

</style>
