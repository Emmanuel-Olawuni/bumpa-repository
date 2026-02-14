<?php

namespace App\Listeners;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;

class MergeCartAfterLogin
{
    public function handle(Login $event): void
    {
        $sessionId = session()->getId();

        $guestCart = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $event->user->id],
            ['session_id' => $sessionId]
        );

        foreach ($guestCart->items as $guestItem) {

            $existingItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity
                ]);
            } else {
                $userCart->items()->create([
                    'product_id'   => $guestItem->product_id,
                    'quantity'     => $guestItem->quantity,
                    'price_at_add' => $guestItem->price_at_add,
                ]);
            }
        }

        $guestCart->delete();

        $userCart->update(['session_id' => $sessionId]);
    }
}
