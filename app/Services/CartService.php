<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    /**
     * Clear cart after successful order
     */
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->delete();
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals(Cart $cart): array
    {
        $itemsByMerchant = $cart->itemsByMerchant();
        $shippingPerMerchant = 2000;

        return [
            'subtotal' => $cart->total(),
            'shipping' => $itemsByMerchant->count() * $shippingPerMerchant,
            'total' => $cart->total() + ($itemsByMerchant->count() * $shippingPerMerchant),
        ];
    }
}
