
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
// Vue.config.devtools = true; //access devtools default true in dev mode and false in production
import Vuex from 'vuex';
// Vue.use(Vuex);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/ExampleComponent.vue').default);
Vue.component('admin-component', require('./components/AdminComponent.vue').default);
Vue.component('categories-index', require('./components/CategoriesIndexComponent.vue').default);
Vue.component('category-element', require('./components/CategoryElementComponent.vue').default);
Vue.component('category-create', require('./components/CategoryCreateComponent.vue').default);
Vue.component('category-update', require('./components/CategoryUpdateComponent.vue').default);
Vue.component('modal-category-create', require('./components/ModalCRUDCategory.vue').default);
Vue.component('products-index', require('./components/ProductsIndexComponent.vue').default);
Vue.component('product-create', require('./components/ProductCreateComponent.vue').default);
Vue.component('product-update', require('./components/ProductUpdateComponent.vue').default);
Vue.component('product-show', require('./components/ProductShowComponent.vue').default);
Vue.component('image-manage', require('./components/ImageManagerComponent.vue').default);
Vue.component('dashboard', require('./components/DashboardComponent.vue').default);
Vue.component('landing-page',require('./components/LandingPageComponent.vue').default);
Vue.component('home-page',require('./components/HomePageComponent.vue').default);
Vue.component('shop-show-product', require('./components/ShopShowProductComponent.vue').default);
Vue.component('shop-menu-categories', require('./components/ShopMenuCategoriesComponent.vue').default);
Vue.component('menu-categories-element', require('./components/MenuCategoriesElement.vue').default);
Vue.component('category-products', require('./components/CategoryProductsComponent.vue').default);
Vue.component('shop-cart', require('./components/ShopCartComponent.vue').default);
Vue.component('order-checkout', require('./components/OrderCheckoutComponent.vue').default);
Vue.component('order-payment', require('./components/OrderPaymentComponent.vue').default);
Vue.component('confirm-three-d-secure', require('./components/Confirm3DSomponent.vue').default);
Vue.component('navbar-component', require('./components/NavbarComponent.vue').default);
Vue.component('footer-component', require('./components/FooterComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('register-component', require('./components/RegisterComponent.vue').default);
Vue.component('orders-index', require('./components/OrdersIndexComponent.vue').default);
Vue.component('order-show', require('./components/OrderShowComponent.vue').default);
Vue.component('comment-component', require('./components/CommentComponent.vue').default);
Vue.component('categories-checkbox', require('./components/CategoriesCheckBox.vue').default);
Vue.component('card-element', require('./components/CardElement.vue').default);
Vue.component('slider', require('./components/Slider.vue').default);



export const bus = new Vue();
import router from './router';
import store from './store';
store.dispatch('prepareUser');


const app = new Vue({
    router,
    store,
    el: '#app'
});

export const phoneUtil = require('google-libphonenumber').PhoneNumberUtil.getInstance();
export const PNF = require('google-libphonenumber').PhoneNumberFormat;

// $(document).ready(function(){
//     $(".del").click(function(){
//     if(confirm("A you sure?")){
//         var token = $(this).data("token");
//         $.ajax({
//             method: "POST",
//             url: $(this).attr("url"),
//             data: { "_method": 'DELETE',
//                 "_token": token,},
//             success: function (response) {
//                 console.log(response);
//             },
//             error: function (error){
//                 console.log(error);
//             }
//         });
//         $(this).closest("tr").fadeOut();
//     }
//
//     });
//
//
//     $('.editAttr').click(function () {
//         var id = $(this).closest("tr").attr('id');
//         $(".modal-content").hide();
//         $.ajax({
//             type: 'GET',
//             url: '/attributes/' + id,
//             success: function (response) {
//                 $("#change_attr").attr("url", "/attributes/"+id);
//                 $("#id_attr").val(response.id);
//                 $("#id_sku").val(response.sku);
//                 $("#id_size").val(response.size);
//                 $("#id_price").val(response.price);
//                 $("#id_stock").val(response.stock);
//                 $(".modal-content").show();
//             },
//             error: function (error) {
//                 console.log(error);
//             }
//         });
//     });
//
//     $('.changeAttr').click(function () {
//         var token = $(this).data("token");
//         $.ajax({
//             method: "POST",
//             url: $(this).attr("url"),
//             data: { "_method": 'PUT',
//                 "_token": token,
//                 "product_id": $("#id_prod").val(),
//                 "sku": $("#id_sku").val(),
//                 "size": $("#id_size").val(),
//                 "price": $("#id_price").val(),
//                 "stock": $("#id_stock").val(),
//              },
//             success: function (response) {
//                 console.log(response);
//                 location.reload();
//
//             },
//             error: function (error){
//                 console.log(error);
//             }
//         });
//     });
//
// });
