<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(): Response
    {
        $cart = Cart::forSession();

        $itemsByMerchant = $cart?->itemsByMerchant() ?? collect();
        $total = $cart?->total() ?? 0;

        return Inertia::render('cart/index', [
            'itemsByMerchant' => $itemsByMerchant->values(),
            'total' => $total
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart = Cart::createForSession();

        // Update or create cart item
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Not enough stock available');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price_at_add' => $product->price
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    public function update(Request $request, $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::forSession();
        $cartItem = $cart->items()->findOrFail($itemId);

        // Check stock
        if ($cartItem->product->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Cart updated');
    }

    public function remove($itemId): RedirectResponse
    {
        $cart = Cart::forSession();
        $cart->items()->findOrFail($itemId)->delete();

        return back()->with('success', 'Item removed from cart');
    }
}
