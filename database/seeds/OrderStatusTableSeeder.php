<?php

use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $statuses=[
        'Awaiting Payment' => 'customer has completed the checkout process, but payment has yet to be confirmed.',
        'Awaiting Fulfillment' => 'customer has completed the checkout process and payment has been confirmed.',
        'Awaiting Shipment' => 'order has been pulled and packaged and is awaiting collection from a shipping provider.',
        'Awaiting Pickup' => 'order has been packaged and is awaiting customer pickup from a seller-specified location.',
        'Partially Shipped' => 'only some items in the order have been shipped, due to some products being pre-order only or other reasons.',
        'Completed' => 'order has been shipped/picked up, and receipt is confirmed.',
        'Shipped' => 'order has been shipped, but receipt has not been confirmed; seller has used the Ship Items action.',
        'Cancelled' => 'seller has canceled an order, due to a stock inconsistency or other reasons.',
        'Declined' => 'seller has marked the order as declined for lack of manual payment, or other reasons.',
        'Refunded' => 'seller has used the Refund action.',
        'Disputed' => 'customer has initiated a dispute resolution process for the PayPal transaction that paid for the order.',
        'Verification Required' => 'order on hold while some aspect (e.g. tax-exempt documentation) needs to be manually confirmed.',
        'Partially Refunded' => 'seller has partially refunded the order.',
        ];

    public function run()
    {
        foreach ($this->statuses as $status => $description)
        {
            DB::table('order_statuses')->insert([
                'status' => $status,
                'description' => $description
            ]);
        }
    }
}
