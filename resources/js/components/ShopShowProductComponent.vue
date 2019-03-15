<template>
    <div v-if="loading == false">

        <!--Show item-->
        <!--{{product_id}}-->
        <!--{{product}}<br/>-->
        <ul style="list-style-type:none;">
            <li style="display: inline-block;">&nbsp;
                <router-link :to="{path: '/', query: {currency_type: $router.currentRoute.query.currency_type}}">
                    Main
                </router-link>
            </li>
            <li v-if="breadcrumbsCategory.length"
                v-for="breadCrumb in breadcrumbsCategory"
                style="display: inline-block;"
            >
                &raquo;&nbsp;
                <router-link
                    :to="{ path: '/category/'+breadCrumb.id,
                           query: { currency_type: $router.currentRoute.query.currency_type,}
                          }"
                >
                    {{breadCrumb.title}}
                </router-link>&nbsp;
            </li>
        </ul>
        <div id="product_row" class="row" style="border: solid 1px;">
            <div id="product_images" class="col-md-6" style="border-right: 1px solid; max-width: 50%">
                <div class="row" v-if="loading == false">
                    <div class="col-md-4 ">
                        <img v-if="productImages.length" class="main_img"
                             :src="'/storage/'+productImages[0].link_to_file"/>
                        <img v-else class="main_img" src="/images/No_Image.png">
                    </div>
                    <ul class="col-md-2 inline-block scrollable-menu">
                        <li v-for="image in product.product_images" class="row" style="height: 10vh" >
                            <img :src="'/storage/'+image.link_to_thumb" @click="setImage(image.link_to_file)" class="thumb_img"/>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="fold_content" style="margin-left: auto; margin-right: auto; padding: 2%; max-width: 50% ">
                <div class="row" style="width: 50vw"><h4 class="product_title">{{product.name}}</h4></div>
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
                    <a @click="addToCart()" class="btn btn-primary btn-md">Add to cart</a>
                </div>
            </div>
        </div>


        <!--begin comments section-->
        <hr>
        <h3 v-if="product_comments.length >0">Comments:</h3>
        <div v-for="comment in product_comments">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex bd-highlight">
                        <div v-if="comment.user.avatar" class="bd-highlight">
                            <img class="rounded-circle" :src="'/storage/'+comment.user.avatar" v-if="!comment.user.avatar.startsWith('http')">
                            <img class="rounded-circle" :src="comment.user.avatar" v-else>
                        </div>
                        <div v-else class="bd-highlight">
                            <img class="rounded-circle w-25" src="/images/No_Image.png">
                        </div>
                        <div class="ml-2 my-auto bd-highlight">
                            {{comment.user.first_name}} {{comment.user.last_name}}
                        </div>
                        <div class="ml-auto bd-highlight my-auto" v-if="isAdmin">
                            <i class="fa fa-trash btn" aria-hidden="true" data-toggle="tooltip" title="Delete comment with his children" @click="deleteComment(comment.id)"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">{{comment.body}}</p>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" :id="'dropdownMenuButton_'+comment.id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reply
                        </button>
                        <div class="dropdown-menu col-6 p-4" :aria-labelledby="'dropdownMenuButton_'+comment.id">
                            <input type="text" class="form-control" v-model="newCommentForComment" placeholder="Quick reply...">
                            <div class="dropdown-divider"></div>
                            <div class="row justify-content-end pt-2 px-2" >
                                <button class="btn btn-primary" @click="replyComment(comment.id)">Post comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul v-bind:id="'sub_'+ comment.id" v-if="comment.comments_recursive"
                style="list-style: none">
                <comment-component
                        v-bind:parent_id="comment.id"
                        v-bind:comments="comment.comments_recursive"
                ></comment-component>
            </ul>
        </div>
        <!--end comments section-->

        <!--begin create new comment section-->
        <div v-if="this.$store.state.isUserLogin" class="form-group">
            <h3 class="mt-2">New comment</h3>
            <div class="card">
                <div class="card-header form-inline">
                    <div v-if="this.$store.state.user.avatar">
                        <img class="rounded-circle" :src="'/storage/'+this.$store.state.user.avatar" v-if="!this.$store.state.user.avatar.startsWith('http')">
                        <img class="rounded-circle" :src="this.$store.state.user.avatar" v-else>
                    </div>
                    <div v-else>
                        <img class="rounded-circle w-25" src="/images/No_Image.png">
                    </div>
                    <div class="ml-1">
                        {{this.$store.state.user.first_name}} {{this.$store.state.user.last_name }}
                    </div>
                </div>
                <div class="card-body">
                    <textarea type="text" class="form-control input-group" v-model="newCommentForProduct" placeholder="New comment..."></textarea>
                    <button class="btn btn-primary" @click="postCommentProduct()">Post comment</button>
                </div>
            </div>
        </div>
        <!--end create new comment section-->

    </div>
