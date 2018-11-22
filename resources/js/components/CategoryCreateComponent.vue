<template>
    <div class="container">
        <ul v-if="errors != null">
            <li class="alert-danger"
                v-for="error in errors"
            >{{error.toString()}}</li>
        </ul>
        <p class="alert-success"
           v-if="message != '' && title ==''"
        >{{message}}</p>
        <form method="POST" action="/categories" @submit.prevent="addCategory()">
            <label>Title</label><br>
            <input id="category_title_id" type="text" name="title" v-model="title"><br>
            <label>Description</label><br>
            <input id="category_description_id" type="text" name="description" v-model="description"><br>
            <label v-if="parent_id != undefined">Parent category id:</label>
            <input type="text" disabled name="parent_id" style="width: 50px;"
                   v-bind:value="parent_id"
                   v-if="parent_id != undefined"
            >
            <p></p>
            <input id="create_category" type="submit" value="Create" class="btn btn-light"
                   v-show="parent_id == undefined">
        </form>
    </div>

</template>

<script>
    import {bus} from '../app';

    export default {
        name: "CreateCategoryComponent",
        props: ['parent_id'],
        data() {
            return {
                title: '',
                description: '',
                categories: [],
                errors: [],
                message: '',
            }
        },
        methods: {
            addCategory() {
                window.axios.post('/categories', {
                    title: this.title,
                    description: this.description,
                    parent_id: this.parent_id
                })
                    .then(response => {
                        console.log(response.statusText);
                        bus.$emit('refreshPage');
                        $(".close").click();
                        this.title ='';
                        this.description = '';
                        this.errors = [];
                        this.message = 'Category was added to database.';
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        console.log(error.response);
                    });
            },
        }
    }
</script>

<style scoped>

</style>
