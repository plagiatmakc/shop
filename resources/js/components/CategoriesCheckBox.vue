<template>
    <div>
        <ul v-for="category in list_of_categories" style="list-style-type:none">
            <li class="form-inline">
                <i v-bind:id="'i'+category.id"
                   class="fa fa-caret-right"
                   aria-hidden="true"
                   @click="showSubCategories(category.id)"
                >
                </i>
                <input type="checkbox"
                       :disabled="category.categories_recursive.length >0 && (checked_categories === undefined || !checked_categories.includes(category.id))"
                       v-bind:id="category.id" v-bind:value="category.id"
                       v-model="categories"
                       @change="check($event, category.id)"
                >
                <label v-bind:for="category.id">{{category.title}}</label>
                <ul v-bind:id="'sub_'+ category.id"
                    v-if="category.categories_recursive"
                    style="display:none"
                >
                    <categories-check-box v-bind:checked_categories="categories"
                        v-bind:list_of_categories="category.categories_recursive"
                    ></categories-check-box>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
    import {bus} from '../app'

    export default {
        props: ['list_of_categories', 'checked_categories'],
        name: "CategoriesCheckBox",
        data() {
            return {
                categories: this.checked_categories || [],
            }
        },
        methods: {
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
