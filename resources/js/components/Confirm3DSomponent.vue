<template>
    <div>
        Confirmation payment from 3DSecure:<br>
        {{order_id}}<br>
        {{source_id}}<br>
        {{client_secret}}<br><hr>
        Amount: {{amount}}&nbsp;{{currency}}<br>
        Email: {{email}}<br>
        Description: {{description}}<br>
        {{message}}
        <button class="btn btn-success" type="button" v-if="isButtonVisible" @click="createCharge(source_id)">
            <span id="btn_spinner" v-if="isSpinnerVisible" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span>Confirm pay</span>
        </button>
    </div>
</template>

<script>
    let stripe = Stripe(process.env.MIX_STRIPE_KEY);

    export default {
        name: "Confirm3DSomponent",
        props: ['order_id'],
        data() {
            return {
                source_id: this.$route.query.source,
                client_secret: this.$route.query.client_secret,
                message: '',
                email: null,
                amount: null,
                currency: null,
                description: null,
                pollCount: 10,
                isButtonVisible: false,
                isSpinnerVisible: false,
            }
        },
        mounted() {
            this.checkSource();
        },
        methods: {
            checkSource() {
                let vm = this;
                stripe.retrieveSource({
                    id: this.source_id,
                    client_secret: this.client_secret,
                })
                    .then(function(response) {
                    if(response.source.status === 'chargeable') {
                        console.log(response.source);
                        vm.email = response.source.owner.email;
                        vm.amount = response.source.amount / 100;
                        vm.currency = response.source.currency;
                        vm.description = response.source.metadata.description;
                        vm.isButtonVisible = true ;

                    } else if (response.source.status === 'pending' && pollCount > 0) {
                        // Try again in a second, if the Source is still `pending`:
                        vm.pollCount -= 1;
                        setTimeout(vm.checkSource, 1000);
                    } else {
                        // Depending on the Source status, show your customer the relevant message.
                        vm.message = 'Bank is declined transaction';
                    }
                });
            },
            createCharge(source_id) {
                this.isSpinnerVisible = true;
                window.axios.post('/api/charge',
                    {source_id: source_id, email: this.email, amount: this.amount, currency: this.currency, order_id: this.order_id, description: this.description},
                    {headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')}}
                )
                    .then(response => {
                        this.isSpinnerVisible = false;
                        console.log(response.data);
                        this.message = response.statusText;
                        alert(response.data.status);
                        this.$router.push({name: 'userOrders'});
                    })
                    .catch(error => {
                        this.isSpinnerVisible = false;
                        console.log(error.response);
                    });
            },
        }
    }
</script>

<style scoped>

</style>