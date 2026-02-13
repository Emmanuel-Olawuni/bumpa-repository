<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayInterface $gateway
    ) {}

    public function initializePayment(Order $order): array
    {
        return $this->gateway->initializePayment($order);
    }

    public function verifyPayment(string $reference): array
    {
        return $this->gateway->verifyPayment($reference);
    }

    public function processSuccessfulPayment($payment, array $data): void
    {
        $this->gateway->processSuccessfulPayment($payment, $data);
    }
}
