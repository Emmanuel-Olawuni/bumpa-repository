<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderGroup;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $cart = Cart::forSession();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $itemsByMerchant = $cart->itemsByMerchant();

        // Calculate shipping per merchant (simplified - ₦2000 per merchant)
        $shippingPerMerchant = 2000;
        $totalShipping = $itemsByMerchant->count() * $shippingPerMerchant;

        $subtotal = $cart->total();
        $total = $subtotal + $totalShipping;

        return Inertia::render('checkout/index', [
            'itemsByMerchant' => $itemsByMerchant->values(),
            'subtotal' => $subtotal,
            'shippingFee' => $totalShipping,
            'total' => $total,
            'user' => Auth::user()
        ]);
    }

    public function process(CheckOutRequest $request): RedirectResponse
    {


        $cart = Cart::forSession();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();

        try {
            $itemsByMerchant = $cart->itemsByMerchant();
            $shippingPerMerchant = 2000;
            $totalShipping = $itemsByMerchant->count() * $shippingPerMerchant;
            $subtotal = $cart->total();
            $total = $subtotal + $totalShipping;

            // Create the main order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'shipping_fee' => $totalShipping,
                'payment_status' => 'pending',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'state' => $request->state,
            ]);

            // Create order groups (one per merchant)
            foreach ($itemsByMerchant as $merchantId => $group) {
                $orderGroup = OrderGroup::create([
                    'order_id' => $order->id,
                    'merchant_id' => $group['merchant']->id,
                    'subtotal' => $group['subtotal'],
                    'shipping_fee' => $shippingPerMerchant,
                    'status' => 'pending',
                ]);

                // Create order items
                foreach ($group['items'] as $cartItem) {
                    $orderGroup->items()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price_at_add,
                    ]);
                }
            }

            // Create payment record
            $paymentReference = 'PAY-' . strtoupper(uniqid());

            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'gateway' => 'paystack',
                'reference' => $paymentReference,
                'status' => 'pending',
            ]);

            DB::commit();

            // Clear the cart
            $cart->items()->delete();
            $cart->delete();

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function success(Order $order): Response
    {
        $order->load(['orderGroups.items.product.merchant', 'orderGroups.merchant']);

        return Inertia::render('checkout/success', [
            'order' => $order
        ]);
    }
}
