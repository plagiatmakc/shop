<template>
    <div class="container">
        Home page
        {{last_added_products}}
        <div id="last_added">
            <div class="card-group">
               <div class="card col-md-2" v-for="product in last_added_products">
                    <img class="card-img-top" src="" alt="Card image cap">
                   <div v-if="product.product_images.length">
                       <img :src="'storage/'+ product.product_images[0].link_to_thumb + '?img=' + Math.random()" width="100%">
                   </div>
                   <div v-else>
                       <img src="/images/No_Image.png" width="100%">
                   </div>
                    <div class="card-body">
                        <router-link :to="{path: '/product/'+ product.id , query: {type: $router.currentRoute.query.type}}" class="card-title">{{product.name}}</router-link>
                        <p class="card-text">{{product.price}} {{product.currency}}</p>
                    </div>
               </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "HomePageComponent",
        data() {
            return {
                last_added_products: [],
            }
        },
        mounted() {
            this.getLastAddedProducts("/get-last-products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.type,);
        },
        methods: {
            getLastAddedProducts(url, items, currency) {
                window.axios.get(url,{
                    params: {
                        pagination: items,
                        type: currency,
                    }
                })
                    .then(response => {
                        this.last_added_products = response.data.data;
                        this.$router.push({
                            path: this.$router.path,
                            query: {
                                pagination: this.items_per_page,
                                page: this.pagination.current_page,
                                type: this.type_of_currency
                            }
                        });
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error.response.data.errors);
                    });
            },

        }
    }
</script>

<style scoped>
.card:hover {
    transform: scale(1.1);
}
</style>
