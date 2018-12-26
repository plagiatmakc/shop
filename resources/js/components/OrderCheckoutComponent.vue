<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div>
                    <div v-if="!isLoggedIn">
                        <h2>You need to login to continue</h2>
                        <button class="col-md-4 btn btn-primary float-left" @click="">Login</button>
                        <button class="col-md-4 btn btn-danger float-right" @click="">Create an account</button>
                    </div>
                    <div v-if="isLoggedIn">
                        <div class="row">
                            <label for="address" class="col-md-3 col-form-label">Delivery Address</label>
                            <div class="col-md-9">
                                <input id="address" type="text" class="form-control" v-model="address" required>
                            </div>
                        </div>
                        <br>
                        <button class="col-md-4 btn btn-sm btn-success float-right" v-if="isLoggedIn" @click="placeOrder">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderCheckoutComponent",
        data() {
            return {
                address: '',
                isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
            }
        },
        mounted() {
            // this.checkAuthorize();
        },
        methods: {
            checkAuthorize() {
              window.axios.post('/api/login', {
                  email: 'plagiatmakc@gmail.com',
                  password: 'plagiatmakc6252'
              })
                  .then(response => {
                      console.log(response.data);
                      localStorage.setItem('bigStore.user', JSON.stringify(response.data.user));
                      localStorage.setItem('bigStore.jwt', response.data.token);
                  })
                  .catch(error => {
                      console.log(error.response);
                  })
            },
            placeOrder() {
                window.axios.get('/api/users', {
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}
                    })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            }
        }
    }
</script>

<style scoped>

</style>