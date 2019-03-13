import Vue from 'vue';
import Router from 'vue-router';
import store from '../store.js'
import {bus} from "../app";
Vue.use(Router);

import AdminComponent from '../components/AdminComponent.vue';
import ClientsIndexComponent from '../components/ClientsIndexComponent.vue';
import ClientCreateComponent from '../components/ClientCreateComponent.vue';
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
        { path: '/admin_section', component: AdminComponent, name: 'admin',
            meta: {requiresAuth: true},
            beforeEnter: (to, from, next) => {
            // console.log(to);
            // console.log(store.getters.getIsAdmin);
                // let user = JSON.parse(localStorage.getItem('bigStore.user'));
                // if(user.roles){
                //     var user_type = user.roles[0].name;
                // }
          if(localStorage.getItem('bigStore.jwt')) {
              window.axios.get('/api/is-admin', {
                  headers: {
                      'Accept': 'application/json',
                      'Authorization': 'Bearer ' + localStorage.getItem('bigStore.jwt')
                  }
              })
                  .then(response => {
                      if(response.data === true) {
                          window.axios.get('/is-admin', {
                              headers: {
                                  'Accept': 'application/json',
                                  'Authorization': 'Bearer ' + localStorage.getItem('bigStore.jwt')
                              }
                          })
                              .then(response => {
                                  store.commit('setIsAdmin', response.data);
                                  bus.$emit('isLoggedIn');
                                  if(response.data === true) {
                                      next();
                                  }else {
                                      window.location.replace('/admin/login');
                                  }
                              })
                              .catch(error => {
                                  window.location.replace('/admin/login');
                              });
                      }
                  });

                }
            }
            ,
            children: [
                {
                    path: 'clients',
                    component: ClientsIndexComponent,
                    props: true
                },
                {
                    path: 'create_client',
                    component: ClientCreateComponent
                },
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
            name: 'shopCart',
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
            path: '/support/:room_id',
            name: 'support',
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
     base: '/',
     linkActiveClass: 'active'

})