</template>

<script>
    import {bus} from '../app.js';
    export default {
        props: ['product_id'],
        name: "ShopShowProductComponent",
        data() {
            return {
                product: [],
                productImages: [],
                product_comments: [],
                breadcrumbsCategory: [],
                loading: true,
                newCommentForProduct: '',
                newCommentForComment: '',
                isAdmin: this.$store.state.isAdmin || false
            }
        },
        mounted() {
            this.getProductInfo(this.product_id, this.$router.currentRoute.query.currency_type);
        },
        created() {
            bus.$on('refreshPage', () => {
                this.getProductInfo(this.product_id, this.$router.currentRoute.query.currency_type);
            });

        },
        watch: {
            '$route'(to, from) {
                this.getProductInfo(this.product_id, this.$router.currentRoute.query.currency_type);
            },
            '$store.state.isAdmin'() {
                this.isAdmin = this.$store.state.isAdmin;
            }

        },
        methods: {
            deleteComment(comment_id) {
                if(this.$store.state.isAdmin) {
                    window.axios.delete('/api/comment/'+comment_id, {
                        headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+ this.$store.state.token}
                    })
                        .then(response => {
                        console.log(response.data);
                        this.getProductInfo(this.product_id);

                    })
                        .catch(error => {
                            console.log(error.response);
                        })
                }
            },
            replyComment(comment_id) {
                if (this.newCommentForComment.length > 0) {
                    window.axios.post('/api/comment/'+comment_id,
                        {
                            comment_body: this.newCommentForComment
                        },
                        {
                            headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+ this.$store.state.token}
                        }
                    )
                        .then(response => {
                            console.log(response.data);
                            this.getProductInfo(this.product_id);
                            this.newCommentForComment = '';

                        })
                        .catch(error => {
                            console.log(error.response);
                        })
                } else {
                    alert('Comment is empty!');
                }

            },
            postCommentProduct() {
                if (this.newCommentForProduct.length > 0) {
                    window.axios.post('/api/product-comments/'+this.product_id,
                        {
                            comment_body: this.newCommentForProduct
                        },
                        {
                            headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+ this.$store.state.token}
                        }
                    )
                        .then(response => {
                            console.log(response.data);
                            this.getProductInfo(this.product_id);
                            this.newCommentForProduct = '';

                        })
                        .catch(error => {
                            console.log(error.response);
                        })
                } else {
                    alert('Comment is empty!');
                }

            },
            getProductInfo(id, currency) {
                // this.loading = true;
                this.breadcrumbsCategory = [];
                window.axios.get('/products/' + id, {
                    params: {
                        currency_type: currency,
                    }
                }).then(response => {
                    this.product = response.data;
                    this.productImages = response.data.product_images;
                    if (response.data.categories.length > 0) {
                        this.breadcrumbsCategory.push(response.data.categories[0]);
                        this.getBreadcrumbsCategory(this.product.categories[0].parent_id);
                    }
                    this.product_comments = response.data.comments;
                    this.loading = false;
                    console.log(response.data);
                }).catch(error => {
                    console.log(error.response);
                });
            },
            getBreadcrumbsCategory(id) {
                window.axios.get('/categories/' + id)
                    .then(response => {
                        this.breadcrumbsCategory.unshift(response.data);
                        //console.log(response.data);
                        if (response.data.parent_id != null) {
                            this.getBreadcrumbsCategory(response.data.parent_id);
                        }
                        this.loading = false;
                    }).catch(error => {
                   // console.log(error);
                });

            },
            setImage(target) {
                $("img.main_img").attr('src', '/storage/' + target);
            },
            addToCart() {
                window.axios.get('/add-to-cart/' + this.product_id)
                    .then(response => {
                        if(response.status === 200)
                        {
                         alert(response.statusText);
                         console.log(response.data);
                         this.$router.go(-1);
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },
        }
    }
</script>

<style scoped>
    .scrollable-menu {
        height: auto;
        max-height: 50vh;
        overflow-x: hidden;
        margin-left: 50%;
    }

    img.main_img {
        display: block;
        max-width: 50vh;
        height: auto;
        position: relative;
        z-index: 0;

    }

    #product_row {
        height: 51vh;
    }

    .thumb_img {
        position: sticky;
        /*alig: end;*/
        /*margin-left: 270px;*/
        padding-top: 2px;
        width: 5vw;
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
