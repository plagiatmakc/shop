<template>
    <div class="container">
        {{checked_categories}}
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <p v-if="message != '' && name ==''" class="alert-success">{{message}}</p>
        <form method="POST" action="/products" enctype="multipart/form-data" @submit.prevent="addProduct()">
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
            <label>Product photos (can attach more than one):</label><br />
            <input type="file" name="images[]" id="images" ref="images" style="display: none;" multiple @change="onExistsFileChanged($event)" />
            <input type="button" class="btn btn-sm btn-light" value="Browse..." onclick="document.getElementById('images').click();" />
            <div class="col-md-12">
                <div class="attachment-holder animated fadeIn" v-cloak v-for="(image, index) in images">
                    <span class="label label-primary">{{ image.name + ' (' + Number((image.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span>
                    <span class="" style="background: red; cursor: pointer;" @click="removeImage(image)"><a class="btn btn-sm btn-light">Remove</a></span>
                </div>
            </div>
            <br /><br />
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
                images: [],
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
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('price', this.price);
                formData.append('currency', this.currency);
                // formData.append('categories', this.checked_categories);
                for (var i =0 ; i < this.checked_categories.length; i++)
                {     let category = this.checked_categories[i];
                    formData.append('categories['+i+']', category);
                }
                for (var i =0 ; i < this.images.length; i++)
                {     let image = this.images[i];
                    formData.append('images['+i+']', image);
                }

                // for (var pair of formData.entries()) {
                //     console.log(pair[0]+ ', ' + pair[1]);
                // }
                // return;
                window.axios.post('/products', formData, {headers: {'Content-Type': 'multipart/form-data'}}
                    // {
                    // name: this.name,
                    // price: this.price,
                    // currency: this.currency,
                    // categories: this.checked_categories,
                    // images: this.images
                // }
                )
                .then(response => {
                    console.log(response);
                    this.errors = [];
                    this.message = 'Product was added to database.';
                    //refresh form
                    this.name = '';
                    this.price = '';
                    this.currency = '';
                    this.images = [];

                })
                .catch(error => {
                    console.log(error);
                    this.message = '';
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
            onExistsFileChanged(e) {
                this.images = [];
                var files = e.target.files || e.dataTransfer.files;

                console.log(files);
                if (!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--) {
                    this.images.push(files[i]);
                }
                console.log(this.images);

            },
            removeImage(image) {
                this.images.splice(this.images.indexOf(image),1);
                this.getAttachmentSize();
            },
            getAttachmentSize() {

                this.upload_size = 0; // Reset to beginningƒ
                this.images.map((item) => { this.upload_size += parseInt(item.size); });
                this.upload_size = Number((this.upload_size).toFixed(1));
                this.$forceUpdate();
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
