<?php
/**
 * ============================================================
 *  FRONT CONTROLLER
 *  Satu-satunya pintu masuk aplikasi.
 *  Semua request dari .htaccess diarahkan ke sini.
 *
 *  Alur:
 *    1. Load config & .env
 *    2. Daftarkan autoloader (PSR-4 sederhana)
 *    3. Mulai session
 *    4. Load helper global
 *    5. Verifikasi CSRF untuk request POST
 *    6. Daftarkan rute + middleware
 *    7. Dispatch ke controller
 * ============================================================
 */

// 1. Konfigurasi & environment
require dirname(__DIR__) . '/config/config.php';

// 2. Autoloader PSR-4: prefix "App\" -> folder /app
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = APP_PATH . '/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

// 3. Session
session_name(env('SESSION_NAME', 'dp_session'));
session_start();

// 4. Helper global
require APP_PATH . '/Helpers/functions.php';

use App\Core\Router;
use App\Core\Auth;
use App\Core\Csrf;

// 5. Proteksi CSRF untuk semua POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::verify();
}

// 6. Router + middleware
$router = new Router();
$router->middleware('auth', fn() => Auth::guard());

// muat definisi rute
require ROUTES_PATH . '/web.php';    // rute publik
require ROUTES_PATH . '/admin.php';  // rute admin

// 7. Jalankan
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
