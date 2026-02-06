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

    /**
     * Get cart items grouped by merchant
     */
    public function itemsByMerchant(): Collection
    {
        return $this->items()
            ->with('product.merchant')
            ->get()
            ->groupBy('product.merchant_id')
            ->map(function ($items, $merchantId) {
                return [
                    'merchant' => $items->first()->product->merchant,
                    'items' => $items,
                    'subtotal' => $items->sum('subtotal')
                ];
            });
    }

    /**
     * Calculate cart total
     */
    public function total(): float
    {
        return $this->items->sum('subtotal');
    }

    /**
     * Get or create cart for current session
     */
    public static function forSession(): ?Cart
    {
        $sessionId = session()->getId();

        return static::where('session_id', $sessionId)
            ->first();
    }

    /**
     * Create cart for current session if doesn't exist
     */
    public static function createForSession(): Cart
    {
        return static::firstOrCreate([
            'session_id' => session()->getId()
        ], [
            'user_id' => Auth::id()
        ]);
    }
}
