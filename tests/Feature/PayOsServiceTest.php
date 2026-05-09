<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PayOsService;

class PayOsServiceTest extends TestCase
{
    protected PayOsService $payOsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payOsService = new PayOsService();
    }

    /**
     * Test signature generation
     */
    public function test_signature_generation()
    {
        $orderCode = 'TEST-123456';
        $amount = 100000;

        $signature = $this->payOsService->generateSignature($orderCode, $amount);

        $this->assertNotEmpty($signature);
        $this->assertIsString($signature);
    }

    /**
     * Test webhook signature verification
     */
    public function test_webhook_signature_verification()
    {
        // Mock webhook data
        $data = [
            'orderCode' => 'TEST-123456',
            'amount' => 100000,
            'status' => 'PAID',
            'transactionDateTime' => now()->timestamp,
        ];

        $body = json_encode($data);
        $signature = hash_hmac('sha256', $body, config('payos.checksum_key'));

        // Verify
        $isValid = $this->payOsService->verifyWebhookSignature($body, $signature);

        $this->assertTrue($isValid);
    }

    /**
     * Test invalid signature rejection
     */
    public function test_invalid_signature_rejection()
    {
        $body = json_encode(['test' => 'data']);
        $invalidSignature = 'invalid-signature';

        $isValid = $this->payOsService->verifyWebhookSignature($body, $invalidSignature);

        $this->assertFalse($isValid);
    }
}
