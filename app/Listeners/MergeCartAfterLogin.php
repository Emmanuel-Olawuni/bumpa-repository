<?php

namespace App\Listeners;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;

class MergeCartAfterLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $guestCart = Cart::whereNull('user_id')
            ->whereNotNull('session_id')
            ->with('items')
            ->latest()
            ->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => session()->getId()]
            );
            $userCart->update(['session_id' => session()->getId()]);
            return;
        }
        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => session()->getId()]
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
        $userCart->update(['session_id' => session()->getId()]);
    }
}
