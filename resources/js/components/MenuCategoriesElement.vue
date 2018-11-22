<template>
    <div>
        <dd class="col-md-2" v-for="category in categories" style="margin-bottom: 1px">
           <router-link :class="category.categories_recursive.length >0 ? 'not-active' : ''"
                        :to="{path: '/category/'+category.id}"
           >{{category.title}}</router-link>

            <div class="row" v-if="category.categories_recursive" >
                    <menu-categories-element
                        v-bind:categories="category.categories_recursive"
                    ></menu-categories-element>

            </div>
        </dd>

    </div>
</template>

<script>
    export default {
        name: "MenuCategoriesElement",
        props:['categories']
    }
</script>

<style scoped>
   .not-active {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        color: black;
    }
</style>
