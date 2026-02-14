<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function itemsByMerchant(): Collection
    {
        return $this->items()
            ->with('product.merchant')
            ->get()
            ->groupBy('product.merchant_id')
            ->map(function ($items, $merchantId) {
                return [
                    'merchant' => $items->first()->product->merchant,
                    'items'    => $items,
                    'subtotal' => $items->sum('subtotal')
                ];
            });
    }

    public function total(): float
    {
        return $this->items->sum('subtotal');
    }

    /**
     * Get cart for this request.
     * 
     */
    public static function forSession(): ?Cart
    {
        if (Auth::check()) {
            return static::where('user_id', Auth::id())
                ->with('items')
                ->first();
        }

        // Guest: find by session
        return static::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->with('items')
            ->first();
    }

    /**
     * Get or create cart for this request.
     */
    public static function createForSession(): Cart
    {
        if (Auth::check()) {
            $cart = static::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => session()->getId()]
            );

            // Keep session_id fresh
            if ($cart->session_id !== session()->getId()) {
                $cart->update(['session_id' => session()->getId()]);
            }

            return $cart;
        }

        return static::firstOrCreate(
            ['session_id' => session()->getId()],
            ['user_id' => null]
        );
    }
}
