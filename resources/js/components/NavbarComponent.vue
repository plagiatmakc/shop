<template>
    <div>
        <nav class="navbar sticky-top navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <router-link :to="{path: '/'}" class="navbar-brand">Shop</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <router-link :to="{path: '/shop-cart'}" class="navbar-brand">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            Shopping cart
                        </router-link>
                        <!-- Authentication Links -->
                        <router-link :to="{ name: 'login' }" class="nav-link" v-if="!isLoggedIn">Login</router-link>
                        <router-link :to="{ name: 'register' }" class="nav-link" v-if="!isLoggedIn">Register</router-link>
                        <span v-if="isLoggedIn" class="custom-control-inline">
                            <img :src="'/storage/'+avatar" v-if="avatar != null">
                            <router-link :to="{ name: 'dashboard' }" class="nav-link" v-if="user_type !== 'Admin'"> Hi, {{name}}</router-link>
                            <router-link :to="{ name: 'admin' }" class="nav-link" v-if="user_type === 'Admin'"> Hi, {{name}}</router-link>
                            <router-link :to="{ name: 'support' , params: { room_id: ''+room+'' } }" class="nav-link" > Chat to support</router-link>
                        </span>
                        <li class="nav-link" v-if="isLoggedIn" @click="logout"> Logout</li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <!--<router-view @loggedIn="change"></router-view>-->
        </main>
    </div>
</template>

<script>
    import {bus} from '../app'
    export default {
        data() {
            return {
                name: null,
                avatar: null,
                user_type: 0,
                isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
                room: null,
            }
        },
        mounted() {
            this.setDefaults();
        },
        created() {
            bus.$on('isLoggedIn', () => {
                this.change();
            });
        },
        methods : {
            setDefaults() {
                if (this.isLoggedIn) {
                    this.user_type = 0;
                    let user = JSON.parse(localStorage.getItem('bigStore.user'));
                    this.name = user.first_name;
                    this.room = 'room_'+ user.email;
                    this.avatar = user.avatar;
                    if(user.roles)
                    {
                        let roles = user.roles;
                        this.user_type = roles[0].name;
                    }

                    console.log(this.user_type);
                }
            },
            change() {
                this.isLoggedIn = localStorage.getItem('bigStore.jwt') != null;
                this.setDefaults();
            },
            logout(){
                localStorage.removeItem('bigStore.jwt');
                localStorage.removeItem('bigStore.user');
                this.change();
                this.$router.push('/');
            }
        }
    }
</script>

<style scoped>
    img {
        border-radius: 25px;
    }
</style>