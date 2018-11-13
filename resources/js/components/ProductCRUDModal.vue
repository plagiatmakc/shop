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
                        class="modal-header d-inline-flex"
                        id="modalTitle"
                    >
                        <slot name="header">
                            <h4 style="margin-top: 10px; padding-right: 10px; padding-top: 30px"
                                v-if="paramCRUD == 'addAttributes'"
                            >
                                Create attributes of {{parent_title}}
                            </h4>

                            <h4 style="margin-top: 10px; padding-right: 10px; padding-top: 30px"
                                v-if="paramCRUD == 'showProduct'"
                                >
                                Show Product
                            </h4>
                            <h4 style="margin-top: 10px; padding-right: 10px; padding-top: 30px"
                                v-if="paramCRUD == 'editProduct'"
                            >
                                Edit product
                            </h4>
                            <h4 style="margin-top: 10px; padding-top: 20px; "
                                v-if="paramCRUD == 'imageManage'"
                            >
                                 {{product_name}}
                            </h4>
                            <h4 style="margin-top: 10px; padding-top: 20px; "
                                v-if="paramCRUD == 'deleteProduct'"
                            >
                                Delete {{product_name}}
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
                            <!--<ProductAttributes-->
                                <!--v-if="paramCRUD == 'addAttributes'"-->
                                <!--v-bind:parent_id="parent_id"-->
                            <!--&gt;</ProductAttributes>-->
                            <ProductShowComponent
                                v-if="paramCRUD == 'showProduct'"
                                v-bind:product_id="product_id"
                            ></ProductShowComponent>
                            <ProductUpdateComponent
                                v-if="paramCRUD == 'editProduct'"
                                v-bind:product_id="product_id"
                            ></ProductUpdateComponent>
                            <ImageManagerComponent
                                v-if="paramCRUD == 'imageManage'"
                                v-bind:product_id="product_id"
                            ></ImageManagerComponent>

                            <div
                                v-if="paramCRUD == 'deleteProduct'"

                            >
                                A you a sure?
                            </div>
                        </slot>
                    </section>
                    <footer class="modal-footer">
                        <slot name="footer">
                            <button @click="clickSubmitButton()"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'addAttributes'"
                            >
                                Create
                            </button>
                            <button @click="clickSubmitButton()"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'editProduct'"
                            >
                                Update
                            </button>
                            <button @click="deleteProductClick(product_id)"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'deleteProduct'"
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

    import ProductUpdateComponent from './ProductUpdateComponent.vue'
    import ProductShowComponent from './ProductShowComponent.vue'
    import ImageManagerComponent from './ImageManagerComponent.vue'
    import {bus} from '../app';

    export default {
        props: ['product_id', 'paramCRUD', 'product_name'],
        name: "ProductCRUDModal",
        components: {
            ProductUpdateComponent,
            ProductShowComponent,
            ImageManagerComponent,
        },
        create() {

        },
        methods: {
            close() {
                this.$emit('close');
            },
            clickSubmitButton() {
                $('#create_product').click();
            },
            deleteProductClick(id) {
                if (confirm("A you sure?")) {
                    window.axios.post('/products/' + id, {
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
        padding-bottom: 0px;
        padding-top: 5px;
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
        padding: 0px;
        cursor: pointer;
        font-weight: bold;
        color: #121212;
        background: transparent;
    }

    .btn-green {
        color: white;
        cursor: pointer;
        background: #4AAE9B;
        border: 1px solid #4AAE9B;
        border-radius: 2px;
    }
</style>
