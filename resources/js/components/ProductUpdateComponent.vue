<template>
    <div class="container">
        {{checked_categories}}
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <p v-if="message !== ''" class="alert-success">{{message}}</p>
        <form method="POST" action="/products" @submit.prevent="updateProduct(product_id)">
            <label>Name</label><br/>
            <input type="text" name="name" v-model="name"><br/>
            <label>Price</label><br/>
            <input type="text" name="price" v-model="price"><br/>
            <label>Currency</label><br/>
            <select type="text" name="currency" v-model="currency">
                <option disabled value="">Choose currency</option>
                <option v-bind:value="'usd'">USD</option>
                <option v-bind:value="'eur'">EUR</option>
                <option v-bind:value="'uah'">UAH</option>
            </select><br>
            <label v-if="categories_of_product.length >0">Selected categories:</label><br/>
            <ul v-if="categories_of_product.length >0">
                <li v-for="category in categories_of_product">{{category.title}}</li>
            </ul>
            <!--<select multiple="multiple" class="form-control input-sm" name="categories[]" v-model="categories" size="10" id="category" style="width: 250px">-->
            <!--<option v-for="category in list_of_categories"  v-bind:value="category.id"  >{{category.title}}-->
            <!--</option>-->
            <!--</select>-->
            <label>Map categories:</label><br/>
            <ul  v-for="category in list_of_categories" style="list-style-type:none">
                <li class="form-inline">
                    <i v-bind:id="'i'+category.id"
                       class="fa fa-caret-right"
                       aria-hidden="true"
                       @click="showSubCategories(category.id)"
                    ></i>
                    <input type="checkbox"
                           :disabled="category.categories_recursive.length >0 &&
                            (checked_categories === undefined || !checked_categories.includes(category.id))"
                           v-bind:id="category.id" v-bind:value="category.id"
                           v-model="categories"
                           @change="check($event, category.id)"
                    >
                    <label v-bind:for="category.id">
                        {{category.title}}
                    </label>
                    <ul v-bind:id="'sub_'+ category.id"
                        v-if="category.categories_recursive"
                        style="display:none"
                    >
                        <categories-checkbox v-bind:checked_categories="checked_categories"
                            v-bind:list_of_categories="category.categories_recursive"
                        ></categories-checkbox>
                    </ul>
                </li>
            </ul>
            <input id="create_product" type="submit" value="update" class="btn btn-info" v-show="false">
        </form>

    </div>
</template>

<script>
    import {bus} from '../app'
    export default {
        name: "ProductUpdateComponent",
        props: ['product_id'],
        data() {
            return {
                name: '',
                price: '',
                currency: '',
                categories_of_product:[],
                list_of_categories: [],
                categories: [],
                errors: [],
                message: '',
                checked_categories: [],
            }
        },
        mounted() {
            this.getProductForEdit(this.product_id);
            this.getAllCategories();
        },
        created() {
            bus.$on('toggleCategory', (data) => {

                if(this.checked_categories.includes(data))
                {
                    this.checked_categories.splice(this.checked_categories.indexOf(data), 1);
                }else{
                    this.checked_categories.push(data);
                }
                console.log('toggleCategory');
            })
        },
        methods: {
            getAllCategories() {
                window.axios.get('/categories')
                    .then(response => {
                        console.log(response.data);
                        this.list_of_categories = response.data;
                    })
                    .catch(error => {
                        console.log(error.statusText)
                    })
            },
            getProductForEdit(id) {
                window.axios.get('/products/'+id+'/edit')
                    .then(response => {
                        console.log(response.statusText);
                        this.name = response.data.name;
                        this.price = response.data.price;
                        this.currency = response.data.currency;
                        for(let index in response.data.categories) {
                             this.categories.push(response.data.categories[index].id);
                             this.categories_of_product.push(response.data.categories[index]);
                             this.checked_categories.push(response.data.categories[index].id);
                         };
                        console.log(response.data.categories);

                    })
                    .catch(error => {
                        console.log(error.statusText)
                    })
            },
            showSubCategories(target) {
                $("#sub_" + target + "").toggle();
                $("#i" + target + "").toggleClass('fa-caret-right').toggleClass('fa-caret-down');

            },
            updateProduct(id) {
                window.axios.post('/products/'+id, {
                    "_method":"PUT",
                    id: id,
                    name: this.name,
                    price: this.price,
                    currency: this.currency,
                    categories: this.checked_categories,
                })
                .then(response => {
                    this.errors = [];
                    this.message = 'Product was changed.';
                    bus.$emit('refreshPage');
                   // alert(this.message);

                    console.log(response);
                }).catch(error => {
                    this.message = '';
                    this.errors = error.response.data.errors;
                    console.log(error);
                })
            },
            check(e, id) {
                bus.$emit('toggleCategory', id);
                console.log(id)
            },
            // checkSelectedCategories(id, list = this.list_of_categories) {
            //     if(list[id].categories_recursive) {
            //         for(let cat in list[id].categories_recursive){
            //
            //             if(this.checked_categories.includes(cat.id))
            //             {
            //                 return true;
            //             }else {
            //                 return false;
            //             }
            //
            //         }
            //     }
            //     return false;
            // }

        }
    }
</script>

<style scoped>
    i:hover {
        cursor: pointer;
        color: red;
    }
</style>
