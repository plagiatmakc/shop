<template>
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
                            <label v-if="paramCRUD == 'showClient'">
                                Show client
                            </label>
                            <label v-if="paramCRUD == 'editClient'">
                                Edit client
                            </label>
                            <label v-if="paramCRUD == 'deleteClient'">
                                Delete "{{client_email}}"
                            </label>
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
                            <ClientShowComponent
                                    v-if="paramCRUD == 'showClient'"
                                    v-bind:client_id="client_id"
                            ></ClientShowComponent>
                            <ClientUpdateComponent
                                    v-if="paramCRUD == 'editClient'"
                                    v-bind:client_id="client_id"
                            ></ClientUpdateComponent>
                            <div
                                    v-if="paramCRUD == 'deleteClient'"
                            >
                                A you a sure?
                            </div>
                        </slot>
                    </section>
                    <footer class="modal-footer">
                        <slot name="footer">
                            <button @click="clickSubmitButton()"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'editClient'"
                            >
                                Update
                            </button>
                            <button @click="deleteClientClick(client_id)"
                                    type="submit" class="btn-green"
                                    v-if="paramCRUD == 'deleteClient'"
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

    import ClientUpdateComponent from './ClientUpdateComponent.vue'
    import ClientShowComponent from './ClientShowComponent.vue'

    import {bus} from '../app';

    export default {
        props: ['client_id', 'paramCRUD', 'client_email'],
        name: "ClientCRUDModal",
        components: {
            ClientUpdateComponent,
            ClientShowComponent,
        },
        create() {

        },
        methods: {
            close() {
                // this.$emit('close');
                bus.$emit('refreshPage');
            },
            clickSubmitButton() {
                $('#create_client').click();
            },
            deleteClientClick(id) {
                if (confirm("A you sure?")) {
                    window.axios.delete('/api/user/' + id, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + this.$store.state.token
                        }
                    })
                        .then(response => {
                            console.log(response);
                            bus.$emit('refreshPage');
                            this.close();
                            this.loading = false;
                        })
                        .catch(error => {
                            console.log(error.response);
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
        width: auto;
        min-width: 25%;
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
        padding: 5px 5px;
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
