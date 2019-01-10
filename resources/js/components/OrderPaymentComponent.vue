<template>
    <div>
        {{name}}- Loaded <br>
        {{order_id}} - Order ID <br>
        {{order}} <br><hr>
        {{order_cart}} <br><hr>
        {{order_recipient}} <br><hr>
        {{order_address}} <br><hr>
        <!--<div id="paypal-button"></div>-->

        <div class="row">
            <div class="col-md-4 offset-md-4 col-10 offset-1 pl-0 pr-0">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5 col-12 pt-2">
                                <h6 class="m-0"><strong>Payment Details</strong></h6>
                            </div>
                            <div class="col-md-7 col-12 icons">
                                <i class="fa fa-cc-visa fa-2x" aria-hidden="true"></i>
                                <i class="fa fa-cc-mastercard fa-2x" aria-hidden="true"></i>
                                <i class="fa fa-cc-discover fa-2x" aria-hidden="true"></i>
                                <i class="fa fa-cc-amex fa-2x" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form v-on:submit.prevent="createStripeSource">
                            <div class="form-group">
                                <label for="Amount"><strong>Amount (USD)</strong></label>
                                <div class="col-md-12">
                                    <span id="amount" class="form-control" v-model="amount" required>{{amount}}</span>
                                </div>
                            </div><div class="form-group">
                                <label for="email"><strong>Email</strong></label>
                                <div class="col-md-12">
                                    <input id="email" type="text" class="form-control" v-model="email" required>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="description" ><strong>Pay description</strong></label>
                                <div class="col-md-12">
                                    <input id="description" type="text" class="form-control" v-model="description" required>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="card" ><strong>Card</strong></label>
                                <div class="col-md-12">
                                    <div id="card" ref="card" class="form-control"></div>
                                </div>
                            </div>


                            <button class="btn btn-info w-100 pb-2 pt-2">Pay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!--<card-element></card-element>-->

    </div>
</template>

