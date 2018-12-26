<template>
    <div class="container">
    {{cart}}
        <p v-if="cart.items == null || cart.items.length == 0">Your cart is empty!!!</p>
        <div v-else>
            <div v-if="loading == true">
                <img src="/public/images/loading.gif">
            </div>
            <table class="table" v-else>
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Summary</th>
                    <th scope="col">Operations</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="items in cart.items" :id="'row_' + items.item.id">
                    <td style="max-width: 250px">{{items.item.name}}</td>
                    <td>{{items.item.price}}</td>
                    <td>{{items.qty}}</td>
                    <td>{{items.price}}</td>
                    <td>
                        <div>
                            <a class="btn btn-info btn-sm" style="border-radius: 25px;" data-toggle="tooltip"
                               data-placement="top" title="Increment product" @click="addToCart(items.item.id)">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-warning btn-sm" data-toggle="tooltip"
                               data-placement="top" title="Decrement product"
                               style="border-radius: 25px;" @click="subFromCart(items.item.id)">
                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" data-toggle="tooltip"
                               data-placement="top" title="Remove product completely"
                               @click="removeItemFromCart(items.item.id)" >
                                Remove
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <hr>
            <div >
                <h3 class="text-right">Total price: {{cart.totalPrice}}</h3>
            </div>
            <div class="text-right">
                <!--<a href="/order-checkout" class="btn btn-info btn-lg" style="border-radius: 25px;">Make order ></a>-->
                <router-link :to="{path: '/order-checkout'}" class="btn btn-info btn-lg" style="border-radius: 25px;"
                >Make order ></router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShopCartComponent",
        data() {
            return {
                'cart': [],
                loading: false,
            }
        },
        mounted() {
            this.getCartItems();
        },
        methods: {
            getCartItems() {
                this.loading = true;
                window.axios.get('/cart')
                    .then(response => {
                        console.log(response.data);
                        this.cart = response.data;
                        this.loading = false;
                    })
                    .catch( error => {
                        console.log(error.response.statusText);
                        this.loading = false;
                    });
            },
            addToCart(id) {
                window.axios.get('/add-to-cart/'+id)
                    .then(response => {
                        this.getCartItems();
                    })
                    .catch(error => {
                        console.log(error.response.statusText);
                    })
            },
            subFromCart(id) {
                window.axios.get('/del-from-cart/' + id)
                    .then(response => {
                        this.getCartItems();
                    })
                    .catch(error => {
                        console.log(error.response.statusText);
                    });
            },
            removeItemFromCart(id) {
                window.axios.get('/remove-from-cart/' + id)
                    .then(response => {
                        this.getCartItems();
                    })
                    .catch(error => {
                        console.log(error.response.statusText);
                    });
            }
        }
    }
</script>

<style scoped>

</style>