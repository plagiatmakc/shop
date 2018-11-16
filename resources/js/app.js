
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/ExampleComponent.vue'));
Vue.component('admin-component', require('./components/AdminComponent.vue'));
Vue.component('categories-index', require('./components/CategoriesIndexComponent.vue'));
Vue.component('category-element', require('./components/CategoryElementComponent.vue'));
Vue.component('category-create', require('./components/CategoryCreateComponent.vue'));
Vue.component('category-update', require('./components/CategoryUpdateComponent.vue'));
Vue.component('modal-category-create', require('./components/ModalCRUDCategory.vue'));
Vue.component('products-index', require('./components/ProductsIndexComponent.vue'));
Vue.component('product-create', require('./components/ProductCreateComponent.vue'));
Vue.component('product-update', require('./components/ProductUpdateComponent.vue'));
Vue.component('product-show', require('./components/ProductShowComponent.vue'));
Vue.component('image-manage', require('./components/ImageManagerComponent.vue'));
Vue.component('dashboard', require('./components/DashboardComponent.vue'));
Vue.component('landing-page',require('./components/LandingPageComponent.vue'));
Vue.component('home-page',require('./components/HomePageComponent.vue'));
Vue.component('shop-show-product', require('./components/ShopShowProductComponent.vue'));

Vue.component('categories-checkbox', require('./components/CategoriesCheckBox.vue'));




export const bus = new Vue();

import router from './router';
const app = new Vue({
    router,
    el: '#app'
});


$(document).ready(function(){
    $(".del").click(function(){
    if(confirm("A you sure?")){
        var token = $(this).data("token");
        $.ajax({
            method: "POST",
            url: $(this).attr("url"),
            data: { "_method": 'DELETE',
                "_token": token,},
            success: function (response) {
                console.log(response);
            },
            error: function (error){
                console.log(error);
            }
        });
        $(this).closest("tr").fadeOut();
    }

    });


    $('.editAttr').click(function () {
        var id = $(this).closest("tr").attr('id');
        $(".modal-content").hide();
        $.ajax({
            type: 'GET',
            url: '/attributes/' + id,
            success: function (response) {
                $("#change_attr").attr("url", "/attributes/"+id);
                $("#id_attr").val(response.id);
                $("#id_sku").val(response.sku);
                $("#id_size").val(response.size);
                $("#id_price").val(response.price);
                $("#id_stock").val(response.stock);
                $(".modal-content").show();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('.changeAttr').click(function () {
        var token = $(this).data("token");
        $.ajax({
            method: "POST",
            url: $(this).attr("url"),
            data: { "_method": 'PUT',
                "_token": token,
                "product_id": $("#id_prod").val(),
                "sku": $("#id_sku").val(),
                "size": $("#id_size").val(),
                "price": $("#id_price").val(),
                "stock": $("#id_stock").val(),
             },
            success: function (response) {
                console.log(response);
                location.reload();

            },
            error: function (error){
                console.log(error);
            }
        });
    });

});
