<?php
/**
 * ============================================================
 *  KONFIGURASI UTAMA
 *  Dijalankan paling awal oleh public/index.php.
 *  Tugasnya: load .env, definisikan path & konstanta global.
 * ============================================================
 */

// ---------- Path dasar ----------
// BASE_PATH = root proyek (satu level di atas /public)
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',     BASE_PATH . '/app');
define('CONFIG_PATH',  BASE_PATH . '/config');
define('ROUTES_PATH',  BASE_PATH . '/routes');
define('VIEWS_PATH',   APP_PATH  . '/Views');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH',  BASE_PATH . '/public');
define('UPLOAD_PATH',  PUBLIC_PATH . '/uploads');

// ---------- Loader .env sederhana ----------
// Baca file .env baris per baris jadi $_ENV / getenv().
function load_env(string $path): void
{
    if (!is_file($path)) {
        die('File .env tidak ditemukan. Copy dulu .env.example jadi .env');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // lewati komentar & baris tanpa "="
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // buang tanda kutip pembungkus + komentar inline
        $value = preg_replace('/\s+#.*$/', '', $value);
        $value = trim($value, "\"'");

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

load_env(BASE_PATH . '/.env');

/**
 * Ambil nilai dari .env dengan fallback default.
 */
function env(string $key, $default = null)
{
    $val = $_ENV[$key] ?? getenv($key);
    if ($val === false || $val === null) {
        return $default;
    }
    // konversi string boolean jadi bool asli
    return match (strtolower($val)) {
        'true'  => true,
        'false' => false,
        'null'  => null,
        default => $val,
    };
}

// ---------- Konstanta aplikasi ----------
define('APP_NAME',  env('APP_NAME', 'Digital Printing'));
define('APP_ENV',   env('APP_ENV', 'local'));
define('APP_DEBUG', env('APP_DEBUG', false));
define('APP_URL',   rtrim(env('APP_URL', 'http://localhost:8000'), '/'));

// ---------- Error reporting sesuai environment ----------
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/error.log');
}

// ---------- Timezone ----------
date_default_timezone_set('Asia/Jakarta');

// ---------- Base URI (dukung subfolder otomatis) ----------
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$dir    = rtrim(str_replace(basename($script), '', $script), '/');
define('BASE_URI', ($dir === '' || $dir === '/') ? '' : $dir);
