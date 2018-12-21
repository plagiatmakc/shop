import Router from 'vue-router';
import Vue from 'vue';
Vue.use(Router);



import AdminComponent from '../components/AdminComponent.vue';
import ProductsIndexComponent from '../components/ProductsIndexComponent.vue';
import ProductCreateComponent from '../components/ProductCreateComponent.vue';
import CategoriesIndexComponent from '../components/CategoriesIndexComponent.vue';
import CategoryCreateComponent from '../components/CategoryCreateComponent.vue';
import HomePageComponent from '../components/HomePageComponent.vue';
import ShopShowProductComponent from '../components/ShopShowProductComponent.vue';
import CategoryProductsComponent from '../components/CategoryProductsComponent.vue';
import ShopCartComponent from '../components/ShopCartComponent.vue';


export default new Router({
    routes: [
        { path: '/admin', component: AdminComponent,
            meta: {requiresAuth: true},
            beforeEnter: (to, from, next) => {
            console.log(to);
                window.axios.get('/is-admin')
                    .then(response => {
                        console.log(response.data);
                        if(response.data === true) {
                           next();
                        }else {
                            from();
                        }
                    }).catch(error => {
                        if(error) return from();
                });
            },
            children: [
                {
                    path: 'products',
                    component: ProductsIndexComponent,
                    props: true
                },
                {
                    path: 'create_product',
                    component: ProductCreateComponent
                },
                {
                    path: 'categories',
                    component: CategoriesIndexComponent
                },
                {
                    path: 'create_category',
                    component: CategoryCreateComponent
                }
            ]
        },
        { path: '/', component:  HomePageComponent,},

        {
            path: '/product/:product_id',
            component: ShopShowProductComponent,
            props: true
        },
        {
            path: '/category/:category_id',
            component: CategoryProductsComponent,
            props: true
        },
        {
            path: '/shop-cart',
            component: ShopCartComponent,
            props: true
        }



    ],
    // mode: 'history',

})
