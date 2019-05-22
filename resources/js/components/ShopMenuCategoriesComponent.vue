<template>
    <div class=" ">

        <div class="show dropdown">
            <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLinkCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Categories
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkCategories" style="width: 200px">
                <div class="dropdown-item form-inline" v-for="category in categories" >
                    <a style="cursor: default;" @mouseenter="showSubcategories(category.id)"  >{{category.title}}</a>
                    <div :id="'sub_'+category.id" style="display: none;"  >
                        <div class="catWrap" @mouseleave="hideSubcategories($event, category.id)" >
                            <dl v-if="category.categories_recursive" style="list-style: none; max-lines: 10" >


                                <dd class="col-md-2" v-for="subcategory in category.categories_recursive"  >
                                    <router-link :class="subcategory.categories_recursive.length >0 ? 'not-active' : ''"
                                                 :to="{path: '/category/'+subcategory.id}"
                                    >{{subcategory.title}}</router-link>
                                    <div class="row">
                                         <menu-categories-element
                                             v-bind:categories="subcategory.categories_recursive"
                                         ></menu-categories-element>
                                    </div>
                                </dd>

                                <!--вынести в отдельный рекурсивный компонент-->

                                <!--<dt class="col-md-2" v-for="subcategory in category.categories_recursive"  >-->
                                    <!--<router-link :class="subcategory.categories_recursive.length >0 ? 'not-active' : ''" :to="{path: '/category/'+subcategory.id}">{{subcategory.title}}</router-link>-->
                                    <!--<dl class="row" v-for="subsubcategory in subcategory.categories_recursive" style="max-height: 200px" >-->
                                        <!--<dt>-->
                                            <!--<router-link :class="subsubcategory.categories_recursive.length >0 ? 'not-active' : ''" :to="{path: '/category/'+subsubcategory.id,-->
                                                        <!--query: { pagination: $router.currentRoute.query.pagination,-->
                                                        <!--type: $router.currentRoute.query.type-->
                                                        <!--}}">{{subsubcategory.title}}</router-link>-->
                                        <!--</dt>-->
                                        <!--<div v-for="subcategory in subsubcategory.categories_recursive" v-if="subsubcategory.categories_recursive.length>0">-->
                                            <!--<dd style="max-width: 100px">-->
                                                <!--<router-link-->
                                                    <!--:to="{path: '/category/'+subcategory.id,-->
                                                        <!--query: { pagination: $router.currentRoute.query.pagination,-->
                                                        <!--type: $router.currentRoute.query.type-->
                                                        <!--}}">{{subcategory.title}}</router-link>-->
                                            <!--</dd>-->
                                        <!--</div>-->
                                    <!--</dl>-->
                                <!--</dt>-->

                                <!---->

                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "ShopMenuCategoriesComponent",
        data() {
          return {
              categories:[],
          }
        },
        mounted() {
            this.getCategories();
        },
        methods: {
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
            // getSubMenu(id, sub_id) {
            //     window.axios.get('/categories/'+id)
            //         .then(response => {
            //             this.subcat[id].push(response.data.categories_recursive );
            //             console.log(response.data);
            //
            //
            //         })
            //         .catch(error => {
            //             console.log(error);
            //         })
            // }


        }
    }
</script>

<style scoped>
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
        padding-left: 10px;
    }
    .not-active {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        color: black;
    }
    @media only screen and (max-width: 450px) {
        .catWrap {
            position: absolute;
            margin-left: 150px;
            margin-top: -50px;
            min-height: auto;
            min-width: 200px;
            max-height: 300px;
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
            max-height: 200px;              /* Limit height to whatever you need */
            overflow-y: scroll;
            max-width: 200px;
            padding-left: 5px;
        }
        .not-active {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: black;
        }
    }
</style>
