<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div>
                    <div v-if="!isLoggedIn">
                        <h2>You need to login to continue</h2>
                        <router-link :to="{ name: 'login' }" class="col-md-4 btn btn-primary float-left">Login</router-link>
                        <router-link :to="{ name: 'register' }" class="col-md-4 btn btn-danger float-right">Register</router-link>
                    </div>
                    <div v-if="isLoggedIn">
                        {{cart}}
                        <form @submit.prevent="placeOrder">
                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">First name</label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" v-model="first_name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last name</label>
                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" v-model="last_name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="country" class="col-sm-4 col-form-label text-md-right">Country</label>
                                <div class="col-md-6">
                                    <select id="country" type="text" class="form-control" v-model="selected_country" @change="setPhoneCode()" required>
                                        <option disabled value="">Choose country</option>
                                        <option v-for="country in countries" :value="country.country">{{country.flag}} {{country.country}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="street_address" class="col-sm-4 col-form-label text-md-right">Street Address</label>
                                <div class="col-md-6">
                                    <input id="street_address" type="text" class="form-control" v-model="street_address" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="apartment_address" class="col-sm-4 col-form-label text-md-right">Apt., ste., bldg.</label>
                                <div class="col-md-6">
                                    <input id="apartment_address" type="text" class="form-control" v-model="apartment_address" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-sm-4 col-form-label text-md-right">City / Town / Village</label>
                                <div class="col-md-6">
                                    <input id="city" type="text" class="form-control" v-model="city" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="state" class="col-sm-4 col-form-label text-md-right">State / Province / Region</label>
                                <div class="col-md-6">
                                    <input id="state" type="text" class="form-control" v-model="state" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="postal_code" class="col-sm-4 col-form-label text-md-right">Postal code</label>
                                <div class="col-md-6">
                                    <input id="postal_code" type="number" class="form-control" v-model="postal_code" required pattern="\d+">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 col-form-label text-md-right">Phone</label>
                                <div class="col-md-6 custom-control-inline">
                                    <select id="dial_code" v-model="code_country" type="text" class="form-control"
                                            style="max-width: 85px; padding-left: unset; padding-right: unset" required
                                    >
                                        <option v-for="country in countries" :value="country.code">
                                            {{country.flag}}{{country.dial_code}}
                                        </option>
                                    </select>
                                    <input id="phone" type="tel" class="form-control" v-model="phone" required>
                                </div>
                            </div>
                            <div class="form-group mb-0 ">
                                <button class="col-md-3 btn btn-sm btn-success float-right" v-if="isLoggedIn" >
                                    Continue
                                </button>
                            </div>
                        </form>

                        <br>
                        <!--<div id="paypal-button"></div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {phoneUtil} from '../app'
    import {PNF} from '../app'
    export default {
        name: "OrderCheckoutComponent",
        data() {
            return {
                user_id: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                selected_country: '',
                street_address: '',
                apartment_address: '',
                city: '',
                state: '',
                postal_code: '',
                isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
                cart: [],
                items: [],
                countries: [],
                code_country: null,
                // dial_code: '',
            }
        },
        mounted() {
             this.getCountries();
             this.getCartItems();
             // this.paypalButtonRender();
        },
        watch: {
          code_country() {
              this.setSelectedCountry();
          }
        },
        methods: {
            checkAuthorize() {
                window.axios.get('/api/user', {
                    headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}
                })
                    .then(response => {
                        this.code_country = null;
                        this.phone = phoneUtil.parseAndKeepRawInput(response.data.phone, "").getNationalNumber();
                        // this.dial_code = '+'+phoneUtil.parseAndKeepRawInput(response.data.phone, "").getCountryCode();

                        this.user_id = response.data.id;
                        this.first_name = response.data.first_name;
                        this.last_name = response.data.last_name;
                        this.email = response.data.email;
                        this.code_country = phoneUtil.getRegionCodeForNumber(phoneUtil.parseAndKeepRawInput(response.data.phone, ""));
                        // this.setSelectedCountry();
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },
            getCountries() {
                window.axios.get('/api/countries')
                    .then(response => {
                        this.countries = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.statusText)
                    });
                this.checkAuthorize();
            },
            setSelectedCountry() {
                console.log(this.code_country);
                for(let country in this.countries)
                {
                    if (this.countries[country].code == this.code_country)
                    {
                        this.selected_country = this.countries[country].country;
                    }
                }
            },
            setPhoneCode() {
                console.log(this.selected_country);
                for(let country in this.countries)
                {
                    if (this.countries[country].country == this.selected_country )
                    {
                        this.code_country = this.countries[country].code;
                    }
                }
            },
            placeOrder() {

                try{
                    console.log(this.phone);
                    console.log(this.code_country);
                    var number = phoneUtil.parseAndKeepRawInput( (this.phone).toString(), (this.code_country).toString() );
                    phoneUtil.isValidNumber(number);
                }catch (error) {
                    alert(error);
                }
                if (!phoneUtil.isValidNumberForRegion(number, this.code_country)) {
                    return alert('This invalid phone number for selected country');
                }
                let pnf_E164 = phoneUtil.format(number, PNF.E164);

                let address = {'country': this.selected_country, 'country_code': this.code_country, 'state': this.state, 'city': this.city, 'street_address': this.street_address, 'apartment_address': this.apartment_address, 'postal_code': this.postal_code};
                let recipient = {'first_name': this.first_name, 'last_name': this.last_name, 'email': this.email, 'phone': pnf_E164};
                console.log(JSON.stringify(recipient));
                console.log(JSON.stringify(address));

                let formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('recipient', JSON.stringify(recipient));
                formData.append('address', JSON.stringify(address));
                formData.append('cart', JSON.stringify(this.cart));
                formData.append('status_id', '1');

                window.axios.post('/api/order', formData,
                    {headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}}
                    )
                    .then(response => {
                        console.log(response.data);
                        console.log(response);
                        let order_id = response.data.order_id;
                        if (order_id) {
                            this.$router.push({ name: 'payOrder', params:  { order_id: order_id }  });
                        }
                    })
                    .catch(errors => {
                       alert(errors.response.statusText);
                    });

            },
            getCartItems: function () {
                this.loading = true;
                window.axios.get('/cart')
                    .then(response => {
                        console.log(response.data);
                        this.cart = response.data;

                        for (let key in response.data.items) {
                            if (response.data.items.hasOwnProperty(key)) {
                                this.items.push({
                                    name: response.data.items[key].item.name,
                                    quantity: response.data.items[key].qty,
                                    price: response.data.items[key].price,
                                    sku: response.data.items[key].item.id,
                                    currency: response.data.items[key].item.currency.toUpperCase(),
                                });
                            }
                        }
                        console.log(this.items);


                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error.response.statusText);
                        this.loading = false;
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
            //                         total: vm.cart.totalPrice,
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
            //                             recipient_name: vm.first_name+' '+vm.last_name,
            //                             line1: '4th Floor',
            //                             line2: 'Unit #34',
            //                             city: 'San Jose',
            //                             country_code: 'US',
            //                             postal_code: '95131',
            //                             phone: '011862212345678',
            //                             state: 'CA'
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

</style>