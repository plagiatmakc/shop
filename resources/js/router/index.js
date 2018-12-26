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
import OrderCheckoutComponent from '../components/OrderCheckoutComponent.vue';
import LoginComponent from '../components/LoginComponent.vue';
import RegisterComponent from '../components/RegisterComponent.vue';
import DashboardComponent from '../components/DashboardComponent.vue';


export default new Router({
    routes: [
        { path: '/admin', component: AdminComponent, name: 'admin',
            meta: {requiresAuth: true},
            beforeEnter: (to, from, next) => {
            console.log(to);
                let user = JSON.parse(localStorage.getItem('bigStore.user'));
                let roles = user.roles;
                let user_type = roles[0].name;
            if(user_type === 'Admin') {
                next();
            }
                // window.axios.get('/is-admin')
                //     .then(response => {
                //         console.log(response.data);
                //         if(response.data === true) {
                //            next();
                //         }else {
                //             from();
                //         }
                //     }).catch(error => {
                //         if(error) return from();
                // });
            }
            ,
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
        },
        {
            path: '/order-checkout',
            component: OrderCheckoutComponent,
            props: true
        },
        {
            path: '/login',
            name: 'login',
            component: LoginComponent,
            props: true
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterComponent,
            props: true
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardComponent,
            props: true
        },


    ],
    // mode: 'history',

})
