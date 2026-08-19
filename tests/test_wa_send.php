<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WaTemplate;
use App\Models\WaMessageLog;
use App\Services\WhatsAppNotificationService;

$waService = app(WhatsAppNotificationService::class);
$phone = '081807057736';
$recipient = 'Pelanggan Setia Assalam Mebel';

echo "========================================================\n";
echo "1. MENGIRIM 5 TEMPLATE WHATSAPP KE: {$phone}\n";
echo "========================================================\n\n";

$templates = [
    'order_created' => [
        'nama' => $recipient,
        'no_pesanan' => 'ORD-2026-9901',
        'produk' => 'Set Meja Makan Jati Solid Trembesi Jepara',
        'total_harga' => '8.500.000',
        'dp_amount' => '4.250.000',
        'link_tracking' => 'http://127.0.0.1:8000/customer/progress',
    ],
    'dp_verified' => [
        'nama' => $recipient,
        'no_pesanan' => 'ORD-2026-9901',
        'produk' => 'Set Meja Makan Jati Solid Trembesi Jepara',
        'dp_amount' => '4.250.000',
        'link_tracking' => 'http://127.0.0.1:8000/customer/progress',
    ],
    'progress_updated' => [
        'nama' => $recipient,
        'no_pesanan' => 'ORD-2026-9901',
        'produk' => 'Set Meja Makan Jati Solid Trembesi Jepara',
        'tahap' => 'Perakitan & Finishing Natural',
        'catatan' => 'Penyatuan sambungan kayu purus dan penghalusan serat alami jati telah selesai.',
        'link_tracking' => 'http://127.0.0.1:8000/customer/progress',
    ],
    'payment_completed' => [
        'nama' => $recipient,
        'no_pesanan' => 'ORD-2026-9901',
        'produk' => 'Set Meja Makan Jati Solid Trembesi Jepara',
        'link_tracking' => 'http://127.0.0.1:8000/customer/progress',
    ],
    'order_finished' => [
        'nama' => $recipient,
        'no_pesanan' => 'ORD-2026-9901',
        'produk' => 'Set Meja Makan Jati Solid Trembesi Jepara',
    ],
];

$sentLogs = [];

foreach ($templates as $code => $vars) {
    $tmpl = WaTemplate::where('code', $code)->first();
    if (!$tmpl) {
        echo "Template {$code} tidak ditemukan!\n";
        continue;
    }

    $body = WhatsAppNotificationService::parseTemplate($tmpl->content, $vars);
    echo ">> Mengirim [{$tmpl->name}] ({$code})...\n";

    $log = $waService->sendMessage($phone, $recipient, $body, $code);
    echo "   [Status: {$log->status}] ID Log: #{$log->id}\n";
    echo "   Payload Respon: " . substr($log->response_payload ?? '', 0, 100) . "...\n\n";

    $sentLogs[] = $log;
    sleep(1); // Jeda 1 detik antar pengiriman
}

echo "========================================================\n";
echo "2. SIMULASI PESAN GAGAL (FAILED) & TEST RETRY SYSTEM\n";
echo "========================================================\n\n";

// Buat 1 simulasi pesan gagal untuk setiap template
$failedLogIds = [];

foreach ($templates as $code => $vars) {
    $tmpl = WaTemplate::where('code', $code)->first();
    $body = WhatsAppNotificationService::parseTemplate($tmpl->content, $vars);

    $simulatedFailedLog = WaMessageLog::create([
        'order_id' => null,
        'recipient_name' => $recipient . ' (Simulasi Gagal)',
        'recipient_phone' => WhatsAppNotificationService::formatPhoneNumber($phone),
        'template_code' => $code,
        'message_body' => $body,
        'status' => 'Failed',
        'response_payload' => json_encode(['error' => 'Simulated Network Timeout / Gateway Busy']),
        'retry_count' => 0,
    ]);

    echo ">> [DIBUAT] Log Gagal untuk template [{$code}]: ID #{$simulatedFailedLog->id}\n";
    $failedLogIds[] = $simulatedFailedLog->id;
}

echo "\n>> Menjalankan Retry pada seluruh pesan simulasi gagal...\n";

foreach ($failedLogIds as $id) {
    $retryResult = $waService->retryMessage($id);
    echo "   >> Retry ID #{$id} -> Status Baru: [{$retryResult['status']}] (Success: " . ($retryResult['success'] ? 'YES' : 'NO') . ")\n";
    sleep(1);
}

echo "\n========================================================\n";
echo "PENGUJIAN SELESAI!\n";
echo "========================================================\n";
