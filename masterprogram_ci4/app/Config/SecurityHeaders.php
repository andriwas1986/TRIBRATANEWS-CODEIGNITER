<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SecureHeaders extends BaseConfig
{
    /**
     * --------------------------------------------------------------------
     * Server Headers
     * --------------------------------------------------------------------
     * Menggunakan properti public agar terbaca otomatis oleh Filter CI4.
     */

    // [Fix Report: Missing X-Content-Type-Options]
    public string $xContentTypeOptions = 'nosniff';

    // [Fix Report: Missing X-Frame-Options]
    public string $xFrameOptions = 'SAMEORIGIN';

    // [Fix Report: Missing X-XSS-Protection]
    public string $xXSSProtection = '1; mode=block';

    // [Fix Report: Missing Referrer-Policy]
    // Opsi aman yang tetap membolehkan analitik (Google Analytics/FB Pixel) bekerja
    public string $referrerPolicy = 'strict-origin-when-cross-origin';

    // [Fix Report: Missing Strict-Transport-Security (HSTS)]
    // Memaksa browser mengingat untuk selalu pakai HTTPS selama 1 tahun
    public string $strictTransportSecurity = 'max-age=31536000; includeSubDomains; preload';

    // Matikan fitur browser yang tidak dipakai (Camera, Mic, Location)
    public string $permissionsPolicy = 'geolocation=(), microphone=(), camera=()';

    /**
     * --------------------------------------------------------------------
     * Content Security Policy (CSP)
     * --------------------------------------------------------------------
     */

    // WAJIB TRUE agar pengaturan CSP di bawah ini aktif
    public bool $cspEnabled = true;

    public array $csp = [
        'default-src' => ['self'],

        'script-src'  => [
            'self',
            'unsafe-inline', // Diperlukan untuk script internal di View
            'unsafe-eval',   // Diperlukan library JS lama
            'https://www.google.com',
            'https://www.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'https://connect.facebook.net', // Script FB
        ],

        'style-src'   => [
            'self',
            'unsafe-inline',
            'https://fonts.googleapis.com',
            'https://cdnjs.cloudflare.com',
        ],

        'img-src'     => [
            'self',
            'data:',
            'https:', // Membolehkan gambar dari semua sumber HTTPS
        ],

        'font-src'    => [
            'self',
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'data:',
        ],

        'connect-src' => [
            'self',
            'https://www.facebook.com', // API connect FB
        ],

        'media-src'   => ['self'],
        'object-src'  => ['none'],

        // [SOLUSI ERROR CONSOLE]
        // Daftar domain yang boleh di-iframe (termasuk m.facebook.com)
        'frame-src'   => [
            'self',
            'https://www.google.com',
            'https://www.facebook.com',
            'https://web.facebook.com',
            'https://m.facebook.com',  // <-- FIX: Agar versi mobile FB lancar
            'https://staticxx.facebook.com',
        ],

        'frame-ancestors' => ['self'],
        'base-uri'    => ['self'],
        'form-action' => ['self'],
        'report-uri'  => null,
    ];
}