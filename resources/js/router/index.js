import Router from 'vue-router';
import Vue from 'vue';
Vue.use(Router);



import AdminComponent from '../components/AdminComponent.vue';
import ProductsIndexComponent from '../components/ProductsIndexComponent.vue';
import ProductCreateComponent from '../components/ProductCreateComponent.vue';
import CategoriesIndexComponent from '../components/CategoriesIndexComponent.vue';
import CategoryCreateComponent from '../components/CategoryCreateComponent.vue';


export default new Router({
    routes: [
        { path: '/admin', component: AdminComponent,
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
        // { path: '', component:  }

    ],


})
