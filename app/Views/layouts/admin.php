<?php use App\Core\Auth; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/brutalism.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin">
    <aside class="sidebar">
        <div class="logo">ADMIN PANEL</div>
        <nav>
            <a href="<?= url('/admin/dashboard') ?>" class="<?= is_active('/admin/dashboard') ?>">Dashboard</a>
            <a href="<?= url('/admin/ads') ?>" class="<?= is_active('/admin/ads') ?>">Iklan / Banner</a>
            <a href="<?= url('/admin/services') ?>" class="<?= is_active('/admin/services') ?>">Layanan</a>
            <a href="<?= url('/admin/products') ?>" class="<?= is_active('/admin/products') ?>">Produk</a>
            <a href="<?= url('/admin/portfolio') ?>" class="<?= is_active('/admin/portfolio') ?>">Portfolio</a>
            <a href="<?= url('/admin/messages') ?>" class="<?= is_active('/admin/messages') ?>">Pesan Masuk</a>
            <a href="<?= url('/admin/settings') ?>" class="<?= is_active('/admin/settings') ?>">Pengaturan</a>
        </nav>
        <form method="POST" action="<?= url('/admin/logout') ?>" class="logout">
            <?= csrf_field() ?>
            <button type="submit">Logout (<?= e(Auth::user()['name'] ?? '') ?>)</button>
        </form>
    </aside>

    <div class="content">
        <?php if ($msg = flash('success')): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
        <?php if ($msg = flash('error')): ?><div class="alert err"><?= e($msg) ?></div><?php endif; ?>
        <?= $content ?>
    </div>
    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>