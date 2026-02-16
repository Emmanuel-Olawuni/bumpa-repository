<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderGroup;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function createFromCart(Cart $cart, array $customerData): Order
    {
        $itemsByMerchant = $cart->itemsByMerchant();
        $shippingPerMerchant = 2000;
        $totalShipping = $itemsByMerchant->count() * $shippingPerMerchant;
        $subtotal = $cart->total();
        $total = $subtotal + $totalShipping;

        $order = Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'user_id'        => Auth::id(),
            'total_amount'   => $total,
            'shipping_fee'   => $totalShipping,
            'payment_status' => 'pending',
            ...$customerData
        ]);

        foreach ($itemsByMerchant as $group) {
            $this->createOrderGroup($order, $group, $shippingPerMerchant);
        }

        $order->payment()->create([
            'reference' => 'BUMPA-' . strtoupper(uniqid()),
            'amount'    => $total,
            'status'    => 'pending',
        ]);

        return $order;
    }

    protected function createOrderGroup(Order $order, array $group, float $shippingFee): OrderGroup
    {
        $orderGroup = OrderGroup::create([
            'order_id'    => $order->id,
            'merchant_id' => $group['merchant']->id,
            'subtotal'    => $group['subtotal'],
            'shipping_fee' => $shippingFee,
            'status'      => 'pending',
        ]);

        foreach ($group['items'] as $cartItem) {
            $orderGroup->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity'   => $cartItem->quantity,
                'price'      => $cartItem->price_at_add,
            ]);
        }

        return $orderGroup;
    }
}
