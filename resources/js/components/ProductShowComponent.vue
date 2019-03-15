<template>
    <div class="container">
        <div v-if="loading === true"  >
            <img src="/images/loading.gif" />
        </div>
        <div v-else>
            <label>Name: </label>
            <label  style="max-width: 300px" >{{name}}</label><br/>
            <label>Price: </label>
            <label>{{price}} {{currency}}</label><br/>
            <label v-if="categories_of_product.length >0">Selected categories:</label><br/>
                <ul v-if="categories_of_product.length >0">
                    <li v-for="category in categories_of_product">{{category.title}}</li>
                </ul>
            <label v-if="images_of_product.length >0">Images:</label><br/>
            <ul v-if="images_of_product.length >0" style="list-style-type:none">
                <li v-for="image in images_of_product"><img class="thumbnail" :src="'/storage/'+image.link_to_thumb + '?img=' + Math.random()" width="30%"></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductUpdateComponent",
        props: ['product_id'],
        data() {
            return {
                name: '',
                price: '',
                currency: '',
                categories_of_product:[],
                images_of_product:[],
                loading: false,

            }
        },
        mounted() {
            this.getProductInfo(this.product_id);
        },
        methods: {
            getProductInfo(id) {
                this.loading = true;
                window.axios.get('/products/'+id)
                    .then(response => {
                        console.log(response.data);
                        this.name = response.data.name;
                        this.price = response.data.price;
                        this.currency = response.data.currency;
                        for(let index in response.data.categories) {
                            if (response.data.categories.hasOwnProperty(index)) {
                                this.categories_of_product.push(response.data.categories[index]);
                            }
                        }

                        for(let index in response.data.product_images) {
                            if (response.data.product_images.hasOwnProperty(index)) {
                                this.images_of_product.push(response.data.product_images[index]);
                            }
                        }
                        console.log(response.data.categories);
                        this.loading = false;

                    })
                    .catch(error => {
                        console.log(error.statusText);
                        this.loading = false;
                    })
            },

        }
    }
</script>

<style scoped>
    .thumbnail:hover {
        position: relative;
        top: -25px;
        left: -35px;
        width: 60%;
        height: auto;
        display: block;
        z-index: 999;
        background-blend-mode: lighten;

    }
</style>
