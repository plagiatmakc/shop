<template>
    <div class="container">
        <div v-if="loading == true"  >
            <img src="/images/loading.gif" />
        </div>
        <div v-else>
            <router-link :to="{path: $route.fullPath, query: {pagination: 3}}">Per 3</router-link>
            <router-link :to="{path: $route.fullPath, query: {currency_type: 'eur'}}">EUR</router-link>
            <table class="table table-responsive table-sm">
                <thead>
                <tr >
                    <th scope="col">Id</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Operations</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in products">
                    <td width="1%">{{product.id}}</td>
                    <td v-if="product.product_images.length" width="2%">
                        <img :src="'/storage/'+ product.product_images[0].link_to_thumb + '?img=' + Math.random()" width="100%">
                    </td>
                    <td v-else width="2%">
                        <img src="/images/No_Image.png" width="50%">
                    </td>
                    <td width="20%">
                        <h5 style="width: 200px; text-overflow: ellipsis; white-space: nowrap;
overflow: hidden;">{{product.name}}</h5>
                    </td>
                    <td width="1%">{{product.price}}</td>
                    <td width="1%">{{product.currency}}</td>
                    <td width="1%">
                        <a class="btn btn-light btn-sm" @click="showProduct(product.id)" data-toggle="tooltip" title="Show product info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-light btn-sm" @click="editProduct(product.id)" data-toggle="tooltip" title="Edit product info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <!--<a class="btn btn-light btn-sm">Attributes</a>-->
                        <a class="btn btn-light btn-sm" @click="imageManage(product.id, product.name)" data-toggle="tooltip" title="Manage images">
                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-light btn-sm" @click="deleteProduct(product.id, product.name)" data-toggle="tooltip" title="Delete product">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
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
                this.getProductsWithPagination("/products?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                    this.$router.currentRoute.query.currency_type,
                );

            }
        },
        mounted() {
            // this.getProductsWithPagination(this.$router.currentRoute.query.pagination,this.$router.currentRoute.query.type);
            this.getProductsWithPagination("/products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.currency_type,
            );
        },
        created() {
             bus.$on('refreshPage', () => {
                 //this.getProductsWithPagination();
                 this.getProductsWithPagination("/products?page=" + this.$router.currentRoute.query.page,
                     this.$router.currentRoute.query.pagination,
                     this.$router.currentRoute.query.currency_type,
                 );
                 this.isModalVisible = false;
             });
        },
        methods: {
            getProductsWithPagination(url, items, currency) {
                this.loading = true;
                window.axios.get(url, {
                    params: {
                        pagination: items,
                        currency_type: currency,
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
                            currency_type: this.type_of_currency
                        }
                    });
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error.response.data);
                    this.loading = false;
                })
            },
            makePagination(data) {
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                };
                this.pagination = pagination
            },
            fetchPaginateProducts(url, items, currency) {
                this.getProductsWithPagination(url, items, currency);
            },
            showProduct(id) {
              this.product_id = id;
              this.paramCRUD = 'showProduct';
              this.openModal();
            },
            editProduct(id) {
                this.product_id = id;
                this.paramCRUD = 'editProduct';
                this.openModal();
            },
            imageManage(id,name) {
              this.product_id = id;
              this.product_name = name;
              this.paramCRUD = 'imageManage';
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
        transform: scale(1.5);
    }
</style>
