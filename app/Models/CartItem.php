<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_at_add'
    ];

    protected $casts = [
        'price_at_add' => 'decimal:2',
    ];

    protected $appends = ['subtotal'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get subtotal for this cart item
     */
    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity * $this->price_at_add,
        );
    }
}
