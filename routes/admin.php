<?php
/**
 * ============================================================
 *  RUTE ADMIN (butuh login, kecuali halaman login itu sendiri)
 *  Middleware ['auth'] = wajib sudah login.
 * ============================================================
 */

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\AdController;
use App\Controllers\Admin\ServiceController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\PortfolioController;
use App\Controllers\Admin\MessageController;
use App\Controllers\Admin\SettingController;

// --- Autentikasi (tanpa guard) ---
$router->get('/admin/login',  [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout',[AuthController::class, 'logout'], ['auth']);

// --- Dashboard ---
$router->get('/admin', [DashboardController::class, 'index'], ['auth']);
$router->get('/admin/dashboard', [DashboardController::class, 'index'], ['auth']);

// --- Modul Iklan/Banner (CRUD) — contoh pola, modul lain menyusul ---
$router->get('/admin/ads',            [AdController::class, 'index'],   ['auth']);
$router->get('/admin/ads/create',     [AdController::class, 'create'],  ['auth']);
$router->post('/admin/ads',           [AdController::class, 'store'],   ['auth']);
$router->get('/admin/ads/{id}/edit',  [AdController::class, 'edit'],    ['auth']);
$router->post('/admin/ads/{id}',      [AdController::class, 'update'],  ['auth']);
$router->post('/admin/ads/{id}/delete',[AdController::class, 'destroy'],['auth']);

// --- Modul Layanan (CRUD) ---
$router->get('/admin/services',             [ServiceController::class, 'index'],   ['auth']);
$router->get('/admin/services/create',      [ServiceController::class, 'create'],  ['auth']);
$router->post('/admin/services',            [ServiceController::class, 'store'],   ['auth']);
$router->get('/admin/services/{id}/edit',   [ServiceController::class, 'edit'],    ['auth']);
$router->post('/admin/services/{id}',       [ServiceController::class, 'update'],  ['auth']);
$router->post('/admin/services/{id}/delete',[ServiceController::class, 'destroy'], ['auth']);

// --- Modul Produk (CRUD) ---
$router->get('/admin/products',             [ProductController::class, 'index'],   ['auth']);
$router->get('/admin/products/create',      [ProductController::class, 'create'],  ['auth']);
$router->post('/admin/products',            [ProductController::class, 'store'],   ['auth']);
$router->get('/admin/products/{id}/edit',   [ProductController::class, 'edit'],    ['auth']);
$router->post('/admin/products/{id}',       [ProductController::class, 'update'],  ['auth']);
$router->post('/admin/products/{id}/delete',[ProductController::class, 'destroy'], ['auth']);

// --- Modul Portfolio (CRUD) ---
$router->get('/admin/portfolio',             [PortfolioController::class, 'index'],   ['auth']);
$router->get('/admin/portfolio/create',      [PortfolioController::class, 'create'],  ['auth']);
$router->post('/admin/portfolio',            [PortfolioController::class, 'store'],   ['auth']);
$router->get('/admin/portfolio/{id}/edit',   [PortfolioController::class, 'edit'],    ['auth']);
$router->post('/admin/portfolio/{id}',       [PortfolioController::class, 'update'],  ['auth']);
$router->post('/admin/portfolio/{id}/delete',[PortfolioController::class, 'destroy'], ['auth']);

// --- Modul Pesan Masuk (baca & hapus) ---
$router->get('/admin/messages',             [MessageController::class, 'index'],   ['auth']);
$router->get('/admin/messages/{id}',        [MessageController::class, 'show'],    ['auth']);
$router->post('/admin/messages/{id}/delete',[MessageController::class, 'destroy'], ['auth']);

// --- Pengaturan Company ---
$router->get('/admin/settings',  [SettingController::class, 'index'],  ['auth']);
$router->post('/admin/settings', [SettingController::class, 'update'], ['auth']);
