<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\PayOsService;
use Illuminate\Http\Request;

/**
 * Testing controller for PayOs
 * Helper functions for development/testing
 */
class PayOsTestController extends Controller
{
    protected PayOsService $payOsService;

    public function __construct(PayOsService $payOsService)
    {
        $this->payOsService = $payOsService;
    }

    /**
     * Test webhook signature generation
     */
    public function generateTestSignature()
    {
        $testData = [
            'orderCode' => 'TK123456',
            'amount' => 100000,
            'status' => 'PAID',
            'transactionDateTime' => now()->timestamp,
        ];

        $body = json_encode($testData);
        $signature = hash_hmac(
            'sha256',
            $body,
            config('payos.checksum_key')
        );

        return [
            'test_data' => $testData,
            'body' => $body,
            'signature' => $signature,
            'usage' => 'POST to /webhook/payos with X-Signature header',
        ];
    }

    /**
     * Simulate PayOs webhook for testing
     * Only for development!
     */
    public function simulateWebhook(Request $request)
    {
        if (!env('APP_DEBUG', false)) {
            return response()->json(['message' => 'Only available in debug mode'], 403);
        }

        $orderCode = $request->input('order_code', 'TK123456');
        $status = $request->input('status', 'PAID');

        $testData = [
            'orderCode' => $orderCode,
            'amount' => 100000,
            'status' => $status,
            'transactionDateTime' => now()->timestamp,
        ];

        $body = json_encode($testData);
        $signature = hash_hmac(
            'sha256',
            $body,
            config('payos.checksum_key')
        );

        // Call webhook
        $response = $this->post('/webhook/payos', $testData, [
            'X-Signature' => $signature,
        ]);

        return response()->json([
            'message' => 'Webhook simulation sent',
            'order_code' => $orderCode,
            'status' => $status,
        ]);
    }

    /**
     * View test page
     */
    public function testPage()
    {
        return view('admin.payos-test', [
            'signature_info' => $this->generateTestSignature(),
        ]);
    }
}
