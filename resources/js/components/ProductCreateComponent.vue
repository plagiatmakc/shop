<template>
    <div class="container">
        {{checked_categories}}
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <p v-if="message != '' && name ==''" class="alert-success">{{message}}</p>
        <form method="POST" action="/products" @submit.prevent="addProduct()">
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
            <label>Category</label><br/>
            <!--<select multiple="multiple" class="form-control input-sm" name="categories[]" v-model="categories" id="category" style="width: 250px">-->
                <!--<option v-for="category in list_of_categories"  v-bind:value="category.id">{{category.title}}-->
                <!--</option>-->
            <!--</select>-->
            <ul  v-for="category in list_of_categories" style="list-style-type:none">
                <li>
                    <i v-bind:id="'i'+category.id"
                       class="fa fa-caret-right" aria-hidden="true"
                       @click="showSubCategories(category.id)"></i>
                    <input type="checkbox"
                           :disabled="category.categories_recursive.length >0"
                           v-bind:id="category.id" v-bind:value="category.id"
                           v-model="categories"
                           @change="check($event, category.id)"
                    >
                <label v-bind:for="category.id">{{category.title}}</label>
                <ul v-bind:id="'sub_'+ category.id" v-if="category.categories_recursive" style="display:none;">
                    <categories-checkbox v-bind:list_of_categories="category.categories_recursive"></categories-checkbox>
                </ul>
                </li>
            </ul>
            <input type="submit" value="create" class="btn btn-info">
        </form>

    </div>
</template>

<script>
    import {bus} from '../app'
    import CategoriesCheckBox from './CategoriesCheckBox';
    export default {
        name: "ProductCreateComponent",
        components: {
          "categories-checkbox": CategoriesCheckBox,
        },
        data() {
            return {
                name: '',
                price: '',
                currency: '',
                list_of_categories: [],
                categories: [],
                errors: [],
                message: '',
                checked_categories: [],
            }
        },
        mounted() {
          this.getCategories();
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
            addProduct() {
                window.axios.post('/products', {
                    name: this.name,
                    price: this.price,
                    currency: this.currency,
                    categories: this.checked_categories
                })
                .then(response => {
                    console.log(response);
                    this.errors = [];
                    this.message = 'Product was added to database.';
                    //refresh form
                    this.name = '';
                    this.price = '';
                    this.currency = '';

                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
            },
            getCategories() {
                window.axios.get('/categories')
                    .then(response => {
                        console.log(response);
                        this.list_of_categories = response.data;
                    })
                    .catch(error => {
                    console.log(error);
                });
            },
            check(e, id) {
              bus.$emit('toggleCategory', id);
                console.log(id)
            },
            showSubCategories(target) {
                $("#sub_" + target + "").toggle();
                $("#i" + target + "").toggleClass('fa-caret-right').toggleClass('fa-caret-down');

            },
        }
    }
</script>

<style scoped>
   i:hover {
       cursor: pointer;
       color: red;
    }
</style>
