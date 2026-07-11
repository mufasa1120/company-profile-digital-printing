<?php
/**
 * ============================================================
 *  KONFIGURASI DATABASE
 *  Nilai diambil dari .env. Dipakai oleh app/Core/Database.php.
 * ============================================================
 */

return [
    'host'    => env('DB_HOST', '127.0.0.1'),
    'port'    => env('DB_PORT', '3306'),
    'dbname'  => env('DB_NAME', 'digital_printing'),
    'user'    => env('DB_USER', 'root'),
    'pass'    => env('DB_PASS', ''),
    'charset' => 'utf8mb4',

    // Opsi PDO
    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // lempar exception saat error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // hasil query jadi array asosiatif
        PDO::ATTR_EMULATE_PREPARES   => false,                   // prepared statement asli (lebih aman)
    ],
];
