<template>
    <div>
        <ul style="list-style-type:none">
            <li v-for="category in categories" >
                <div >
                    <i v-bind:id="'i'+category.id"  class="fa fa-plus-circle" ></i>
                    <button class="btn btn-sm" @click="showSubCategories(category.id)" href="#">
                        {{ category.title }}
                    </button>
                    <a @click="getParent(category.id, category.title)" class="btn btn-sm">
                        Add subcategory
                    </a>
                    <a @click="editCategory(category.id)" class="btn btn-sm">
                        Edit
                    </a>
                    <a @click="deleteCategory(category.id, category.title)"
                       class="btn btn-sm"
                    >
                        Delete
                    </a>
                </div>
                <ul
                    v-bind:id="'sub_'+ category.id"
                    v-if="category.categories_recursive"
                    style="display:none;"
                >
                    <category-element
                        v-bind:parent_id="category.id"
                        v-bind:categories="category.categories_recursive"
                    >
                    </category-element>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
    import {bus} from '../app';
    import ModalCreateCategory from './ModalCRUDCategory.vue';
    export default {
        props:['categories', 'parent_id', 'parent_title'],
        name: "CategoryElementComponent",
        components: {
            "modal": ModalCreateCategory,
        },
        methods: {
            showSubCategories(target){
                $("#sub_"+target+"").toggle();
                $("#i"+target+"").toggleClass('fa-minus-circle');
            },
            getParent(id, title) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParentTitle', title);
                bus.$emit('changeParamCRUD', 'addSubCategory')
            },
            editCategory(id) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParamCRUD', 'editCategory');
            },
            deleteCategory(id, title) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParentTitle', title);
                bus.$emit('changeParamCRUD','deleteCategory');
            }
        }
    }
</script>

<style scoped>
    a.btn-sm:hover {
        color: #227dc7;
    }
</style>
