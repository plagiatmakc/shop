<template>
    <div class="container">
        <div>
            Category products loaded <br>
            {{products}}
            <div class="card-group">
                <div class="card col-md-2" v-for="product in products">
                    <div v-if="product.product_images.length">
                        <img class="card-img-top" :src="'/storage/'+ product.product_images[0].link_to_thumb + '?img=' + Math.random()" width="100%">
                    </div>
                    <div v-else>
                        <img class="card-img-top" src="/images/No_Image.png" width="100%">
                    </div>
                    <div class="card-body">
                        <router-link :to="{path: '/product/'+ product.id , query: {currency_type: $router.currentRoute.query.currency_type}}" class="card-title">{{product.name}}</router-link>
                        <p class="card-text">{{product.price}} {{product.currency}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['category_id'],
        name: "CategoryProductsComponent",
        data() {
            return {
                products: [],
            }
        },
        mounted() {
            this.getProductsByCategory("/get-last-products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.currency_type);
        },
        watch: {
            '$route'(to, from) {
                this.getProductsByCategory("/get-last-products?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                    this.$router.currentRoute.query.currency_type
                );
            }
        },
        methods: {
            getProductsByCategory(url, items, currency) {
                window.axios.get(url,{
                    params: {
                        category: this.category_id,
                        pagination: items,
                        currency_type: currency,
                    }
                })
                    .then(response => {
                        this.products = response.data.data;
                    })
                    .catch(error => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>
    .card:hover {
        transform: scale(1.02);
    }
</style>
