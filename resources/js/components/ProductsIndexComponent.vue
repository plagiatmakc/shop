<template>
    <div class="container">
        <!--<button class="btn btn-light btn-sm"@click="getProductsWithPagination(3, type_of_currency)">per 3</button>-->
        <router-link :to="{path: $route.fullPath, query: {pagination: 3}}">Per 3</router-link>
        <!--<button class="btn btn-light btn-sm"@click="getProductsWithPagination(items_per_page, 'usd')">USD</button>-->
        <router-link :to="{path: $route.fullPath, query: {type: 'eur'}}">EUR</router-link>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Currency</th>
                <th scope="col">Operations</th>
            </tr>
            </thead>
            <tbody>

            <tr v-for="product in products">
                <td>{{product.id}}</td>
                <td>{{product.name}}</td>
                <td>{{product.price}}</td>
                <td>{{product.currency}}</td>
                <td>
                    <a class="btn btn-light btn-sm">Show</a>
                    <a class="btn btn-light btn-sm" @click="editProduct(product.id)">Edit</a>
                    <a class="btn btn-light btn-sm">Attributes</a>
                    <a class="btn btn-light btn-sm" @click="deleteProduct(product.id, product.name)">
                        Delete
                    </a>
                </td>
            </tr>
            </tbody>
        </table>


        <div class="pagination">
            <!--<router-link :to="{ path: $route.fullPath, query: {page: pagination.current_page -1 }}">-->
            <button class="page-item btn btn-light btn-sm"
                    v-on:click="fetchPaginateProducts(pagination.prev_page_url, items_per_page, type_of_currency)"
                    :disabled="!pagination.prev_page_url || loading"
            >
                Prev
            </button>
            <!--</router-link>-->

            <span class="page-item btn btn-outline-secondary btn-sm disabled">
        page {{pagination.current_page}} of {{pagination.last_page}}
        </span>
            <!--<router-link :to="{ path: $route.fullPath, query: {page: pagination.current_page +1}}">-->
            <button class="page-item btn btn-light btn-sm"
                    v-on:click="fetchPaginateProducts(pagination.next_page_url, items_per_page, type_of_currency)"
                    :disabled="!pagination.next_page_url || loading"
            >
                Next
            </button>
            <!--</router-link>-->
        </div>

        <productCRUDModal id="modalCreate"
                          v-bind:paramCRUD="paramCRUD"
                          v-bind:product_name="product_name"
                          v-bind:product_id="product_id"
                          v-if="isModalVisible"
                          @close="closeModal"
        />
    </div>
</template>

<script>
    import ProductCRUDModal from './ProductCRUDModal.vue';
    import {bus} from '../app'

    export default {
        name: "ProductsIndexComponent",
        components: {
            "productCRUDModal": ProductCRUDModal,
        },
        data() {
            return {
                products: [],
                pagination: [],
                items_per_page: '',
                type_of_currency: '',
                url: '/products',
                product_id: '',
                isModalVisible: false,
                paramCRUD: '',
                product_name: '',
                loading: false,
            }
        },
        watch: {
            '$route'(to, from) {
                // this.getProductsWithPagination(this.$router.currentRoute.query.pagination,
                //                                 this.$router.currentRoute.query.type);
                this.getPart("http://shop.loc/products?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                    this.$router.currentRoute.query.type,
                );

            }
        },
        mounted() {
            // this.getProductsWithPagination(this.$router.currentRoute.query.pagination,this.$router.currentRoute.query.type);
            this.getPart("http://shop.loc/products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.type,
            );
        },
        created() {
             bus.$on('refreshPage', () => {
                 //this.getProductsWithPagination();
                 this.getPart("http://shop.loc/products?page=" + this.$router.currentRoute.query.page,
                     this.$router.currentRoute.query.pagination,
                     this.$router.currentRoute.query.type,
                 );
                 this.isModalVisible = false;
             });
        },
        methods: {
            getProductsWithPagination(items, currency) {
                this.loading = true;
                window.axios.get('/products', {
                    params: {
                        pagination: items,
                        type: currency
                    }
                })
                    .then(response => {
                        console.log(response);
                        this.products = response.data.data;
                        this.makePagination(response.data);
                        this.loading = false;
                        this.items_per_page = items;
                        this.type_of_currency = currency;
                        // this.$router.push({path: this.$router.path, query: {pagination: this.products.length , page: this.pagination.current_page}});
                    })
                    .catch(error => {
                        console.log(error.data);
                        this.loading = false;
                    })
            },
            getPart(url, items, currency) {
                this.loading = true;
                window.axios.get(url, {
                    params: {
                        pagination: items,
                        type: currency,
                    }
                })
                    .then(response => {
                        console.log(response);
                        this.products = response.data.data;
                        this.makePagination(response.data);
                        this.items_per_page = items;
                        this.type_of_currency = currency;
                        this.$router.push({
                            path: this.$router.path,
                            query: {
                                pagination: this.items_per_page,
                                page: this.pagination.current_page,
                                type: this.type_of_currency
                            }
                        });
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error.data);
                        this.loading = false;
                    })
            },
            makePagination(data) {
                var pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                };
                this.pagination = pagination
            },
            fetchPaginateProducts(url, items, currency) {
                this.getPart(url, items, currency);
            },
            editProduct(id) {
                this.product_id = id;
                this.paramCRUD = 'editProduct';
                this.openModal();
            },
            deleteProduct(id, name) {
                this.product_id = id;
                this.product_name = name;
                this.paramCRUD = 'deleteProduct';
                this.openModal();
            },
            openModal() {
                // $(".container").css("position", "fixed");
                this.isModalVisible = true;
            },
            closeModal() {
                // $(".container").css("position", "relative");
                this.isModalVisible = false;
            },
        }


    }
</script>

<style scoped>
    a.btn:hover {
        color: #227dc7;
    }
</style>
