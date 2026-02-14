<?php

namespace App\Listeners;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;

class MergeCartAfterLogin
{
    public function handle(Login $event): void
    {
        // Get the current session ID (guest's session)
        $sessionId = session()->getId();

        // Find guest cart by session ID
        $guestCart = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();

        // No guest cart? Nothing to merge
        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        // Find or create a cart for the logged-in user
        $userCart = Cart::firstOrCreate(
            ['user_id' => $event->user->id],
            ['session_id' => $sessionId]
        );

        // Move each item from guest cart to user cart
        foreach ($guestCart->items as $guestItem) {

            // Check if user cart already has this product
            $existingItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                // Product already in user cart - just add quantities
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity
                ]);
            } else {
                // New product - move it to user cart
                $userCart->items()->create([
                    'product_id'   => $guestItem->product_id,
                    'quantity'     => $guestItem->quantity,
                    'price_at_add' => $guestItem->price_at_add,
                ]);
            }
        }

        // Delete the old guest cart (items cascade delete)
        $guestCart->delete();

        // Update session to point to user cart
        $userCart->update(['session_id' => $sessionId]);
    }
}
