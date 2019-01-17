<template>
    <div>
        <button class="btn btn-sm btn-outline-info" @click="$router.go(-1)">Back</button>
        <br>
        <div v-if="loading">
            <img src="/images/loading.gif">
        </div>
        <div v-else>
            <table class="table table-bordered table-sm">
                <tbody>
                <tr>
                    <th scope="col">
                        Order
                    </th>
                    <td>
                        #{{order.id}}
                    </td>
                </tr>
                <tr>
                    <th scope="col">
                        Status
                    </th>
                    <td>
                        {{status_list[order.status_id]}}
                        <router-link :to="'/order-payment/' + order_id" v-if="status_list[order.status_id] === 'PENDING_PAYMENT'" class="btn btn-sm btn-warning" >Pay order</router-link>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Recipient:
                    </th>
                    <td>
                        <span>Email: {{recipient.email}}</span><br>
                        <span>Full name: {{recipient.first_name}} {{recipient.last_name}}</span><br>
                        <span>Phone: {{recipient.phone}} {{recipient.last_name}}</span><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Address delivery:
                    </th>
                    <td>
                        <span>Line1: {{address.street_address}}</span><br>
                        <span>Line2: {{address.apartment_address}}</span><br>
                        <span>City: {{address.city}}</span><br>
                        <span>State: {{address.state}}</span><br>
                        <span>Country: {{address.country}}</span><br>
                        <span>Postal code: {{address.postal_code}}</span><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Items:
                    </th>
                    <td>
                        <ol>
                            <li v-for="item in JSON.parse(order.cart).items">
                                {{item.item.name}} {{item.price}}{{item.item.currency}} {{item.qty}}pcs.
                            </li>
                        </ol>

                    </td>
                </tr>
                <tr class="table-success">
                    <th scope="row">
                        Total Price:
                    </th>
                    <td>
                        <strong>{{JSON.parse(order.cart).totalPrice}} usd</strong>
                    </td>
                </tr>
                </tbody>
                <tr v-if="payment">
                    <th scope="row">
                        Payment:
                    </th>
                    <td>
                        <ul>
                            <li>Charge_id: {{payment.charge_id}}</li>
                            <li>Source_id: {{payment.source_id}}</li>
                            <li>Order_id: {{payment.order_id}}</li>
                            <li>Amount: {{payment.amount}}{{payment.currency}}</li>
                            <li>Status: {{payment.status}}</li>
                            <li>Date: {{payment.updated_at}}</li>
                        </ul>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderShowComponent",
        props: ['order_id'],
        data() {
            return {
                order: null,
                recipient: null,
                address: null,
                status_list: {1: 'PENDING_PAYMENT', 2: 'AWAITING_FULFILLMENT', 3: 'AWAITING_SHIPMENT'},
                payment: false,
                loading: true,
            }
        },
        mounted() {
            this.showOrderInfo();
        },
        methods: {
            showOrderInfo() {
                this.loading = true;
                window.axios.get('/api/order/' + this.order_id, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('bigStore.jwt')
                    }
                })
                    .then(response => {
                        console.log(response.data);
                        this.order = response.data;
                        this.recipient = JSON.parse(response.data.recipient);
                        this.address = JSON.parse(response.data.address);
                        this.payment = response.data.payment;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.loading = false;
                    });
            }
        }
    }
</script>

<style scoped>

</style>