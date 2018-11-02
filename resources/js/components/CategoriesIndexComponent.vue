<template>
    <div class="container">
        <!--<button class="btn btn-outline-secondary"@click="getCategories(3)">per 3</button>-->
        <!--<button class="btn btn-outline-secondary"@click="getCategories(5)">per 5</button>-->
        <!--<button class="btn btn-outline-secondary" @click="getCategories(10)">per 10</button><br><hr>-->
        <!--Loaded-->

        <div>{{parent_id}}</div>
        <div class="panel panel-primary">

            <div class="panel-heading"></div>

            <div class="panel-body">

                <div class="row">

                    <div class="col-md-10">

                        <h3>Category List</h3>

                        <ul id="tree1" style="list-style-type:none">

                            <li v-for="category in categories">

                                <div>
                                    <i v-bind:id="'i'+category.id" class="fa fa-plus-circle"></i>
                                    <button class="btn btn-sm" @click="showSubCategories(category.id)" href="#">
                                        {{category.title }}
                                    </button>
                                    <a @click="getParent(category.id, category.title)"
                                       class="btn btn-sm "
                                    >
                                        Add subcategory
                                    </a>
                                    <a @click="editCategory(category.id)"
                                       class="btn btn-sm"
                                    >
                                        Edit
                                    </a>
                                    <a @click="deleteCategory(category.id, category.title)"
                                       class="btn btn-sm"
                                    >
                                        Delete
                                    </a>
                                </div>
                                <ul v-bind:id="'sub_'+ category.id" v-if="category.categories_recursive"
                                    style="display:none;">
                                    <category-element
                                        v-bind:parent_id="category.id"
                                        v-bind:categories="category.categories_recursive"
                                    ></category-element>
                                </ul>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>
        <!--<modal v-bind:parent_id="parent_id"-->
        <!--v-bind:parent_title="parent_title"-->
        <!--v-show="isModalVisible"-->
        <!--@close="closeModal"-->
        <!--/>-->

        <!--<div class="pagination">-->
        <!--<button class="page-item btn btn-secondary btn-sm"-->
        <!--v-on:click="fetchPaginateCategories(pagination.prev_page_url, items_per_page)"-->
        <!--:disabled="!pagination.prev_page_url"-->
        <!--&gt;-->
        <!--Prev-->
        <!--</button>-->
        <!--<span  class="page-item btn btn-outline-secondary btn-sm disabled">-->
        <!--page {{pagination.current_page}} of {{pagination.last_page}}-->
        <!--</span>-->
        <!--<button class="page-item btn btn-secondary btn-sm"-->
        <!--v-on:click="fetchPaginateCategories(pagination.next_page_url, items_per_page)"-->
        <!--:disabled="!pagination.next_page_url"-->
        <!--&gt;-->
        <!--Next-->
        <!--</button>-->
        <!--</div>-->

        <!-- Modal Create SubCategory -->
        <!--<div id="SubCategory" class="modal fade" role="dialog">-->
        <!--<div class="modal-dialog">-->
        <!--&lt;!&ndash; Modal content&ndash;&gt;-->
        <!--<div class="modal-content">-->
        <!--<div class="modal-header">-->
        <!--<h4 class="modal-title">Create subcategory of {{parent_title}}</h4>-->
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->

        <!--</div>-->
        <!--<div class="modal-body">-->
        <!--<category-create v-bind:parent_id="parent_id"></category-create>-->
        <!--</div>-->
        <!--<div class="modal-footer">-->
        <!--&lt;!&ndash;<button @click="clickSubmitButton()" type="submit" class="btn btn-info">Create</button>&ndash;&gt;-->
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
        <!--</div>-->
        <!--</div>-->

        <!--</div>-->
        <!--</div>-->

        <modalCRUDCategories id="modalCreate"
                             v-bind:paramCRUD="paramCRUD"
                             v-bind:parent_id="parent_id"
                             v-bind:parent_title="parent_title"
                             v-if="isModalVisible"
                             @close="closeModal"
        />


    </div>


