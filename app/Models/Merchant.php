<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = ['name', 'slug', 'email', 'phone', 'logo', 'payout_account'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderGroups()
    {
        return $this->hasMany(OrderGroup::class);
    }
}
