<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PayOsService
{
    protected $clientId;
    protected $apiKey;
    protected $checksumKey;
    protected $apiUrl;
    protected $webhookUrl;

    public function __construct()
    {
        $this->clientId = config('payos.client_id');
        $this->apiKey = config('payos.api_key');
        $this->checksumKey = config('payos.checksum_key');
        $this->apiUrl = config('payos.api_url');
        $this->webhookUrl = config('payos.webhook_url');
    }

    /**
     * Tạo đơn hàng QR code thanh toán
     */
    public function createQRCode(string $orderCode, int $amount, string $description, string $returnUrl)
    {
        $data = [
            'orderCode' => $orderCode,
            'amount' => $amount,
            'description' => $description,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $returnUrl,
            'signature' => $this->generateSignature($orderCode, $amount),
        ];

        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-api-key' => $this->apiKey,
            ])->post("{$this->apiUrl}/qr-code/generate", $data);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to create QR code',
                'code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function getTransactionStatus(string $orderCode)
    {
        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-api-key' => $this->apiKey,
            ])->get("{$this->apiUrl}/transaction-status/{$orderCode}");

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to get status',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hủy thanh toán
     */
    public function cancelTransaction(string $orderCode)
    {
        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-api-key' => $this->apiKey,
            ])->post("{$this->apiUrl}/transaction/{$orderCode}/cancel");

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to cancel',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Tạo chữ ký (signature) cho request
     * Signature = HMAC-SHA256(checksum_key, orderCode|amount)
     */
    public function generateSignature(string $orderCode, int $amount): string
    {
        $data = "{$orderCode}|{$amount}";
        return hash_hmac('sha256', $data, $this->checksumKey);
    }

    /**
     * Verify webhook signature từ PayOs
     */
    public function verifyWebhookSignature(string $data, string $signature): bool
    {
        $calculated = hash_hmac('sha256', $data, $this->checksumKey);
        return hash_equals($calculated, $signature);
    }
}
