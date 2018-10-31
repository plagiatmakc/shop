<template>
    <div class="container">
        <form method="POST" action="/categories" @submit.prevent="addCategory()">
            <label>Title</label><br>
            <input type="text" name="title" v-model="title"><br>
            <label>Description</label><br>
            <input type="text" name="description" v-model="description"><br>
            <label>Parent Category</label><br>
            <input  name="parent_id" v-bind:value="parent_id">
            <!--<select name="parent_id"  v-model="parent_id">-->
                <!--<option value="" selected>Hasn't parent category</option>-->
                <!--<option v-for="category in categories" v-bind:value="category.id">{{category.title}}</option>-->
            <!--</select>-->
            <br>
            <button type="submit" class="btn btn-info">Create</button>
        </form>
    </div>

</template>

<script>
    import {bus} from '../app';
    export default {
        name: "CreateCategoryComponent",
        props:['parent_id'],
        data() {
            return {
                title: '',
                description: '',
                categories: [],

            }
        },
        mounted() {
          //this.getAllCategories();
        },
        methods: {
            addCategory() {
                window.axios.post('/categories', {
                    title: this.title,
                    description: this.description,
                    parent_id: this.parent_id
                })
                    .then(function (response) {
                        console.log(response.statusText);
                        bus.$emit('createNewCategory');
                        $(".close").click();
                        $('form :input').val('');
                    })
                    .catch(function(error) {
                        console.log(error.response);
                    });
            },
            // getAllCategories() {
            //     window.axios.get('/all-categories')
            //         .then(response => {
            //             console.log(response.data);
            //             this.categories = response.data;
            //         })
            //         .catch(error => {
            //             console.log(error.statusText)
            //         })
            // }
        }
    }
</script>

<style scoped>

</style>
