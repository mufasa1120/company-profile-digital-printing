<?php
/**
 * ============================================================
 *  HELPER GLOBAL
 *  Fungsi pendek yang sering dipakai di controller & view.
 * ============================================================
 */

use App\Core\Csrf;

if (!function_exists('url')) {
    /**
     * Bangun URL absolut dari path internal.
     * url('/admin/ads') -> http://localhost:8000/admin/ads
     */
    function url(string $path = ''): string
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                   || (($_SERVER['SERVER_PORT'] ?? null) == 443);
            $origin = ($https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_URI;
        } else {
            $origin = APP_URL;
        }
        return rtrim($origin, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * URL ke file aset statis di public/assets.
     * asset('css/brutalism.css') -> .../assets/css/brutalism.css
     */
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    /**
     * URL ke file hasil upload.
     * upload_url('ads', 'foo.jpg') -> .../uploads/ads/foo.jpg
     */
    function upload_url(string $sub, ?string $filename): string
    {
        if (!$filename) {
            return asset('img/placeholder.png');
        }
        return url('uploads/' . $sub . '/' . $filename);
    }
}

if (!function_exists('e')) {
    /**
     * Escape output HTML (cegah XSS). WAJIB dipakai saat echo data user.
     */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect cepat lalu berhenti.
     */
    function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Ambil nilai input lama (mis. saat form gagal validasi & di-render ulang).
     * Perlu flash('_old', $_POST) sebelum redirect balik.
     */
    function old(string $key, $default = '')
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    /**
     * Simpan / ambil pesan kilat (tampil sekali lalu hilang).
     * Set:  flash('success', 'Data tersimpan');
     * Ambil: flash('success');
     */
    function flash(string $key, ?string $message = null)
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
}

if (!function_exists('flash_errors')) {
    /**
     * Simpan/ambil array error validasi untuk di-render di form.
     */
    function flash_errors(?array $errors = null): array
    {
        if ($errors !== null) {
            $_SESSION['_errors'] = $errors;
            return [];
        }
        $val = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        return $val;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Cetak hidden input berisi token CSRF untuk form POST.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . e(Csrf::token()) . '">';
    }
}

if (!function_exists('is_active')) {
    /**
     * Bantu tandai menu aktif berdasarkan path saat ini.
     * <a class="<?= is_active('/admin/ads') ?>">
     */
    function is_active(string $path, string $class = 'active'): string
    {
        $current = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $path    = rtrim($path, '/');
        return $current === $path ? $class : '';
    }
}

if (!function_exists('str_length')) {
    /**
     * Hitung panjang string. Pakai mbstring kalau ada (aman untuk
     * karakter multibyte), fallback ke strlen kalau ekstensi mati.
     */
    function str_length($str): int
    {
        $str = (string) $str;
        return function_exists('mb_strlen') ? mb_strlen($str, 'UTF-8') : strlen($str);
    }
}

if (!function_exists('str_excerpt')) {
    /**
     * Potong teks jadi ringkasan + "..." tanpa bergantung mbstring.
     */
    function str_excerpt(string $text, int $length = 50): string
    {
        if (str_length($text) <= $length) {
            return $text;
        }
        $cut = function_exists('mb_substr')
            ? mb_substr($text, 0, $length, 'UTF-8')
            : substr($text, 0, $length);
        return rtrim($cut) . '...';
    }
}

if (!function_exists('slug')) {
    /**
     * Ubah teks jadi slug URL-friendly.
     * slug('Spanduk & Banner') -> 'spanduk-banner'
     */
    function slug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text); // non-alnum jadi strip
        return trim($text, '-');
    }
}

if (!function_exists('rupiah')) {
    /**
     * Format angka jadi rupiah. rupiah(25000) -> 'Rp 25.000'
     */
    function rupiah($number): string
    {
        if ($number === null || $number === '') {
            return '-';
        }
        return 'Rp ' . number_format((float) $number, 0, ',', '.');
    }
}

if (!function_exists('dd')) {
    /**
     * Dump & die -> debugging cepat.
     */
    function dd(...$vars): void
    {
        echo '<pre style="background:#111;color:#0f0;padding:16px;font-size:13px">';
        foreach ($vars as $v) {
            var_dump($v);
        }
        echo '</pre>';
        exit;
    }
}
