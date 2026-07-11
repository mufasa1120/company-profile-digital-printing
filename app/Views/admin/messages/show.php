<div class="page-head"><h1>DETAIL PESAN</h1><a href="<?= url('/admin/messages') ?>" class="btn ghost">← Kembali</a></div>
<div class="card" style="max-width:640px">
    <p><strong>Nama:</strong> <?= e($message['name']) ?></p>
    <p><strong>Email:</strong> <?= e($message['email'] ?: '-') ?></p>
    <p><strong>No. HP/WA:</strong> <?= e($message['phone'] ?: '-') ?></p>
    <p><strong>Subjek:</strong> <?= e($message['subject'] ?: '-') ?></p>
    <p><strong>Waktu:</strong> <?= date('d M Y, H:i', strtotime($message['created_at'])) ?></p>
    <hr style="border:2px solid #111;margin:16px 0">
    <p><?= nl2br(e($message['body'])) ?></p>
    <?php if (!empty($message['phone'])): ?>
        <a class="btn wa" target="_blank" href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $message['phone'])) ?>" style="margin-top:16px">BALAS VIA WHATSAPP →</a>
    <?php endif; ?>
</div>
