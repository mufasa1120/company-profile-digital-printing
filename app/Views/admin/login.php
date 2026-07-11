<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/brutalism.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="login-page">
    <div class="login-box">
        <h1>ADMIN LOGIN</h1>
        <?php if ($msg = flash('error')): ?><div class="alert err"><?= e($msg) ?></div><?php endif; ?>
        <form method="POST" action="<?= url('/admin/login') ?>" class="form">
            <?= csrf_field() ?>
            <label>Email<input type="email" name="email" value="<?= e(old('email')) ?>" required></label>
            <label>Password<input type="password" name="password" required></label>
            <button type="submit" class="btn">MASUK →</button>
        </form>
        <p class="hint">Default: admin@printing.test / admin123</p>
    </div>
</body>
</html>
