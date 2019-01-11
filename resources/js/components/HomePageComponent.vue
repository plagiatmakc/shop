<template>

   <div>
       <shop-menu-categories></shop-menu-categories>

        <div class="form-inline">
            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button"
                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pagination
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <router-link class="dropdown-item"
                                 :to="{ path: $router.path,
                                        query:{ pagination: 3,
                                                type: $router.currentRoute.query.type
                                               }
                                 }"
                    >per 3</router-link>
                    <router-link class="dropdown-item"
                                 :to="{ path: $router.path,
                                        query:{ pagination: 5,
                                                type: $router.currentRoute.query.type
                                               }
                                 }"
                    >per 5</router-link>
                    <router-link class="dropdown-item"
                                 :to="{ path: $router.path,
                                        query:{ pagination: 10,
                                                type: $router.currentRoute.query.type
                                               }
                                 }"
                    >per 10</router-link>
                </div>
            </div>

            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button" data-display="static"
                   id="dropdownMenuLinkCurrency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Currency
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkCurrency">
                    <router-link  :class=" $router.currentRoute.query.type === 'usd' ? 'text-danger dropdown-item' : 'dropdown-item' "
                                 :to="{ path: $router.path,
                                        query:{ type: 'usd',
                                                pagination: $router.currentRoute.query.pagination
                                               }
                                 }"
                    >USD</router-link>
                    <router-link :class="$router.currentRoute.query.type === 'eur' ? 'text-danger dropdown-item' : 'dropdown-item' "
                                 :to="{ path: $router.path,
                                        query:{ type: 'eur',
                                                pagination: $router.currentRoute.query.pagination
                                              }
                                 }"
                    >EUR</router-link>
                    <router-link :class="$router.currentRoute.query.type === 'uah' ? 'text-danger dropdown-item' : 'dropdown-item' "
                                 :to="{ path: $router.path,
                                        query:{ type: 'uah',
                                                pagination: $router.currentRoute.query.pagination
                                               }
                                 }"
                    >UAH</router-link>
                </div>
            </div>
        </div>

        <div class="container">
            <!--Home page-->
            <!--{{last_added_products}}-->

            <div id="last_added" style="border: solid 1px ">
                <h4 style="padding-top: 10px; padding-left: 10px; padding-bottom: 0px; margin-bottom: -15px;">
                    Last added
                </h4>
                <hr>
                <div class="card-group">
                   <div class="card col-md-2" v-for="product in last_added_products">
                       <div v-if="product.product_images.length">
                           <img class="card-img-top" :src="'/storage/'+ product.product_images[0].link_to_thumb + '?img=' + Math.random()" width="100%">
                       </div>
                       <div v-else>
                           <img class="card-img-top" src="/images/No_Image.png" width="100%">
                       </div>
                        <div class="card-body">
                            <router-link class="card-title"
                                         :to="{ path: '/product/'+ product.id ,
                                                query: { type: $router.currentRoute.query.type }
                                               }"
                            >
                                <div class="text">
                                    {{product.name}}
                                </div>
                            </router-link>
                            <p class="card-text">{{product.price}} {{product.currency}}</p>
                        </div>
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
                currency: '',
                items_per_page: '',
            }
        },
        mounted() {
            this.getLastAddedProducts(
                "/get-last-products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.type
            );
        },
        watch: {
            '$route'(to, from) {
                this.getLastAddedProducts(
                    "/get-last-products?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                    this.$router.currentRoute.query.type
                );
            }
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
                        this.currency = currency;
                        this.items_per_page = items;
                        this.$router.push({
                            path: this.$router.path,
                            query: {
                                pagination: this.items_per_page,
                                page: response.data.current_page,
                                type: this.currency,
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
        transform: scale(1.02);
    }
    .text {
        display: block;
        max-width: 200px;
        height: 200px;
        overflow-y: hidden;
        white-space: pre-line;
        text-overflow: ellipsis;
    }

</style>
