<template>
    <div class="container">
        <div v-if="loading == true" class="row justify-content-center" >
            <img src="/images/loading.gif" />
        </div>
        <div v-else>
            <router-link :to="{path: $route.fullPath, query: {pagination: 3}}">Per 3</router-link>
            <table class="table table-responsive table-sm">
                <thead>
                <tr >
                    <th scope="col">Id</th>
                    <th scope="col">Avatar</th>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Operations</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="client in clients">
                    <td width="1%">{{client.id}}</td>
                    <td v-if="client.avatar" width="2%">
                        <img :src="'/storage/'+ client.avatar + '?img=' + Math.random()" v-if="!client.avatar.startsWith('http')" >
                        <img :src="client.avatar + '?img=' + Math.random()" v-else >
                    </td>
                    <td v-else width="2%">
                        <img src="/images/No_Image.png" width="50%">
                    </td>
                    <td width="1%">{{client.first_name}}</td>
                    <td width="1%">{{client.last_name}}</td>
                    <td width="1%">{{client.email}}</td>
                    <td width="1%">{{client.phone}}</td>
                    <td width="2%">
                        <a class="btn btn-light btn-sm" @click="showClient(client.id)" data-toggle="tooltip" title="Show client info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-light btn-sm" @click="editClient(client.id)" data-toggle="tooltip" title="Edit client info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-light btn-sm" @click="deleteClient(client.id, client.email)" data-toggle="tooltip" title="Delete client">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="pagination">
                <button class="page-item btn btn-light btn-sm"
                        v-on:click="fetchPaginateClients(pagination.prev_page_url, items_per_page)"
                        :disabled="!pagination.prev_page_url || loading"
                >
                    Prev
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        page {{pagination.current_page}} of {{pagination.last_page}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="dropdown-item form-group d-flex">
                            <!--<label class="col-form-label">to page</label>-->
                            <input class="form-control" v-model="goToPage" >
                            <router-link class="form-control btn " :to="{path: $route.fullPath, query: {page: goToPage}}">GO</router-link>
                        </div>

                    </div>
                </div>
                <!--<span class="page-item btn btn-outline-secondary btn-sm disabled">-->
                    <!--page {{pagination.current_page}} of {{pagination.last_page}}-->
                <!--</span>-->

                <button class="page-item btn btn-light btn-sm"
                        v-on:click="fetchPaginateClients(pagination.next_page_url, items_per_page)"
                        :disabled="!pagination.next_page_url || loading"
                >
                    Next
                </button>
            </div>
        </div>

        <clientCRUDModal id="modalCreate"
                          v-bind:paramCRUD="paramCRUD"
                          v-bind:client_email="client_email"
                          v-bind:client_id="client_id"
                          v-if="isModalVisible"
                          @close="closeModal"
        />
    </div>
</template>

<script>
    import ClientCRUDModal from './ClientCRUDModal.vue';
    import {bus} from '../app'
    export default {
        name: "ClientsIndexComponent",
        components: {
            "clientCRUDModal": ClientCRUDModal,
        },
        data() {
            return {
                clients: [],
                pagination: [],
                goToPage: '',
                items_per_page: '',
                client_id: '',
                client_email: '',
                isModalVisible: false,
                paramCRUD: '',
                loading: false,
            }
        },
        mounted() {
            this.getClientsWithPagination('/api/users?page=' + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination);
        },
        created() {
            bus.$on('refreshPage', () => {
                //this.getProductsWithPagination();
                this.getClientsWithPagination("/api/users?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                );
                this.isModalVisible = false;
            });
        },
        watch: {
            '$route'(to, from) {
                this.getClientsWithPagination("/api/users?page=" + this.$router.currentRoute.query.page,
                    this.$router.currentRoute.query.pagination,
                );
            }
        },
        methods: {
            getClientsWithPagination(url, items_per_page) {
                this.loading = true;
                window.axios.get(url,{
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')},
                    params: { pagination: items_per_page }
                })
                    .then(response => {
                        this.clients = response.data.data;
                        this.makePagination(response.data);
                        this.items_per_page = items_per_page;
                        this.$router.push({
                            path: this.$router.path,
                            query: {
                                pagination: this.items_per_page,
                                page: this.pagination.current_page
                            }
                        });
                        this.loading = false;
                    })
                    .catch(error => {
                        this.loading = false;
                    });
            },
            makePagination(data) {
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                };
                this.pagination = pagination
            },
            fetchPaginateClients(url, items) {
                this.getClientsWithPagination(url, items);
            },
            showClient(id) {
                this.client_id = id;
                this.paramCRUD = 'showClient';
                this.openModal();
            },
            editClient(id) {
                this.client_id = id;
                this.paramCRUD = 'editClient';
                this.openModal();
            },
            deleteClient(id, email) {
                this.client_id = id;
                this.client_email = email;
                this.paramCRUD = 'deleteClient';
                this.openModal();
            },
            openModal() {
                this.isModalVisible = true;
            },
            closeModal() {
                this.isModalVisible = false;
            },

        }
    }
</script>

<style scoped>

</style>