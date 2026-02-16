<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaystackGateway implements PaymentGatewayInterface
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('paystack.secretKey');
    }

    public function initializePayment(Order $order): array
    {
        $response = Http::withToken($this->secretKey)
            ->withOptions([
                'verify' => app()->isProduction(),
            ])
            ->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $order->customer_email,
                'amount'       => (int)($order->total_amount * 100),
                'reference'    => $order->payment->reference,
                'callback_url' => route('payment.callback'),
            ]);
        /**
         * @var \Illuminate\Http\Client\Response $response
         */

        return $response->successful()
            ? ['status' => 'success', 'data' => $response->json()['data']]
            : ['status' => 'error', 'message' => $response->json()['message'] ?? 'Initialization failed'];
    }

    public function verifyPayment(string $reference): array
    {
        $response = Http::withToken($this->secretKey)
            ->withOptions([
                'verify' => app()->isProduction(),
            ])
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        /**
         * @var \Illuminate\Http\Client\Response $response
         */

        return $response->successful()
            ? ['status' => 'success', 'data' => $response->json()['data']]
            : ['status' => 'error', 'message' => 'Verification failed'];
    }

    public function processSuccessfulPayment($payment, array $data): void
    {
        $payment->update([
            'status' => 'success',
            'response_data' => $data
        ]);

        $payment->order->update(['payment_status' => 'paid']);
    }
}