<script>
    let stripe = Stripe('pk_test_wEFBkJ4pzBO2zPiP0mSL9E1l'),
        elements = stripe.elements(),
        card = undefined;

    export default {
        name: "OrderPaymentComponent",
        props: ['order_id'],
        data() {
            return {
                name: "OrderPaymentComponent",
                order: '',
                order_cart: '',
                order_recipient: '',
                order_address: '',
                items: [],
                card: {
                    card_number: null,
                    expiry_month: null,
                    expiry_year: null,
                    cvv: null,
                },
                amount: 0,
                email: null,
                description: null,
                card_token: null,
                source: null,
            }
        },
        mounted() {
            this.getOrder();
            this.drawStripeCardFields();
            // this.paypalButtonRender();
        },
        methods: {
            getOrder() {
                window.axios.get('/api/order/'+this.order_id, {
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}
                })
                    .then(response => {
                        console.log(response.data);
                        this.order = response.data;
                        this.order_cart = JSON.parse(response.data.cart);
                        this.order_recipient = JSON.parse(response.data.recipient);
                        this.order_address = JSON.parse(response.data.address);

                        this.amount = this.order_cart.totalPrice;
                        this.email = this.order_recipient.email;
                        this.description = "Pay for order "+this.order_id+" by "+this.email;

                        // for (let key in  this.order_cart.items)
                        // {
                        //     this.items.push({
                        //         name: this.order_cart.items[key].item.name,
                        //         quantity: this.order_cart.items[key].qty,
                        //         price: this.order_cart.items[key].price,
                        //         sku: this.order_cart.items[key].item.id,
                        //         currency: this.order_cart.items[key].item.currency.toUpperCase(),
                        //     });
                        // }
                        //  console.log(this.items);
                    })
                    .catch(error => {
                        console.log(error.response.statusText);
                    });



            },
            drawStripeCardFields() {
                card = elements.create('card',{
                    hidePostalCode: true,
                    style: {
                        base: {
                            color: '#303238',
                            fontSize: '15px',
                            color: "#32325d",
                            fontSmoothing: 'antialiased',
                            '::placeholder': {
                                color: '#ccc',
                            },
                        },
                        invalid: {
                            color: '#e5424d',
                            ':focus': {
                                color: '#303238',
                            },
                        },
                    }
                });
                card.mount(this.$refs.card);
            },
            createStripeSource() {
                let vm = this;
                stripe.createSource(card).then(function(result) {
                    if (result.error) {
                        self.hasCardErrors = true;
                        self.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
                        return;
                    }
                    if (result.source.card.three_d_secure === 'required') {
                        console.log(result.source);
                        vm.stripe3DSecureHandler(result.source);
                    }else {
                        console.log(result.source);
                        vm.createCharge(result.source.id);
                    }



                });
            },
            stripe3DSecureHandler(source) {
                let vm = this;
                    stripe.createSource({
                        type: "three_d_secure",
                        amount: this.amount,
                        currency: "usd",
                        three_d_secure: {
                            card: source.id
                        },
                        redirect: {
                            return_url: window.location.href
                        }
                    }).then(function(result) {
                        if (result.error){
                            return;
                        }
                        //redirect to verification 3DS page
                         window.location = result.source.redirect.url;

                                        if (result.source.status === 'chargeable') {
                                            alert('OK');
                                            vm.createCharge(result.source.id);
                                            console.log(result.source);
                                        }

                    });
            },
            createCharge(card_token) {
                window.axios.post('/api/charge',
                    {card_token: card_token, email: this.email, amount: this.amount, order_id: this.order_id, description: this.description},
                    {headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}}
                    )
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },
            // paypalButtonRender() {
            //     var vm = this;
            //     paypal.Button.render({
            //         // Configure environment
            //         env: 'sandbox',
            //         client: {
            //             sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
            //             production: 'demo_production_client_id'
            //         },
            //         // Customize button (optional)
            //         locale: 'en_US',
            //         style: {
            //             size: 'small',
            //             color: 'gold',
            //             shape: 'pill',
            //         },
            //
            //         // Enable Pay Now checkout flow (optional)
            //         commit: true,
            //
            //         // Set up a payment
            //         payment: function(data, actions) {
            //             return actions.payment.create({
            //                 transactions: [{
            //                     amount: {
            //                         total: vm.order_cart.totalPrice,
            //                         currency: 'USD',
            //                         // details: {
            //                         //     subtotal: '499.00',
            //                         //     tax: '0.07',
            //                         //     shipping: '0.03',
            //                         //     handling_fee: '1.00',
            //                         //     shipping_discount: '-1.00',
            //                         //     insurance: '0.01'
            //                         // }
            //                     },
            //                     description: 'The payment transaction description.',
            //                     custom: '90048630024435',
            //                     //invoice_number: '12345', Insert a unique invoice number
            //                     payment_options: {
            //                         allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
            //                     },
            //                     soft_descriptor: 'ECHI5786786',
            //                     item_list: {
            //                         items: vm.items,
            //                         shipping_address: {
            //                             recipient_name: vm.order_recipient.first_name+' '+vm.order_recipient.last_name,
            //                             line1: vm.order_address.street_address,
            //                             line2: vm.order_address.apartment_address,
            //                             city: vm.order_address.city,
            //                             country_code: vm.order_address.country_code,
            //                             postal_code: vm.order_address.postal_code,
            //                             phone: vm.order_recipient.phone,
            //                             state: vm.order_address.state,
            //                         }
            //                     }
            //                 }],
            //                 note_to_payer: 'Contact us for any questions on your order.'
            //             });
            //         },
            //         // Execute the payment
            //         onAuthorize: function(data, actions) {
            //             return actions.payment.execute().then(function() {
            //                 // Show a confirmation message to the buyer
            //                 window.alert('Thank you for your purchase!');
            //             });
            //         }
            //     }, '#paypal-button');
            // }
        }
    }
</script>

<style scoped>
    .card-header .icons .fa-cc-visa{
        color: #FFB85F;
    }
    .card-header .icons .fa-cc-discover{
        color: #027878;
    }
    .card-header .icons .fa-cc-amex{
        color: #EB4960;
    }
    .card-body label{
        font-size: 14px;
    }
</style>