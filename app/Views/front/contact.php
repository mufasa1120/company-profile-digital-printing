<?php $s = $setting ?? []; $err = flash_errors(); ?>
<section class="block">
    <h2>HUBUNGI KAMI</h2>
    <?php if ($msg = flash('success')): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
    <div class="contact-wrap">
        <form method="POST" action="<?= url('/kontak') ?>" class="form">
            <?= csrf_field() ?>
            <label>Nama<input type="text" name="name" value="<?= e(old('name')) ?>"></label>
            <?php if (isset($err['name'])): ?><small class="err"><?= e($err['name']) ?></small><?php endif; ?>
            <label>Email<input type="email" name="email" value="<?= e(old('email')) ?>"></label>
            <label>No. HP / WA<input type="text" name="phone" value="<?= e(old('phone')) ?>"></label>
            <label>Pesan<textarea name="body" rows="5"><?= e(old('body')) ?></textarea></label>
            <?php if (isset($err['body'])): ?><small class="err"><?= e($err['body']) ?></small><?php endif; ?>
            <button type="submit" class="btn">KIRIM PESAN</button>
        </form>
        <div class="contact-info">
            <?php if (!empty($s['address'])): ?>
                <p><strong>Alamat:</strong><br><?= e($s['address']) ?></p>
            <?php endif; ?>
            <?php if (!empty($s['phone'])): ?>
                <p><strong>Telepon:</strong> <?= e($s['phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($s['email'])): ?>
                <p><strong>Email:</strong> <?= e($s['email']) ?></p>
            <?php endif; ?>
            <?php if (!empty($s['whatsapp'])): ?>
                <a class="btn wa" target="_blank" href="https://wa.me/<?= e($s['whatsapp']) ?>">CHAT WHATSAPP →</a>
            <?php endif; ?>
            <?php if (!empty($s['instagram'])): ?>
                <p><strong>Instagram:</strong> <a href="<?= e($s['instagram']) ?>" target="_blank">@<?= e($s['company_name'] ?? 'kami') ?></a></p>
            <?php endif; ?>
            <?php if (!empty($s['open_hours'])): ?>
                <p><strong>Jam Buka:</strong><br><?= e($s['open_hours']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>