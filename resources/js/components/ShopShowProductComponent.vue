<template>
    <div class="container" v-if="loading == false">

        Show item
        {{product_id}}
        {{product}}<br/>
        <ul style="list-style-type:none;">
            <li style="display: inline-block;">&nbsp;
                <router-link :to="{path: '/', query: {type: $router.currentRoute.query.type}}">
                    Main
                </router-link>
            </li>
            <li v-if="breadcrumbsCategory.length"
                v-for="breadCrumb in breadcrumbsCategory"
                style="display: inline-block;"
            >
                &raquo;&nbsp;
                <router-link :to="{path: '/category/'+ breadCrumb.id }">
                    {{breadCrumb.title}}
                </router-link>&nbsp;
            </li>
        </ul>
        <div id="product_row" class="row" style="border: solid 1px;">
            <div id="product_images" class="col-md-6" style="border-right: 1px solid; max-width: 50%">
                <div class="row" v-if="loading == false">
                    <div class="col-md-4 ">
                        <img v-if="productImages.length" class="main_img"
                             :src="'storage/'+productImages[0].link_to_file"/>
                        <img v-else class="main_img" src="/images/No_Image.png">
                    </div>
                    <div class="col-md-2">
                        <div v-for="image in product.product_images" class="row thumb_img">
                            <img :src="'storage/'+image.link_to_thumb" @click="setImage(image.link_to_file)"/>
                        </div>
                    </div>
                </div>
            </div>

            <div id="fold_content" style="margin-left: auto; margin-right: auto; padding: 2%; max-width: 50% ">
                <div class="row" style="width: 500px"><h4 class="product_title">{{product.name}}</h4></div>
                <div class="row form-inline"
                     style="margin-left: auto; margin-right: auto; padding: 2%; max-width: 500px; ">
                    <div class="col-md-4 price" style="max-width: 50%">
                        <h3>{{product.price}}&nbsp;{{product.currency}}</h3>
                    </div>
                    <div class="col-md-2 rating" style="max-width: 50%; margin-left: auto; margin-right: auto;">
                        <div class="row">
                            RATING *****
                        </div>
                    </div>
                </div>
                <div class="row" style="max-width: 50%; margin-left: auto; margin-right: auto;">
                    PRODUCT VERSIONS SECTION
                </div>
                <div class="row">
                    <a href="#" class="btn btn-primary btn-md">Add to cart</a>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['product_id'],
        name: "ShopShowProductComponent",
        data() {
            return {
                product: [],
                productImages: [],
                breadcrumbsCategory: [],
                loading: false,
            }
        },
        mounted() {
            this.getProductInfo(this.product_id, this.$router.currentRoute.query.type);

        },
        watch: {
            '$route'(to, from) {
                this.getProductInfo(this.product_id, this.$router.currentRoute.query.type);
            }
        },
        methods: {
            getProductInfo(id, currency) {
                this.loading = true;
                this.breadcrumbsCategory = [];
                window.axios.get('/products/' + id, {
                    params: {
                        type: currency,
                    }
                }).then(response => {
                    this.product = response.data;
                    this.productImages = response.data.product_images;
                    if (response.data.categories.length > 0) {
                        this.breadcrumbsCategory.push(response.data.categories[0]);
                        this.getBreadcrumbsCategory(this.product.categories[0].parent_id);
                    }
                    this.loading = false;
                    console.log(response.data);
                }).catch(error => {
                    console.log(error.response);
                });
            },
            getBreadcrumbsCategory(id) {
                window.axios.get('categories/' + id)
                    .then(response => {
                        this.breadcrumbsCategory.unshift(response.data);
                        console.log(response.data);
                        if (response.data.parent_id != null) {
                            this.getBreadcrumbsCategory(response.data.parent_id);
                        }
                        this.loading = false;
                    }).catch(error => {
                    console.log(error);
                });

            },
            setImage(target) {
                $("img.main_img").attr('src', 'storage/' + target);
            }
        }
    }
</script>

<style scoped>
    img.main_img {
        display: block;
        max-width: 450px;
        height: auto;
        position: relative;
        z-index: 0;

    }

    #product_row {
        height: 500px;
    }

    .thumb_img {
        position: relative;
        padding-right: 0px;
        margin-left: 270px;
        padding-top: 2px;
        width: 130%;
        z-index: 1;
    }

    .thumb_img:hover {
        cursor: pointer;
        transform: scale(1.1);
    }

    .product_title {
        /*height: 200px;*/
        /*max-width: 30%;*/
        padding-bottom: 2%;
    }
</style>
