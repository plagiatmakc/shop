<template>
    <div>
        <div class="sidebar">

            <a href="#" @click="component= 'dashboard'"><i class="fa fa-fw fa-home"></i> Dashboard</a>
            <a href="#"><i class="fa fa-fw fa-vcard"></i> Clients</a>
            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button" id="dropdownProductsMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-database"></i>
                    Products
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownProductsMenuLink">
                    <a class="dropdown-item" @click="component= 'products-index'">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        Index
                    </a>
                    <a class="dropdown-item"  @click="component= 'product-create'">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Create
                    </a>
                </div>
            </div>
            <div class="dropdown show">
                <a href="#" class="btn dropdown-toggle" role="button" id="dropdownCategoriesMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-database"></i>
                    Categories
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownCategoriesMenuLink">
                    <a class="dropdown-item" @click="component= 'categories-index'">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        Index
                    </a>
                    <a class="dropdown-item"  @click="component= 'category-create'">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Create
                    </a>
                </div>
            </div>
            <a href="#"><i class="fa fa-fw fa-envelope"></i> Orders</a>
        </div>
        <keep-alive>
            <component
                v-bind:is="component"
            ></component>
        </keep-alive>
    </div>
</template>

<script>

import CategoriesIndexComponent from './CategoriesIndexComponent.vue';
import CategoryCreateComponent from './CategoryCreateComponent.vue';
import ProductsIndexComponent from './ProductsIndexComponent.vue';
import ProductCreateComponent from './ProductCreateComponent.vue';

import DashboardComponent from './DashboardComponent.vue';


    export default {
        name: "AdminComponent",
        components: {
            "dashboard": DashboardComponent,
            "categories-index": CategoriesIndexComponent,
            "category-create": CategoryCreateComponent,
            "products-index": ProductsIndexComponent,
            "product-create": ProductCreateComponent,
        },
        data() {
            return {
                component: 'dashboard',
            }
        },
        methods: {

        }
    }
</script>

<style scoped>
    /* Style the sidebar - fixed full height */
    .sidebar {
        height: 100%;
        width: 200px;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 16px;
    }

    /* Style sidebar links */
    .sidebar a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 20px;
        color: #818181;
        display: block;
    }

    /* Style links on mouse-over */
    .sidebar a:hover {
        color: #f1f1f1;
    }

    /* Style the main content */
    .main {
        margin-left: 160px; /* Same as the width of the sidenav */
        padding: 0px 10px;
    }

    /* Add media queries for small screens (when the height of the screen is less than 450px, add a smaller padding and font-size) */
    @media screen and (max-height: 450px) {
        .sidebar {padding-top: 15px;}
        .sidebar a {font-size: 18px;}
    }
</style>
