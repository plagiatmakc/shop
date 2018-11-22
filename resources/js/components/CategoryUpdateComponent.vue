<template>
    <div class="container">
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <p v-if="message != '' && title ==''" class="alert-success">{{message}}</p>
        <form method="POST" action="/categories" @submit.prevent="updateCategory(category_id)">
            <label>Title</label><br>
            <input id="category_title_id" type="text" name="title" v-model="title"><br>
            <label>Description</label><br>
            <input id="category_description_id" type="text" name="description" v-model="description"><br>
            <label >Parent category:</label><br/>
            <select name="parent_id"  v-model="parent_id" style="width: 210px">
                <option :value="null" >Hasn't parent category</option>
                <option v-for="category in categories"  v-bind:value="category.id">{{category.title}}</option>
            </select>
            <p></p>
            <input id="create_category" type="submit" value="update" class="btn btn-info"
                   v-show="false">
        </form>
    </div>
</template>

<script>

    import {bus} from '../app';

    export default {
        name: "UpdateCategoryComponent",
        props: ['category_id'],
        data() {
            return {
                title: '',
                description: '',
                categories: [],
                parent_id: '',
                errors: [],
                message: '',
            }
        },
        mounted() {
            this.getCategoryForEdit(this.category_id);
            this.getAllCategories();
        },
        methods: {
            getCategoryForEdit(category_id) {
              window.axios.get('/categories/'+category_id+'/edit')
                  .then(response => {
                      console.log(response.statusText);
                      this.title = response.data.title;
                      this.description = response.data.description;
                      this.parent_id = response.data.parent_id;
                  })
                  .catch(error => {
                      console.log(error.statusText)
                  })
            },
            updateCategory(id) {
                window.axios.post('/categories/'+id, {
                    "_method": 'PUT',
                    id: id,
                    title: this.title,
                    description: this.description,
                    parent_id: this.parent_id
                })
                    .then(response => {
                        console.log(response.statusText);
                        bus.$emit('refreshPage');
                        $(".close").click();
                        this.errors = [];
                        this.message = 'Category was added to database.';
                        // $('form :input').val('');
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        console.log(error.response);
                    });
            },
            getAllCategories() {
                window.axios.get('/all-categories')
                    .then(response => {
                        console.log(response.data);
                        this.categories = response.data;
                    })
                    .catch(error => {
                        console.log(error.statusText)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
