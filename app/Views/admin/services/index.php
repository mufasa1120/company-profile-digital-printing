<div class="page-head">
    <h1>LAYANAN</h1>
    <a href="<?= url('/admin/services/create') ?>" class="btn">+ TAMBAH</a>
</div>
<table class="table">
    <thead><tr><th>Nama</th><th>Slug</th><th>Harga Mulai</th><th>Aktif</th><th>Urutan</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach (($services ?? []) as $s): ?>
        <tr>
            <td><?= e($s['name']) ?></td>
            <td><code><?= e($s['slug']) ?></code></td>
            <td><?= rupiah($s['price_from']) ?></td>
            <td><?= $s['is_active'] ? 'Ya' : 'Tidak' ?></td>
            <td><?= (int)$s['sort_order'] ?></td>
            <td class="actions">
                <a href="<?= url('/admin/services/' . $s['id'] . '/edit') ?>" class="btn sm">Edit</a>
                <form method="POST" action="<?= url('/admin/services/' . $s['id'] . '/delete') ?>" onsubmit="return confirm('Hapus layanan ini?')">
                    <?= csrf_field() ?><button class="btn sm danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($services)): ?><tr><td colspan="6">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
</table>
