<h1>PESAN MASUK</h1>
<table class="table">
    <thead><tr><th>Status</th><th>Nama</th><th>Kontak</th><th>Pesan</th><th>Waktu</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach (($messages ?? []) as $m): ?>
        <tr style="<?= $m['is_read'] ? '' : 'font-weight:bold;background:#fffbe6' ?>">
            <td><?= $m['is_read'] ? 'Dibaca' : 'BARU' ?></td>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['email'] ?: $m['phone'] ?: '-') ?></td>
            <td><?= e(str_excerpt($m['body'], 50)) ?></td>
            <td><?= date('d/m/y H:i', strtotime($m['created_at'])) ?></td>
            <td class="actions">
                <a href="<?= url('/admin/messages/' . $m['id']) ?>" class="btn sm">Lihat</a>
                <form method="POST" action="<?= url('/admin/messages/' . $m['id'] . '/delete') ?>" onsubmit="return confirm('Hapus pesan ini?')">
                    <?= csrf_field() ?><button class="btn sm danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($messages)): ?><tr><td colspan="6">Belum ada pesan.</td></tr><?php endif; ?>
    </tbody>
</table>
