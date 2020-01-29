import Vue from 'vue';
import Vuex from "vuex";
Vue.use(Vuex);
import {bus} from "./app";


export default new Vuex.Store({
    // devtools: false,  //permission devtools
    state: {
        isUserLogin: localStorage.getItem('bigStore.jwt') != null,
        user: null,
        token: localStorage.getItem('bigStore.jwt') || null,
        isAdmin: false
    },
    getters: {
        getIsAdmin(state) {
            return state.isAdmin
        }
    },
    mutations: {
        setUser (state, user) {
            state.user = user;
        },
        setIsUserLogin(state, status) {
            state.isUserLogin = status;
            state.token = localStorage.getItem('bigStore.jwt') || null ;
            this.dispatch('prepareUser');
        },
        setLogout(state) {
            state.isUserLogin = false;
            state.user = null;
            state.token = null;
            state.isAdmin = false;
        },
        setIsAdmin(state, value) {
            state.isAdmin = value;
        }
    },
    computed() {
        this.dispatch('prepareUser');
    },
    watch: {
        token() {
            if (this.state.token !=null) {
                this.dispatch('prepareUser');
            }
        }
    },
    actions: {
        prepareUser() {
            if (this.state.token !=null) {
                window.axios.get('/api/user', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + this.state.token
                    }
                })
                    .then(response => {
                        let user = response.data;
                        this.commit('setUser', user);
                        bus.$emit('isLoggedIn');
                        this.dispatch('checkIsAdmin');

                    }).catch(error => {
                    localStorage.removeItem('bigStore.jwt');
                    document.cookie = "laravel_session=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    this.commit('setLogout');
                    bus.$emit('isLoggedIn');
                    this.$router.push('/');
                    });
            }
        },
        checkIsAdmin() {
            window.axios.get('/api/is-admin', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + this.state.token
                }
            })
                .then(response => {
                    this.commit('setIsAdmin', response.data);
                    bus.$emit('isLoggedIn');
                });
        }
    }
});
