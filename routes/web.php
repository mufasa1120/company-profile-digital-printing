<?php
/**
 * ============================================================
 *  RUTE PUBLIK (sisi pengunjung)
 *  $router tersedia dari public/index.php
 * ============================================================
 */

use App\Controllers\Front\HomeController;
use App\Controllers\Front\ContactController;

$router->get('/',        [HomeController::class, 'index']);
$router->get('/layanan', [HomeController::class, 'services']);
$router->get('/portfolio', [HomeController::class, 'portfolio']);
$router->get('/kontak',  [HomeController::class, 'contact']);

// Form kontak -> simpan ke DB (messages)
$router->post('/kontak', [ContactController::class, 'store']);
