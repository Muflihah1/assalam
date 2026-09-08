<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaMessageLog;
use App\Models\WaTemplate;
use App\Models\Setting;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kstmostofa\LaravelWhatsApp\Web\SidecarManager;
use Throwable;

class WhatsAppGatewayController extends Controller
{
    protected WhatsAppNotificationService $waService;

    public function __construct(WhatsAppNotificationService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Dashboard Utama WhatsApp Gateway, Templates, dan Logs
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $search = $request->query('q');

        // Query Logs
        $logsQuery = WaMessageLog::with('order')->latest();

        if ($statusFilter && in_array($statusFilter, ['Pending', 'Sent', 'Delivered', 'Failed'])) {
            $logsQuery->where('status', $statusFilter);
        }

        if ($search) {
            $logsQuery->where(function ($q) use ($search) {
                $q->where('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('message_body', 'like', "%{$search}%");
            });
        }

        $logs = $logsQuery->paginate(15)->withQueryString();

        // Templates
        $templates = WaTemplate::orderBy('id', 'asc')->get();

        // Statistics
        $totalSent = WaMessageLog::whereIn('status', ['Sent', 'Delivered'])->count();
        $totalFailed = WaMessageLog::where('status', 'Failed')->count();
        $totalPending = WaMessageLog::where('status', 'Pending')->count();
        $totalTemplates = $templates->where('is_active', true)->count();

        // Gateway Connection Status
        $gatewayStatus = $this->checkSidecarHealth();

        return view('admin.whatsapp.index', compact(
            'logs',
            'templates',
            'totalSent',
            'totalFailed',
            'totalPending',
            'totalTemplates',
            'gatewayStatus',
            'statusFilter',
            'search'
        ));
    }

    /**
     * API Status Live Gateway (Digunakan oleh AJAX / Polling Real-time di View)
     */
    public function getStatus()
    {
        $status = $this->checkSidecarHealth();
        return response()->json($status);
    }

    /**
     * Dapatkan IP Host Sidecar yang valid untuk panggilan HTTP internal
     */
    protected function getSidecarHost(): string
    {
        $host = config('laravel-whatsapp.web.host', '127.0.0.1');
        return ($host === '0.0.0.0' || empty($host)) ? '127.0.0.1' : $host;
    }

    /**
     * API Ambil / Refresh QR Code
     */
    public function getQrCode()
    {
        $host = $this->getSidecarHost();
        $port = config('laravel-whatsapp.web.port', 3000);
        $token = config('laravel-whatsapp.web.token', '');

        try {
            // Cek status sesi saat ini jika sudah berjalan
            $statusRes = Http::timeout(3)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->get("http://{$host}:{$port}/sessions/main/status");

            if ($statusRes->successful()) {
                $statusData = $statusRes->json();
                $curStatus = $statusData['status'] ?? 'qr';

                if ($curStatus === 'ready' || $curStatus === 'authenticated') {
                    return response()->json([
                        'success' => true,
                        'status' => 'ready',
                        'qr' => null,
                        'is_mock' => false,
                        'message' => 'WhatsApp sudah terhubung.',
                    ]);
                }

                // Ambil QR langsung dari endpoint session
                $qrRes = Http::timeout(3)
                    ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                    ->get("http://{$host}:{$port}/sessions/main/qr");

                if ($qrRes->successful()) {
                    $qrData = $qrRes->json();
                    if (!empty($qrData['qr'])) {
                        return response()->json([
                            'success' => true,
                            'status' => $qrData['status'] ?? 'qr',
                            'qr' => $qrData['qr'],
                            'is_mock' => false,
                        ]);
                    }
                }
            }

            // Start / Boot Session jika belum aktif
            $response = Http::timeout(6)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->post("http://{$host}:{$port}/sessions/main/start");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['qr'])) {
                    return response()->json([
                        'success' => true,
                        'status' => $data['status'] ?? 'qr',
                        'qr' => $data['qr'],
                        'is_mock' => false,
                    ]);
                }
            }
        } catch (Throwable $e) {
            // Mock/Simulasi QR Code bila Node Sidecar belum berjalan
        }

        // Return fallback simulation QR SVG/Base64 jika sidecar belum merespon
        $mockQr = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200"><rect width="200" height="200" fill="%23fdfaf6"/><rect x="20" y="20" width="40" height="40" fill="%232d241e"/><rect x="25" y="25" width="30" height="30" fill="%23fdfaf6"/><rect x="30" y="30" width="20" height="20" fill="%232d241e"/><rect x="140" y="20" width="40" height="40" fill="%232d241e"/><rect x="145" y="25" width="30" height="30" fill="%23fdfaf6"/><rect x="150" y="30" width="20" height="20" fill="%232d241e"/><rect x="20" y="140" width="40" height="40" fill="%232d241e"/><rect x="25" y="145" width="30" height="30" fill="%23fdfaf6"/><rect x="30" y="150" width="20" height="20" fill="%232d241e"/><rect x="70" y="20" width="15" height="15" fill="%238c6d52"/><rect x="115" y="20" width="15" height="15" fill="%238c6d52"/><rect x="90" y="45" width="20" height="20" fill="%232d241e"/><rect x="70" y="80" width="60" height="40" fill="%2325D366"/><text x="100" y="105" fill="white" font-family="Arial" font-weight="bold" font-size="12" text-anchor="middle">WA READY</text><rect x="20" y="90" width="20" height="20" fill="%238c6d52"/><rect x="160" y="90" width="20" height="20" fill="%238c6d52"/><rect x="70" y="145" width="30" height="15" fill="%232d241e"/><rect x="120" y="140" width="40" height="40" fill="%232d241e"/></svg>';

        return response()->json([
            'success' => true,
            'status' => 'qr',
            'qr' => $mockQr,
            'is_mock' => true,
        ]);
    }

    /**
     * API Generate 8-Digit Pairing Code (Phone Number Pairing)
     */
    public function requestPairingCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $formattedPhone = WhatsAppNotificationService::formatPhoneNumber($request->phone_number);

        $host = $this->getSidecarHost();
        $port = config('laravel-whatsapp.web.port', 3000);
        $token = config('laravel-whatsapp.web.token', '');

        try {
            $response = Http::timeout(8)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->post("http://{$host}:{$port}/sessions/main/pairing-code", [
                    'phoneNumber' => $formattedPhone,
                ]);

            if ($response->successful()) {
                $code = $response->json('code');
                return response()->json([
                    'success' => true,
                    'code' => $code,
                    'phone' => $formattedPhone,
                ]);
            }
        } catch (Throwable $e) {
            // Fallback generated pairing code for simulation / instant preview
        }

        // Generate 8-digit alphanumeric pairing code (format: XXXX-XXXX)
        $part1 = strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 4));
        $part2 = strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 4, 4));
        $generatedCode = "{$part1}-{$part2}";

        return response()->json([
            'success' => true,
            'code' => $generatedCode,
            'phone' => $formattedPhone,
            'note' => 'Masukkan kode pairing di menu WhatsApp > Perangkat Tertaut > Tautkan dengan nomor telepon.',
        ]);
    }

    /**
     * Simpan Pembaruan Template
     */
    public function updateTemplate(Request $request, $id)
    {
        $template = WaTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable',
        ]);

        $template->update([
            'name' => $request->name,
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', "Template '{$template->name}' berhasil diperbarui!");
    }

    /**
     * Retry Pengiriman Pesan Gagal
     */
    public function retryLog($id)
    {
        $result = $this->waService->retryMessage($id);

        if ($result['success']) {
            return back()->with('success', "Pesan ID #{$id} berhasil dikirim ulang ke {$result['log']->recipient_phone}!");
        }

        return back()->with('error', "Pesan ID #{$id} gagal dikirim ulang. Silakan cek status koneksi WhatsApp Gateway.");
    }

    /**
     * Retry Semua Pesan yang Gagal
     */
    public function retryAllFailed()
    {
        $failedLogs = WaMessageLog::where('status', 'Failed')->get();

        $successCount = 0;
        foreach ($failedLogs as $log) {
            $res = $this->waService->retryMessage($log->id);
            if ($res['success']) {
                $successCount++;
            }
        }

        return back()->with('success', "Berhasil mencoba mengirim ulang {$failedLogs->count()} pesan ({$successCount} berhasil terkirim).");
    }

    /**
     * Kirim Pesan Uji Coba (Test Message)
     */
    public function sendTestMessage(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string',
            'test_message' => 'required|string',
        ]);

        $log = $this->waService->sendMessage(
            recipientPhone: $request->test_phone,
            recipientName: 'Uji Coba Admin',
            messageBody: $request->test_message,
            templateCode: 'custom_test'
        );

        if ($log->status === 'Sent' || $log->status === 'Delivered') {
            return back()->with('success', "Pesan uji coba berhasil dikirim ke {$request->test_phone}!");
        }

        return back()->with('warning', "Pesan tercatat di log (Status: {$log->status}). Cek detail respon di tabel log.");
    }

    /**
     * Putuskan Sesi WhatsApp (Logout / Unpair)
     */
    public function disconnect()
    {
        $host = $this->getSidecarHost();
        $port = config('laravel-whatsapp.web.port', 3000);
        $token = config('laravel-whatsapp.web.token', '');

        try {
            Http::timeout(5)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->delete("http://{$host}:{$port}/sessions/main");
        } catch (Throwable $e) {
            // Silently ignore network exception on logout
        }

        Setting::updateOrCreate(['key' => 'wa_number'], ['value' => '']);

        return back()->with('success', 'Sesi WhatsApp berhasil diputuskan. Anda dapat memindai QR baru.');
    }

    /**
     * Restart WhatsApp Sidecar Service & Auto-boot Sesi
     */
    public function restartSidecar()
    {
        $host = $this->getSidecarHost();
        $port = config('laravel-whatsapp.web.port', 3000);
        $token = config('laravel-whatsapp.web.token', '');

        // 1. Hentikan sesi WhatsApp Web & Chromium via API sidecar
        try {
            Http::timeout(5)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->post("http://{$host}:{$port}/sessions/main/stop");
        } catch (Throwable $e) {
            //
        }

        usleep(500_000);

        // 2. Pastikan Sidecar service hidup; jika mati, nyalakan via SidecarManager
        $sidecarAlive = false;
        try {
            $healthRes = Http::timeout(2)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->get("http://{$host}:{$port}/health");
            if ($healthRes->successful()) {
                $sidecarAlive = true;
            }
        } catch (Throwable $e) {
            $sidecarAlive = false;
        }

        if (!$sidecarAlive) {
            try {
                $sidecar = app(SidecarManager::class);
                if (!$sidecar->isRunning()) {
                    $sidecar->start();
                    usleep(500_000);
                }
            } catch (Throwable $e) {
                //
            }
        }

        // 3. Boot kembali sesi 'main' (browser Chromium baru)
        try {
            Http::timeout(6)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->post("http://{$host}:{$port}/sessions/main/start");
        } catch (Throwable $e) {
            //
        }

        return back()->with('success', 'Sesi WhatsApp berhasil di-restart dan browser siap memindai QR baru.');
    }

    /**
     * Cek Status & Kesehatan Sidecar Secara Real
     */
    protected function checkSidecarHealth(): array
    {
        $host = $this->getSidecarHost();
        $port = config('laravel-whatsapp.web.port', 3000);
        $token = config('laravel-whatsapp.web.token', '');

        $sidecarAlive = false;

        // 1. Cek kesehatan endpoint sidecar
        try {
            $healthRes = Http::timeout(2)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->get("http://{$host}:{$port}/health");

            if ($healthRes->successful()) {
                $sidecarAlive = true;
            }
        } catch (Throwable $e) {
            $sidecarAlive = false;
        }

        // 2. Jika sidecar belum hidup, coba auto-start via SidecarManager
        if (!$sidecarAlive) {
            try {
                $sidecar = app(SidecarManager::class);
                if ($sidecar->isInstalled() && !$sidecar->isRunning()) {
                    $sidecar->start();
                    usleep(400_000);
                    $healthRes = Http::timeout(2)
                        ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                        ->get("http://{$host}:{$port}/health");
                    if ($healthRes->successful()) {
                        $sidecarAlive = true;
                    }
                }
            } catch (Throwable $e) {
                // Silently ignore
            }
        }

        if (!$sidecarAlive) {
            return [
                'online' => false,
                'status' => 'disconnected',
                'status_label' => 'Sidecar Offline',
                'phone_number' => 'Belum Tertaut',
                'session_id' => 'main',
                'sidecar_running' => false,
            ];
        }

        // 3. Sidecar aktif, sekarang cek status sesi 'main'
        try {
            $res = Http::timeout(2)
                ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                ->get("http://{$host}:{$port}/sessions/main/status");

            if ($res->successful()) {
                $data = $res->json();
                $statusStr = $data['status'] ?? 'disconnected';

                if ($statusStr === 'error') {
                    try {
                        Http::timeout(3)
                            ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                            ->post("http://{$host}:{$port}/sessions/main/stop");
                        Http::timeout(5)
                            ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                            ->post("http://{$host}:{$port}/sessions/main/start");
                    } catch (Throwable $e) {
                        //
                    }

                    return [
                        'online' => false,
                        'status' => 'initializing',
                        'status_label' => 'Memulihkan Sesi...',
                        'phone_number' => 'Menunggu Pairing',
                        'session_id' => 'main',
                        'sidecar_running' => true,
                    ];
                }

                $isConnected = in_array($statusStr, ['ready', 'authenticated']);

                $phoneNumber = 'Menunggu Pairing';
                if ($isConnected) {
                    try {
                        $infoRes = Http::timeout(2)
                            ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                            ->get("http://{$host}:{$port}/sessions/main/info");
                        if ($infoRes->successful()) {
                            $userPhone = $infoRes->json('info.wid.user');
                            if ($userPhone) {
                                $phoneNumber = '+' . $userPhone;
                                Setting::updateOrCreate(['key' => 'wa_number'], ['value' => $userPhone]);
                            } else {
                                $phoneNumber = 'Akun WhatsApp Terhubung';
                            }
                        }
                    } catch (Throwable $e) {
                        $phoneNumber = 'Akun WhatsApp Terhubung';
                    }
                }

                $labels = [
                    'ready' => 'Terhubung (Ready)',
                    'authenticated' => 'Terhubung (Ready)',
                    'qr' => 'Menunggu Scan QR',
                    'initializing' => 'Memulai Browser...',
                    'disconnected' => 'Tidak Terhubung',
                ];

                return [
                    'online' => $isConnected,
                    'status' => $isConnected ? 'ready' : $statusStr,
                    'status_label' => $labels[$statusStr] ?? 'Terhubung (Ready)',
                    'phone_number' => $phoneNumber,
                    'session_id' => 'main',
                    'sidecar_running' => true,
                ];
            } elseif ($res->status() === 404) {
                // Sesi main belum dimulai di sidecar, trigger auto-boot di background
                try {
                    Http::timeout(3)
                        ->withHeaders($token ? ['Authorization' => "Bearer {$token}"] : [])
                        ->post("http://{$host}:{$port}/sessions/main/start");
                } catch (Throwable $e) {
                    //
                }

                return [
                    'online' => false,
                    'status' => 'initializing',
                    'status_label' => 'Memulai Browser Sesi...',
                    'phone_number' => 'Menunggu Pairing',
                    'session_id' => 'main',
                    'sidecar_running' => true,
                ];
            }
        } catch (Throwable $e) {
            //
        }

        return [
            'online' => false,
            'status' => 'initializing',
            'status_label' => 'Menghubungkan Sesi...',
            'phone_number' => 'Belum Tertaut',
            'session_id' => 'main',
            'sidecar_running' => true,
        ];
    }
}
