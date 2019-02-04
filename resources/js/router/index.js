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
import OrderPaymentComponent from '../components/OrderPaymentComponent.vue';
import Confirm3DSomponent from '../components/Confirm3DSomponent.vue';
import OrdersIndexComponent from "../components/OrdersIndexComponent.vue";
import OrderShowComponent from '../components/OrderShowComponent.vue';
import ExampleComponent from '../components/ExampleComponent.vue';

export default new Router({
    routes: [
        { path: '/admin', component: AdminComponent, name: 'admin',
            meta: {requiresAuth: true},
            beforeEnter: (to, from, next) => {
            console.log(to);
                let user = JSON.parse(localStorage.getItem('bigStore.user'));
                // if(user.roles){
                //     var user_type = user.roles[0].name;
                // }
            if(user.roles && user.roles[0].name === 'Admin') {
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
                },
                {
                    path: 'orders',
                    component: OrdersIndexComponent,
                },
                {
                    path: 'order/:order_id',
                    component: OrderShowComponent,
                    props: true,
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
            path: '/order-payment/:order_id',
            name: 'payOrder',
            component: OrderPaymentComponent,
            props: true
        },
        {
            path: '/confirm-three-d-secure/:order_id',
            name: 'confirm3DS',
            component: Confirm3DSomponent,
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
            path: '/example',
            name: 'example',
            component: ExampleComponent,
            props: true
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardComponent,
            props: true,
            children: [
                {
                    path: 'orders',
                    name: 'userOrders',
                    component: OrdersIndexComponent,
                },
                {
                    path: 'order/:order_id',
                    component: OrderShowComponent,
                    props: true,
                }
            ],
        },



    ],
     mode: 'history',
     hashbang: false,
     history: true,
     base: '/vue/',
     linkActiveClass: 'active'

})