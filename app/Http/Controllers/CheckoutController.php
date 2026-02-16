<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\CartService;
use App\Services\PaymentService;
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
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
        protected PaymentService $paymentService
    ) {}
    public function index(): Response|RedirectResponse
    {
        $cart = Cart::forSession();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $itemsByMerchant = $cart->itemsByMerchant();

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

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'required|email',
            'customer_phone'  => 'required|string',
            'shipping_address' => 'required|string',
            'city'            => 'required|string',
            'state'           => 'required|string',
        ]);

        $cart = Cart::forSession();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();

        try {
            $order = $this->orderService->createFromCart($cart, $validated);

            $order->load('payment');

            if (!$order->payment) {
                throw new \RuntimeException('Payment record was not created for order #' . $order->id);
            }

            $result = $this->paymentService->initializePayment($order);

            if ($result['status'] === 'success') {

                $this->cartService->clearCart($cart);
                DB::commit();
                return Inertia::location($result['data']['authorization_url']);
            }

            DB::rollBack();
            return back()->with('error', $result['message'] ?? 'Payment initialization failed');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with(
                'error',
                app()->isLocal()
                    ? $e->getMessage()
                    : 'Something went wrong. Please try again.'
            );
        }
    }

    public function success(Order $order): Response
    {
        $order->load(['orderGroups.items.product.merchant', 'orderGroups.merchant']);

        return Inertia::render('checkout/success', [
            'order' => $order
        ]);
    }

    public function paymentCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('home')->with('error', 'Invalid payment callback');
        }

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Payment record not found');
        }

        $result = $this->paymentService->verifyPayment($reference);

        if ($result['status'] === 'success') {
            $this->paymentService->processSuccessfulPayment($payment, $result['data']);
            return redirect()->route('checkout.success', ['order' => $payment->order_id])
                ->with('success', 'Payment successful! Your order is being processed.');
        }

        return redirect()->route('home')->with('error', 'Payment verification failed');
    }
}
