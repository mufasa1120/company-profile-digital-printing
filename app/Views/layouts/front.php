<?php $s = $setting ?? []; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($s['company_name'] ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= asset('css/brutalism.css') ?>">
</head>
<body class="front">
    <header class="nav">
        <a href="<?= url('/') ?>" class="brand"><?= e($s['company_name'] ?? APP_NAME) ?></a>
        <button class="nav-toggle" aria-label="Menu">&#9776;</button>
        <nav class="nav-links">
            <a href="<?= url('/') ?>">Home</a>
            <a href="<?= url('/layanan') ?>">Layanan</a>
            <a href="<?= url('/portfolio') ?>">Portfolio</a>
            <a href="<?= url('/kontak') ?>">Kontak</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer class="foot">
        <div>
            <strong><?= e($s['company_name'] ?? APP_NAME) ?></strong><br>
            <?php if (!empty($s['address'])): ?><?= e($s['address']) ?><br><?php endif; ?>
            <?php if (!empty($s['open_hours'])): ?><?= e($s['open_hours']) ?><?php endif; ?>
        </div>
        <div>
            <?php if (!empty($s['phone'])): ?>Telp: <?= e($s['phone']) ?><br><?php endif; ?>
            <?php if (!empty($s['whatsapp'])): ?>
                <a href="https://wa.me/<?= e($s['whatsapp']) ?>" target="_blank" style="color:inherit">WhatsApp</a><br>
            <?php endif; ?>
            <?php if (!empty($s['instagram'])): ?>
                <a href="<?= e($s['instagram']) ?>" target="_blank" style="color:inherit">Instagram</a>
            <?php endif; ?>
        </div>
        <div>&copy; <?= date('Y') ?> — Project MVC PHP Native + MySQL.</div>
    </footer>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>