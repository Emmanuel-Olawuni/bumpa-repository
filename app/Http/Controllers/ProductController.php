<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $products = Product::with('merchant')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        // Group products by merchant
        $productsByMerchant = $products->groupBy('merchant_id')->map(function ($items) {
            return [
                'merchant' => $items->first()->merchant,
                'products' => $items->values()
            ];
        })->values();

        return Inertia::render('products/index', [
            'productsByMerchant' => $productsByMerchant
        ]);
    }

    public function show(Product $product): Response
    {
        $product->load('merchant');

        return Inertia::render('products/show', [
            'product' => $product
        ]);
    }
}
