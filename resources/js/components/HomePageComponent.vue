<template>

   <div>
       <!--<shop-filters></shop-filters>-->


       <div class=" ">
           <div class="sidebar-header ">
               <h3>Filters</h3>
           </div>

           {{subcat}}

           <div class="show dropdown">
               <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLinkCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fa fa-bars" aria-hidden="true"></i>
                   Categories
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkCategories" style="width: 200px">
                   <div class="dropdown-item form-inline" v-for="category in categories" >
                       <a @mouseenter="showSubcategories(category.id)"  >{{category.title}}</a>
                        <div :id="'sub_'+category.id" style="display: none;"  >
                            <div class="catWrap" @mouseleave="hideSubcategories($event, category.id)" >
                                <dl v-if="category.categories_recursive" style="list-style: none; max-lines: 10" >

                                <!--вынести в отдельный рекурсивный компонент-->

                                    <dt class="col-md-2" v-for="subcategory in category.categories_recursive"  >
                                        <router-link :class="subcategory.categories_recursive.length >0 ? 'not-active' : ''" :to="{path: '/category/'+subcategory.id}">{{subcategory.title}}</router-link>
                                        <dl class="row" v-for="subsubcategory in subcategory.categories_recursive" style="max-height: 200px" >
                                            <dt  >
                                                <router-link :class="subsubcategory.categories_recursive.length >0 ? 'not-active' : ''" :to="{path: '/category/'+subsubcategory.id,
                                                        query: { pagination: $router.currentRoute.query.pagination,
                                                        type: $router.currentRoute.query.type
                                                        }}">{{subsubcategory.title}}</router-link>
                                            </dt>
                                            <div v-for="subcategory in subsubcategory.categories_recursive" v-if="subsubcategory.categories_recursive.length>0">
                                                <dd style="max-width: 100px">
                                                    <router-link
                                                        :to="{path: '/category/'+subcategory.id,
                                                        query: { pagination: $router.currentRoute.query.pagination,
                                                        type: $router.currentRoute.query.type
                                                        }}">{{subcategory.title}}</router-link>
                                                </dd>
                                            </div>
                                        </dl>
                                    </dt>

                                <!---->

                                </dl>
                            </div>
                        </div>
                   </div>
               </div>
           </div>

       </div>


        <div class="form-inline">
            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pagination
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <router-link class="dropdown-item"
                                 :to="{path: $router.path, query:{pagination: 3, type: $router.currentRoute.query.type }}">per 3</router-link>
                    <router-link class="dropdown-item"
                                 :to="{path: $router.path, query:{pagination: 5, type: $router.currentRoute.query.type }}">per 5</router-link>
                    <router-link class="dropdown-item"
                                 :to="{path: $router.path, query:{pagination: 10, type: $router.currentRoute.query.type }}">per 10</router-link>
                </div>
            </div>

            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLinkCurrency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Currency
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkCurrency">

                    <router-link class="dropdown-item" :class="$router.currentRoute.query.type == 'usd' ? 'active' : '' "
                                 :to="{path: $router.path, query:{type: 'usd', pagination: $router.currentRoute.query.pagination}}">USD</router-link>
                    <router-link class="dropdown-item" :class="$router.currentRoute.query.type == 'eur' ? 'active' : '' "
                                 :to="{path: $router.path, query:{type: 'eur', pagination: $router.currentRoute.query.pagination}}">EUR</router-link>
                    <router-link class="dropdown-item" :class="$router.currentRoute.query.type == 'uah' ? 'active' : '' "
                                 :to="{path: $router.path, query:{type: 'uah', pagination: $router.currentRoute.query.pagination }}">UAH</router-link>
                </div>
            </div>
        </div>

        <div class="container">
            <!--Home page-->
            <!--{{last_added_products}}-->

            <div id="last_added" style="border: solid 1px ">
                <h4 style="padding-top: 10px; padding-left: 10px; padding-bottom: 0px; margin-bottom: -15px;">Last added</h4>
                <hr>
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
                categories: [],
                subcat: {},
            }
        },
        mounted() {
            this.getLastAddedProducts("/get-last-products?page=" + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination,
                this.$router.currentRoute.query.type
            );
            this.getCategories();
        },
        watch: {
            '$route'(to, from) {
                this.getLastAddedProducts("/get-last-products?page=" + this.$router.currentRoute.query.page,
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
            getCategories() {
                window.axios.get('/categories')
                    .then(response => {
                        // console.log(response.data);
                        this.categories = response.data;
                        // this.categories.forEach((item) => {
                        //     this.subcat[item.id]=[];
                        //     if(item.categories_recursive.length>0){
                        //         this.getSubMenu(item.id);
                        //     }
                        // });

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            showSubcategories(id) {
                $(".catWrap").hide();
                $("#sub_"+id).show();
                $("#sub_"+id + '>.catWrap').show();

            },
            hideSubcategories(e, id = null) {
                if(id != null) {
                    $("#sub_"+id + '>.catWrap').hide();

                }else {
                    $(".catWrap").hide();
                    $(e.target).hide();
                }

            },
            getSubMenu(id, sub_id) {
                window.axios.get('/categories/'+id)
                    .then(response => {
                        this.subcat[id].push(response.data.categories_recursive );
                        console.log(response.data);


                    })
                    .catch(error => {
                        console.log(error);
                    })
            }

        }
    }
</script>

<style scoped>
.card:hover {
    transform: scale(1.02);
}
    .catWrap {
        position: absolute;
        margin-left: 180px;
        margin-top: -50px;
        min-height: 475px;
        min-width: 700px;
        max-height: 500px;
        padding: 2px;
        border: 1px #00a0ea solid;
        background-color: #fffdf4;
        /*box-shadow: 3px 3px 9px rgba(0, 0, 0, 1);*/
    }
dl {
    display: -ms-flexbox;           /* IE 10 */
    display: -webkit-flex;          /* Safari 6.1+. iOS 7.1+ */
    display: flex;
    -webkit-flex-flow: wrap column; /* Safari 6.1+ */
    flex-flow: wrap column;
    max-height: 500px;              /* Limit height to whatever you need */
    max-width: 500px;
}
.not-active {
    pointer-events: none;
    cursor: default;
    text-decoration: none;
    color: black;
}
</style>
