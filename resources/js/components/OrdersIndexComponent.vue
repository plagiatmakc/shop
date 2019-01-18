<template>
    <div>
        "OrdersIndexComponent"
        <!--{{orders}}-->

        <div v-if="loading == true"  >
            <img src="/images/loading.gif" />
        </div>
        <div v-else >
            <table class="table table-responsive table-sm table-hover" >
                <thead>
                <tr >
                    <th scope="col">Id</th>
                    <th scope="col">User</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Status</th>
                    <th scope="col">Last update</th>
                    <th scope="col">Operations</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders">
                    <td width="1%">{{order.id}}</td>

                    <td width="40%">
                        <h5 style="max-width: 250px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;"
                        >{{order.user.email}}</h5>
                    </td>
                    <td width="1%">{{JSON.parse(order.cart).totalPrice}}</td>
                    <td width="1%">USD</td>
                    <td v-if="isAdmin">
                        <select class="form-control"  v-model="order.status_id" @change="onChange(order.id, order.status_id)">
                            <option v-for="status in statuses" :key="status.id" :value="status.id">{{status.value}}</option>
                        </select>
                    </td>
                    <td v-else>
                        {{statuses[order.status_id-1].value}}
                    </td>
                    <td width="12%">{{order.updated_at}}</td>
                    <td >
                        <router-link :to="'order/'+order.id" class="btn btn-light btn-sm" data-toggle="tooltip" title="Show order info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </router-link>
                        <a class="btn btn-light btn-sm" @click="editOrder(order.id)" data-toggle="tooltip" title="Edit order info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-light btn-sm" @click="deleteOrder(order.id)" data-toggle="tooltip" title="Delete order">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="pagination">
                <button class="page-item btn btn-light btn-sm"
                        v-on:click="fetchPaginateOrders(pagination.prev_page_url, items_per_page)"
                        :disabled="!pagination.prev_page_url || loading"
                >
                    Prev
                </button>

                <span class="page-item btn btn-outline-secondary btn-sm disabled">
            page {{pagination.current_page}} of {{pagination.last_page}}
            </span>
                <button class="page-item btn btn-light btn-sm"
                        v-on:click="fetchPaginateOrders(pagination.next_page_url, items_per_page)"
                        :disabled="!pagination.next_page_url || loading"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrdersIndexComponent",
        data() {
            return {
                orders: null,
                isAdmin: JSON.parse(localStorage.getItem('bigStore.user')).roles && JSON.parse(localStorage.getItem('bigStore.user')).roles[0].name ==='Admin',
                pagination: [],
                statuses: [
                    {id: 1, value: 'PENDING_PAYMENT'},
                    {id: 2, value: 'AWAITING_FULFILLMENT'},
                    {id: 3, value: 'AWAITING_SHIPMENT'}],
                items_per_page: '',
                loading: false,
            }
        },
        mounted() {
            this.getOrdersWithPagination('/api/orders?page=' + this.$router.currentRoute.query.page,
                this.$router.currentRoute.query.pagination);
        },
        methods: {
            getOrdersWithPagination(url, items_per_page) {
                this.loading = true;
                window.axios.get(url,{
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')},
                    params: { pagination: items_per_page }
                })
                    .then(response => {
                        console.log(response.data);
                        this.orders = response.data.data;
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
                        console.log(error.response);
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
            fetchPaginateOrders(url, items) {
                this.getOrdersWithPagination(url, items);
            },
            onChange(order_id, status_id) {
                //(''+order_id+ ' -> '+status_id+'');
                if(confirm('Change status?')) {
                    window.axios.post('/api/change-order-status/'+order_id,
                        {status: status_id},
                        { headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}}
                    )
                        .then(response => {
                            console.log(response.data);
                        })
                        .catch(error => {
                           console.log(error.response);
                        });
                }
            },
        }

    }
</script>

<style scoped>

</style>