<template>
    <!-- Modal Create SubCategory -->
    <div style="width: 200px">
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal"
                     role="dialog"
                     aria-labelledby="modalTitle"
                     aria-describedby="modalDescription"
                >
                    <header
                        class="modal-header"
                        id="modalTitle"
                    >
                        <slot name="header">
                            <h4 v-if="paramCRUD == 'addSubCategory'">
                                Create subcategory of "{{parent_title}}"
                            </h4>
                            <h4 v-if="paramCRUD == 'editCategory'">
                                Edit category
                            </h4>
                            <h4 v-if="paramCRUD == 'deleteCategory'">
                                Delete "{{parent_title}}"
                            </h4>
                            <button
                                class="btn-close"
                                @click="close"
                                aria-label="Close modal"
                            >
                                x
                            </button>
                        </slot>
                    </header>
                    <section
                        class="modal-body"
                        id="modalDescription"
                    >
                        <slot name="body">
                            <CategoryCreateComponent
                                v-if="paramCRUD == 'addSubCategory'"
                                v-bind:parent_id="parent_id"
                            ></CategoryCreateComponent>
                            <CategoryUpdateComponent
                                v-if="paramCRUD == 'editCategory'"
                                v-bind:category_id="parent_id"
                            ></CategoryUpdateComponent>
                            <div
                                v-if="paramCRUD == 'deleteCategory'"
                                v-bind:category_id="parent_id"
                            >
                                <h4 class="text-danger" style="text-align: center" >Warning!</h4>
                                All subcategories will be destroyed!!!
                            </div>
                        </slot>
                    </section>
                    <footer class="modal-footer">
                        <slot name="footer">
                            <button @click="clickSubmitButton()"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'addSubCategory'"
                            >
                                Create
                            </button>
                            <button @click="clickSubmitButton()"
                                    type="submit" class="btn-green"

                                    v-if="paramCRUD == 'editCategory'"
                            >
                                Update
                            </button>
                            <button @click="deleteCategoryClick(parent_id)"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'deleteCategory'"
                            >
                                Delete
                            </button>
                            <button
                                type="button"
                                class="btn-green"
                                @click="close"
                                aria-label="Close modal"
                            >
                                Close
                            </button>
                        </slot>
                    </footer>
                </div>
            </div>
        </transition>
    </div>

</template>


<script>
    import CategoryCreateComponent from './CategoryCreateComponent.vue'
    import CategoryUpdateComponent from './CategoryUpdateComponent.vue'
    import {bus} from '../app';

    export default {
        props: ['parent_id', 'parent_title', 'paramCRUD'],
        name: "ModalCRUDCategory",
        components: {
            CategoryCreateComponent,
            CategoryUpdateComponent,
        },
        create() {

        },
        methods: {
            close() {
                this.$emit('close');
            },
            clickSubmitButton() {
                $('#create_category').click();
            },
            deleteCategoryClick(id) {
                if (confirm("A you sure?")) {
                    window.axios.post('/categories/' + id, {
                        "_method": 'DELETE',
                    })
                        .then(response => {
                            console.log(response);
                            bus.$emit('refreshPage');
                            this.close();
                            this.loading = false;
                        })
                        .catch(error => {
                            console.log(error.statusText);
                            this.loading = false;
                        })
                }
            }
        },
    }
</script>

<style scoped>
    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        position: fixed;
        z-index: -1;
        top: auto;
        bottom: auto;
        left: auto;
        right: auto;
        width: auto;
        height: auto;
        max-height: 95vh;
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        border-bottom: 1px solid #eeeeee;
        color: #121212;
        justify-content: space-between;
        /*padding-bottom: 0px;*/
        padding: 15px;
    }

    .modal-footer {
        padding: 15px;
        display: flex;
        border-top: 1px solid #eeeeee;
        justify-content: flex-end;
    }

    .modal-body {
        position: relative;
        padding: 20px 10px;
        max-height: 600px;
        overflow-y: auto;
    }

    .btn-close {
        border: none;
        font-size: 20px;
        padding: 0px 0px 0px 15px;
        cursor: pointer;
        font-weight: bold;
        color: #121212;
        background: transparent;
        justify-content: flex-end;
        margin-top: -10px;
    }

    .btn-green {
        color: white;
        cursor: pointer;
        background: #4AAE9B;
        border: 1px solid #4AAE9B;
        border-radius: 2px;
    }

</style>
