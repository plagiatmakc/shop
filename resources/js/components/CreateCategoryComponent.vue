<template>
    <div class="container">
        <form method="POST" action="/categories" @submit.prevent="addCategory()">
            <label>Title</label><br>
            <input id="category_title_id" type="text" name="title" v-model="title"><br>
            <label>Description</label><br>
            <input id="category_description_id" type="text" name="description" v-model="description"><br>
            <label v-if="parent_id != undefined">Parent category id:</label>
            <input type="text" disabled name="parent_id" v-bind:value="parent_id" v-if="parent_id != undefined"
                   style="width: 50px;">
            <!--<select name="parent_id"  v-model="parent_id">-->
            <!--<option value="" selected>Hasn't parent category</option>-->
            <!--<option v-for="category in categories" v-bind:value="category.id">{{category.title}}</option>-->
            <!--</select>-->
            <p></p>
            <input id="create_category" type="submit" value="create" class="btn btn-info"
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

            }
        },
        mounted() {
            //this.getAllCategories();
        },
        created() {
            // bus.$on('openModal', () => {
            //     this.title = '';
            //     this.description = '';
            // });
        },
        // watch: {
        //   parent_id: function() {
        //       this.title = '';
        //       this.description = '';
        //   }
        // },
        methods: {
            addCategory() {
                window.axios.post('/categories', {
                    title: this.title,
                    description: this.description,
                    parent_id: this.parent_id
                })
                    .then(function (response) {
                        console.log(response.statusText);
                        bus.$emit('refreshPage');
                        $(".close").click();
                        // $('form :input').val('');
                    })
                    .catch(function (error) {
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
