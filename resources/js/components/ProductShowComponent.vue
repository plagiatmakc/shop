<template>
    <div class="container">
        <label>Name: </label>
        <label type="text" >{{name}}</label><br/>
        <label>Price: </label>
        <label type="text" >{{price}}</label>
        <label type="text" >{{currency}}</label><br/>
        <label v-if="categories_of_product.length >0">Selected categories:</label><br/>
            <ul v-if="categories_of_product.length >0">
                <li v-for="category in categories_of_product">{{category.title}}</li>
            </ul>
        <label v-if="images_of_product.length >0">Images:</label><br/>
        <ul v-if="images_of_product.length >0">
            <li v-for="image in images_of_product"><img class="thumbnail" :src="'storage/'+image.link_to_thumb" width="30%"></li>
        </ul>
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

            }
        },
        mounted() {
            this.getProductInfo(this.product_id);
        },
        methods: {
            getProductInfo(id) {
                window.axios.get('/products/'+id)
                    .then(response => {
                        console.log(response.data);
                        this.name = response.data.name;
                        this.price = response.data.price;
                        this.currency = response.data.currency;
                        for(let index in response.data.categories) {
                            this.categories_of_product.push(response.data.categories[index]);
                        };
                        for(let index in response.data.product_images) {
                            this.images_of_product.push(response.data.product_images[index]);
                        };
                        console.log(response.data.categories);

                    })
                    .catch(error => {
                        console.log(error.statusText)
                    })
            },

        }
    }
</script>

<style scoped>
    .thumbnail:hover {
        position:relative;
        top:-25px;
        left:-35px;
        width:100%;
        height:auto;
        display:block;
        z-index:999;
        background-blend-mode: lighten;

    }
</style>
