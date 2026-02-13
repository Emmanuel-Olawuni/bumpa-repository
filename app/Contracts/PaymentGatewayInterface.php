<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function initializePayment(Order $order): array;
    public function verifyPayment(string $reference): array;
    public function processSuccessfulPayment($payment, array $data): void;
}
