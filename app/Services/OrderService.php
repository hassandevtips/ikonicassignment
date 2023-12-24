<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
        $existingOrder = Order::where('external_order_id', $data['order_id'])->exists();
        if ($existingOrder) {
            return;
        }
        $merchant = Merchant::where('domain', $data['merchant_domain'])->first();

        $affiliate = Affiliate::where('merchant_id', $merchant->id)->first();

         $this->affiliateService->register(
                $merchant,
                $data['customer_email'],
                $data['customer_name'],
                0.1
            );

        $commissionExpected = $data['subtotal_price'] * $affiliate->commission_rate;

        Order::create([
            'subtotal' => $data['subtotal_price'],
            'affiliate_id' => $affiliate->id,
            'merchant_id' => $merchant->id,
            'commission_owed' => $commissionExpected,
            'external_order_id' => $data['order_id']
        ]);

        // if u like my code please give me a feedback i tried my best to make it clean
    }
}
