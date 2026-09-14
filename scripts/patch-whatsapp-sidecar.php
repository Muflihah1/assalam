<?php

/**
 * Script untuk memastikan vendor/kstmostofa/laravel-whatsapp/sidecar/index.js
 * memiliki patch stabilitas lengkap (CdpPage fix, WebVersionCache, Pairing Code,
 * Session isolation, dan Chromium flags) yang identik dengan standar produksi.
 */

$targetFile = __DIR__ . '/../vendor/kstmostofa/laravel-whatsapp/sidecar/index.js';
$sourceFile = __DIR__ . '/sidecar-index.js';

if (!file_exists(dirname($targetFile))) {
    exit(0);
}

if (file_exists($sourceFile)) {
    copy($sourceFile, $targetFile);
    echo "✔ Synced robust sidecar implementation from scripts/sidecar-index.js to vendor/kstmostofa/laravel-whatsapp/sidecar/index.js\n";
} else {
    echo "⚠ Source file {$sourceFile} not found.\n";
}

// Pastikan direktori sesi ada
$sessionDir = __DIR__ . '/../storage/app/whatsapp-sidecar/sessions';
if (!is_dir($sessionDir)) {
    mkdir($sessionDir, 0755, true);
}

