<template>
    <div>
        <ul style="list-style-type:none">
            <li v-for="category in categories" >
                <div >
                    <i v-bind:id="'i'+category.id"  class="fa fa-plus-circle" ></i>
                    <button class="btn btn-sm" @click="showSubCategories(category.id)" href="#">
                        {{ category.title }}
                    </button>
                    <a @click="getParentId(category.id)" class="btn btn-sm" data-toggle="modal" data-target="#SubCategory">
                        Add subcategory
                    </a>
                </div>
                <ul v-bind:id="'sub_'+ category.id" v-if="category.categories_recursive" style="display:none;">
                    <category-element v-bind:parent_id="category.id" v-bind:categories="category.categories_recursive"></category-element>
                </ul>
            </li>
        </ul>

    </div>
</template>

<script>
    import {bus} from '../app';
    export default {
        props:['categories', 'parent_id'],
        name: "CategoryElementComponent",
        data() {
            return{
                parent_id: '',
            }
        },
        methods: {
            showSubCategories(target){
                $("#sub_"+target+"").toggle();
                $("#i"+target+"").toggleClass('fa-minus-circle');
            },
            getParentId(target) {
                bus.$emit('changeParentId', target)
            }
        }
    }
</script>

<style scoped>

</style>