</template>

<script>
    import {bus} from '../app';

    import CreateCategoryComponent from './CreateCategoryComponent.vue';
    import ModalCRUDCategory from './ModalCRUDCategory.vue';

    export default {
        // props: ['parent_id','parent_title'],
        name: "CategoriesIndexComponent",
        components: {
            // "category-create": CreateCategoryComponent,
            "modalCRUDCategories": ModalCRUDCategory,
        },
        data() {
            return {
                categories: [],
                pagination: [],
                loading: false,
                url: '/categories',
                items_per_page: '',
                parent_id: '',
                parent_title: '',
                isModalVisible: false,
                paramCRUD: '',
            }
        },
        mounted() {
            this.getCategories();
        },
        created() {
            bus.$on('changeParentId', (data) => {
                this.parent_id = data;
            });
            bus.$on('changeParentTitle', (data) => {
                this.parent_title = data;
                this.isModalVisible = true;
            });
            bus.$on('changeParamCRUD', (data) => {
                this.paramCRUD = data;
                this.isModalVisible = true;
            });
            bus.$on('refreshPage', () => {
                this.getCategories();
                this.isModalVisible = false;
            });
        },
        methods: {
            // getCategories(items = 100){
            //     this.loading = true;
            //
            //     window.axios.get('/categories', {
            //         params: {
            //             pagination: items
            //         }
            //     })
            //         .then(response => {
            //            // console.log();
            //             this.categories = response.data.data;
            //             this.makePagination(response.data);
            //             this.loading = false;
            //             this.items_per_page = items;
            //         })
            //         .catch(error => {
            //            // console.log(error.data);
            //             this.loading = false;
            //         })
            // },
            // getPart(url, items){
            //     this.loading = true;
            //     window.axios.get(url, {
            //         params: {
            //             pagination: items
            //         }
            //     })
            //         .then(response => {
            //             //console.log(response);
            //             this.categories = response.data.data;
            //             this.makePagination(response.data);
            //             this.loading = false;
            //         })
            //         .catch(error => {
            //             console.log(error.data);
            //             this.loading = false;
            //         })
            // },
            // makePagination(data) {
            //     var pagination = {
            //         current_page: data.current_page,
            //         last_page: data.last_page,
            //         next_page_url: data.next_page_url,
            //         prev_page_url: data.prev_page_url,
            //     };
            //     this.pagination = pagination
            // },
            // fetchPaginateCategories(url,items) {
            //     this.getPart(url,items);
            // },
            getCategories() {
                window.axios.get('/categories')
                    .then(response => {
                        console.log(response);
                        this.categories = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error.data);
                        this.loading = false;
                    })

            },
            showSubCategories(target) {
                $("#sub_" + target + "").toggle();
                $("#i" + target + "").toggleClass('fa-minus-circle');

            },
            getParent(id, title) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParentTitle', title);
                bus.$emit('changeParamCRUD', 'addSubCategory')
            },
            clickSubmitButton() {
                $('#create_category').click();
            },
            showModal() {
                // bus.$emit('openModal');
                this.isModalVisible = true;
            },
            closeModal() {
                this.isModalVisible = false;
            },
            editCategory(id) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParamCRUD', 'editCategory');
            },
            deleteCategory(id, title) {
                bus.$emit('changeParentId', id);
                bus.$emit('changeParentTitle', title);
                bus.$emit('changeParamCRUD','deleteCategory');
                // if(confirm("A you sure?")){
                //     window.axios.post('/categories/'+id, {
                //         "_method": 'DELETE',
                //     })
                //         .then(response => {
                //             console.log(response);
                //             bus.$emit('refreshPage');
                //             this.loading = false;
                //         })
                //         .catch(error => {
                //             console.log(error.statusText);
                //             this.loading = false;
                //         })
                // }

            }
        }
    }
</script>

<style scoped>
a.btn-sm:hover {
    color: #227dc7;
}
</style>
