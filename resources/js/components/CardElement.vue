<template>
    <div class="container">
        <div ref="card"></div>
        <button v-on:click="purchase">Purchase</button>
    </div>

</template>

<script>
    let stripe = Stripe('pk_test_wEFBkJ4pzBO2zPiP0mSL9E1l'),
        elements = stripe.elements(),
        card = undefined;

    export default {
        mounted: function () {

            card = elements.create('card',{
                hidePostalCode: true,
                style: {
                    base: {
                        iconColor: '#666EE8',
                        color: '#31325F',
                        lineHeight: '40px',
                        fontWeight: 300,
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSize: '15px',

                        '::placeholder': {
                            color: '#CFD7E0',
                        },
                    },
                }
            });
            card.mount(this.$refs.card);
        },
        methods: {
            purchase: function () {
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        self.hasCardErrors = true;
                        self.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
                        return;
                    }else {
                        console.log(result.token);
                    }

                });
            }
        }
    };
</script>

<style scoped>

</style>