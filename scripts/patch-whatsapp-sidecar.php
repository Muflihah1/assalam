<?php

/**
 * Script untuk memastikan vendor/kstmostofa/laravel-whatsapp/sidecar/index.js
 * memiliki definisi SESSION_DIR sehingga tidak mengalami ReferenceError saat dijalankan.
 */

$file = __DIR__ . '/../vendor/kstmostofa/laravel-whatsapp/sidecar/index.js';

if (!file_exists($file)) {
    exit(0);
}

$content = file_get_contents($file);

if (!str_contains($content, 'const SESSION_DIR =')) {
    $target = "const PID_FILE = process.env.SIDECAR_PID_FILE || defaultPidFile;";
    $replacement = "const PID_FILE = process.env.SIDECAR_PID_FILE || defaultPidFile;\n"
        . "const defaultSessionDir = path.resolve(__dirname, '../../../../storage/app/whatsapp-sidecar/sessions');\n"
        . "const SESSION_DIR = process.env.SESSION_DIR || process.env.WHATSAPP_WEB_SESSION_DIR || defaultSessionDir;";

    $content = str_replace($target, $replacement, $content);
    file_put_contents($file, $content);
    echo "✔ Patched vendor/kstmostofa/laravel-whatsapp/sidecar/index.js (SESSION_DIR fix)\n";
} else {
    echo "✔ vendor/kstmostofa/laravel-whatsapp/sidecar/index.js is already patched (SESSION_DIR fix).\n";
}

if (!str_contains($content, 'SingletonLock')) {
    $target = "  const client = new Client({";
    $replacement = "  const sessionDir = path.join(SESSION_DIR, `session-\${sessionId}`);\n"
        . "  if (fs.existsSync(sessionDir)) {\n"
        . "    ['SingletonLock', 'SingletonCookie', 'SingletonSocket', 'DevToolsActivePort'].forEach((f) => {\n"
        . "      try { fs.unlinkSync(path.join(sessionDir, f)); } catch (_) {}\n"
        . "    });\n"
        . "  }\n\n"
        . "  const client = new Client({";

    $content = str_replace($target, $replacement, $content);
    file_put_contents($file, $content);
    echo "✔ Patched vendor/kstmostofa/laravel-whatsapp/sidecar/index.js (SingletonLock cleanup fix)\n";
} else {
    echo "✔ vendor/kstmostofa/laravel-whatsapp/sidecar/index.js is already patched (SingletonLock cleanup fix).\n";
}
