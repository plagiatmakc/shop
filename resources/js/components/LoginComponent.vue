<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary mr-5" @click="handleSubmit">
                                        Login
                                    </button>
                                    <router-link :to="{name: 'register'}">Forgot Your Password?</router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {bus} from '../app'
    export default {
        data() {
            return {
                email: "",
                password: ""
            }
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault();
                if (this.password.length > 0) {
                    let email = this.email;
                    let password = this.password;

                    window.axios.post('/api/login', {email, password}).then(response => {
                        let user = response.data.user;
                        // let is_admin = user.is_admin;


                        // localStorage.setItem('bigStore.user', JSON.stringify(user));
                        localStorage.setItem('bigStore.jwt', response.data.token);

                        if (localStorage.getItem('bigStore.jwt') != null) {
                            this.$store.commit('setIsUserLogin', true);
                            this.setUserToStore();
                            // bus.$emit('isLoggedIn');
                            // this.$router.push('/');
                        }
                    });
                }
            },
            setUserToStore () {
                window.axios.get('http://shop.loc/api/user', {
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}
                })
                    .then(response => {
                        console.log(response.data);
                        let user = response.data;
                        this.$store.commit('setUser', user);
                        bus.$emit('isLoggedIn');
                        this.$router.push('/');
                    })
                    .catch(error => {
                        // console.log(error.response);
                        // console.log(localStorage.getItem('bigStore.jwt'));
                    });

            }
        }
    }
</script>

<style scoped>

</style>