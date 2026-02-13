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
        /** @var \Illuminate\Http\Client\Response $response */

        $response = Http::withToken($this->secretKey)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $order->customer_email,
                'amount' => $order->total_amount * 100,
                'reference' => $order->payment->reference,
                'callback_url' => route('payment.callback'),
            ]);




        return $response->successful()
            ? ['status' => 'success', 'data' => $response->json()['data']]
            : ['status' => 'error', 'message' => 'Initialization failed'];
    }

    public function verifyPayment(string $reference): array
    {
        /** @var \Illuminate\Http\Client\Response $response */

        $response = Http::withToken($this->secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

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
