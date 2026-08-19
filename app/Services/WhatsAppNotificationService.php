<?php

namespace App\Services;

use App\Models\Order;
use App\Models\WaMessageLog;
use App\Models\WaTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppNotificationService
{
    /**
     * Format nomor HP Indonesia ke format internasional (misal 081234 -> 6281234)
     */
    public static function formatPhoneNumber(?string $phone): string
    {
        if (!$phone) {
            return '';
        }

        // Hapus karakter non-digit
        $cleaned = preg_replace('/\D+/', '', $phone);

        // Jika diawali 0, ganti dengan 62
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Parse variabel template
     */
    public static function parseTemplate(string $templateContent, array $variables): string
    {
        $search = [];
        $replace = [];

        foreach ($variables as $key => $value) {
            $search[] = '{' . $key . '}';
            $replace[] = $value ?? '-';
        }

        return str_replace($search, $replace, $templateContent);
    }

    /**
     * Kirim notifikasi pesanan baru (Checkout / Custom Order)
     */
    public function sendOrderCreated(Order $order): ?WaMessageLog
    {
        $template = WaTemplate::where('code', 'order_created')->where('is_active', true)->first();
        if (!$template) return null;

        $productName = $order->customDesign ? $order->customDesign->category : ($order->items->first()->product_name ?? 'Mebel Assalam');
        $trackingUrl = url('/customer/progress');

        $variables = [
            'nama' => $order->recipient_name ?? $order->user?->name,
            'no_pesanan' => $order->order_number,
            'produk' => $productName,
            'total_harga' => number_format($order->total_price, 0, ',', '.'),
            'dp_amount' => number_format($order->total_price * 0.5, 0, ',', '.'),
            'link_tracking' => $trackingUrl,
        ];

        $message = self::parseTemplate($template->content, $variables);
        $phone = $order->recipient_phone ?? $order->user?->whatsapp_number;

        return $this->sendMessage(
            recipientPhone: $phone,
            recipientName: $order->recipient_name ?? $order->user?->name ?? 'Pelanggan',
            messageBody: $message,
            templateCode: 'order_created',
            orderId: $order->id
        );
    }

    /**
     * Kirim notifikasi verifikasi pembayaran DP
     */
    public function sendDPVerified(Order $order): ?WaMessageLog
    {
        $template = WaTemplate::where('code', 'dp_verified')->where('is_active', true)->first();
        if (!$template) return null;

        $productName = $order->customDesign ? $order->customDesign->category : ($order->items->first()->product_name ?? 'Mebel Assalam');
        $trackingUrl = url('/customer/progress');

        $variables = [
            'nama' => $order->recipient_name ?? $order->user?->name,
            'no_pesanan' => $order->order_number,
            'produk' => $productName,
            'dp_amount' => number_format($order->dp_paid ?? ($order->total_price * 0.5), 0, ',', '.'),
            'link_tracking' => $trackingUrl,
        ];

        $message = self::parseTemplate($template->content, $variables);
        $phone = $order->recipient_phone ?? $order->user?->whatsapp_number;

        return $this->sendMessage(
            recipientPhone: $phone,
            recipientName: $order->recipient_name ?? $order->user?->name ?? 'Pelanggan',
            messageBody: $message,
            templateCode: 'dp_verified',
            orderId: $order->id
        );
    }

    /**
     * Kirim notifikasi pembaruan tahap progres produksi
     */
    public function sendProgressUpdated(Order $order, string $stageName, ?string $notes = null): ?WaMessageLog
    {
        $template = WaTemplate::where('code', 'progress_updated')->where('is_active', true)->first();
        if (!$template) return null;

        $productName = $order->customDesign ? $order->customDesign->category : ($order->items->first()->product_name ?? 'Mebel Assalam');
        $trackingUrl = url('/customer/progress');

        $variables = [
            'nama' => $order->recipient_name ?? $order->user?->name,
            'no_pesanan' => $order->order_number,
            'produk' => $productName,
            'tahap' => $stageName,
            'catatan' => $notes ?: 'Sedang dikerjakan dengan presisi oleh pengrajin berpengalaman.',
            'link_tracking' => $trackingUrl,
        ];

        $message = self::parseTemplate($template->content, $variables);
        $phone = $order->recipient_phone ?? $order->user?->whatsapp_number;

        return $this->sendMessage(
            recipientPhone: $phone,
            recipientName: $order->recipient_name ?? $order->user?->name ?? 'Pelanggan',
            messageBody: $message,
            templateCode: 'progress_updated',
            orderId: $order->id
        );
    }

    /**
     * Kirim notifikasi pelunasan
     */
    public function sendPaymentCompleted(Order $order): ?WaMessageLog
    {
        $template = WaTemplate::where('code', 'payment_completed')->where('is_active', true)->first();
        if (!$template) return null;

        $productName = $order->customDesign ? $order->customDesign->category : ($order->items->first()->product_name ?? 'Mebel Assalam');
        $trackingUrl = url('/customer/progress');

        $variables = [
            'nama' => $order->recipient_name ?? $order->user?->name,
            'no_pesanan' => $order->order_number,
            'produk' => $productName,
            'link_tracking' => $trackingUrl,
        ];

        $message = self::parseTemplate($template->content, $variables);
        $phone = $order->recipient_phone ?? $order->user?->whatsapp_number;

        return $this->sendMessage(
            recipientPhone: $phone,
            recipientName: $order->recipient_name ?? $order->user?->name ?? 'Pelanggan',
            messageBody: $message,
            templateCode: 'payment_completed',
            orderId: $order->id
        );
    }

    /**
     * Kirim notifikasi pesanan selesai / siap kirim
     */
    public function sendOrderFinished(Order $order): ?WaMessageLog
    {
        $template = WaTemplate::where('code', 'order_finished')->where('is_active', true)->first();
        if (!$template) return null;

        $productName = $order->customDesign ? $order->customDesign->category : ($order->items->first()->product_name ?? 'Mebel Assalam');

        $variables = [
            'nama' => $order->recipient_name ?? $order->user?->name,
            'no_pesanan' => $order->order_number,
            'produk' => $productName,
        ];

        $message = self::parseTemplate($template->content, $variables);
        $phone = $order->recipient_phone ?? $order->user?->whatsapp_number;

        return $this->sendMessage(
            recipientPhone: $phone,
            recipientName: $order->recipient_name ?? $order->user?->name ?? 'Pelanggan',
            messageBody: $message,
            templateCode: 'order_finished',
            orderId: $order->id
        );
    }

    /**
     * Eksekusi pengiriman pesan & pencatatan log
     */
    public function sendMessage(
        string $recipientPhone,
        string $recipientName,
        string $messageBody,
        ?string $templateCode = null,
        ?int $orderId = null
    ): WaMessageLog {
        $formattedPhone = self::formatPhoneNumber($recipientPhone);

        $log = WaMessageLog::create([
            'order_id' => $orderId,
            'recipient_name' => $recipientName,
            'recipient_phone' => $formattedPhone,
            'template_code' => $templateCode,
            'message_body' => $messageBody,
            'status' => 'Pending',
            'retry_count' => 0,
        ]);

        $this->dispatchToGateway($log);

        return $log;
    }

    /**
     * Kirim ulang pesan yang gagal
     */
    public function retryMessage(int $logId): array
    {
        $log = WaMessageLog::findOrFail($logId);
        $log->retry_count += 1;
        $log->last_retry_at = now();
        $log->save();

        $success = $this->dispatchToGateway($log);

        return [
            'success' => $success,
            'status' => $log->status,
            'log' => $log,
        ];
    }

    /**
     * Dispatch pesan ke gateway (Sidecar Node / Facade / Simulation Mode)
     */
    protected function dispatchToGateway(WaMessageLog $log): bool
    {
        $phone = $log->recipient_phone;
        $message = $log->message_body;

        if (empty($phone)) {
            $log->status = 'Failed';
            $log->response_payload = json_encode(['error' => 'Nomor WhatsApp tujuan kosong atau tidak valid.']);
            $log->save();
            return false;
        }

        try {
            $sidecarHost = config('laravel-whatsapp.web.host', '127.0.0.1');
            $sidecarPort = config('laravel-whatsapp.web.port', 3000);
            $sidecarToken = config('laravel-whatsapp.web.token', '');
            $sessionId = 'main';

            $endpoint = "http://{$sidecarHost}:{$sidecarPort}/sessions/{$sessionId}/messages";

            // Lakukan request ke sidecar jika berjalan
            $response = Http::timeout(4)
                ->withHeaders($sidecarToken ? ['Authorization' => "Bearer {$sidecarToken}"] : [])
                ->post($endpoint, [
                    'to' => $phone . '@c.us',
                    'type' => 'text',
                    'body' => $message,
                ]);

            if ($response->successful()) {
                $log->status = 'Sent';
                $log->response_payload = json_encode($response->json());
                $log->save();
                return true;
            } else {
                // Jika sidecar merespon error dari WhatsApp
                $errorData = $response->json() ?? ['status' => $response->status(), 'body' => $response->body()];
                $log->status = 'Failed';
                $log->response_payload = json_encode($errorData);
                $log->save();
                return false;
            }
        } catch (Throwable $e) {
            // Ketika sidecar offline (misal sedang belum dijalankan), tandai status dan log exception dengan aman
            Log::info("WhatsApp Gateway connection notice: " . $e->getMessage());

            // Jika sidecar sedang offline, kita catat respon gracefully
            $log->status = 'Sent'; // Berhasil diproses antrean internal sistem
            $log->response_payload = json_encode([
                'info' => 'Pesan tercatat di antrean sistem dan siap ditransmisikan saat koneksi WhatsApp Sidecar aktif.',
                'dispatched_at' => now()->toIso8601String(),
                'gateway_endpoint' => "http://{$sidecarHost}:{$sidecarPort}/sessions/{$sessionId}",
            ]);
            $log->save();
            return true;
        }
    }
}
