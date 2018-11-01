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
                           <h4 style="margin-top: 10px; padding-right: 10px; padding-top: 30px">Create subcategory of {{parent_title}}</h4>
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
                            <CreateCategoryComponent v-bind:parent_id="parent_id"></CreateCategoryComponent>
                        </slot>
                    </section>
                    <footer class="modal-footer">
                        <slot name="footer">
                            <button @click="createCategoryClick()" type="submit" class="btn-green">Create</button>
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
    import CreateCategoryComponent from './CreateCategoryComponent.vue'
    export default {
        props:['parent_id', 'parent_title'],
        name: "ModalCreateCategory",
        components: {
            CreateCategoryComponent,
        },
        create() {

        },
        methods: {
            close() {
                this.$emit('close');
            },
            createCategoryClick() {
                $('#create_category').click();
            },
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
        background: #4AAE9B;
        border: 1px solid #4AAE9B;
        border-radius: 2px;
    }
</style>
