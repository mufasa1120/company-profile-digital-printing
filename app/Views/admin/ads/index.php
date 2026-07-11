<div class="page-head">
    <h1>IKLAN / BANNER</h1>
    <a href="<?= url('/admin/ads/create') ?>" class="btn">+ TAMBAH</a>
</div>
<table class="table">
    <thead><tr><th>Gambar</th><th>Judul</th><th>Aktif</th><th>Urutan</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach (($ads ?? []) as $ad): ?>
        <tr>
            <td><img src="<?= upload_url('ads', $ad['image']) ?>" class="thumb"></td>
            <td><?= e($ad['title']) ?></td>
            <td><?= $ad['is_active'] ? 'Ya' : 'Tidak' ?></td>
            <td><?= (int)$ad['sort_order'] ?></td>
            <td class="actions">
                <a href="<?= url('/admin/ads/' . $ad['id'] . '/edit') ?>" class="btn sm">Edit</a>
                <form method="POST" action="<?= url('/admin/ads/' . $ad['id'] . '/delete') ?>" onsubmit="return confirm('Hapus iklan ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn sm danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($ads)): ?><tr><td colspan="5">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
</table>
