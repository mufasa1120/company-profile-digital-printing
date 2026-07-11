<div class="page-head">
    <h1>PORTFOLIO</h1>
    <a href="<?= url('/admin/portfolio/create') ?>" class="btn">+ TAMBAH</a>
</div>
<table class="table">
    <thead><tr><th>Gambar</th><th>Judul</th><th>Klien</th><th>Kategori</th><th>Aktif</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach (($items ?? []) as $p): ?>
        <tr>
            <td><img src="<?= upload_url('portfolio', $p['image']) ?>" class="thumb"></td>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['client'] ?: '-') ?></td>
            <td><?= e($p['category'] ?: '-') ?></td>
            <td><?= $p['is_active'] ? 'Ya' : 'Tidak' ?></td>
            <td class="actions">
                <a href="<?= url('/admin/portfolio/' . $p['id'] . '/edit') ?>" class="btn sm">Edit</a>
                <form method="POST" action="<?= url('/admin/portfolio/' . $p['id'] . '/delete') ?>" onsubmit="return confirm('Hapus item ini?')">
                    <?= csrf_field() ?><button class="btn sm danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($items)): ?><tr><td colspan="6">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
</table>
