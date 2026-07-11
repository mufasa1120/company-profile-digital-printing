<?php
namespace App\Core;

/**
 * ============================================================
 *  CSRF PROTECTION
 *  Cegah request palsu lintas situs pada form POST.
 *
 *  Di form:   <?= csrf_field() ?>
 *  Di handler POST: Csrf::verify() dipanggil otomatis oleh index.php
 * ============================================================
 */
class Csrf
{
    private const KEY = '_csrf_token';

    /**
     * Ambil token saat ini (buat kalau belum ada).
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    /**
     * Verifikasi token dari form. Kalau gagal -> hentikan (419).
     */
    public static function verify(): void
    {
        $sent = $_POST[self::KEY] ?? '';
        $real = $_SESSION[self::KEY] ?? '';

        if (!$real || !hash_equals($real, $sent)) {
            http_response_code(419);
            die('Sesi kedaluwarsa atau token CSRF tidak valid. Muat ulang halaman.');
        }
    }
}
