<div class="page-head">
    <h1>PRODUK</h1>
    <a href="<?= url('/admin/products/create') ?>" class="btn">+ TAMBAH</a>
</div>
<table class="table">
    <thead><tr><th>Gambar</th><th>Nama</th><th>Layanan</th><th>Harga</th><th>Aktif</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach (($products ?? []) as $p): ?>
        <tr>
            <td><img src="<?= upload_url('products', $p['image']) ?>" class="thumb"></td>
            <td><?= e($p['name']) ?></td>
            <td><?= e($services[$p['service_id']] ?? '-') ?></td>
            <td><?= rupiah($p['price']) ?></td>
            <td><?= $p['is_active'] ? 'Ya' : 'Tidak' ?></td>
            <td class="actions">
                <a href="<?= url('/admin/products/' . $p['id'] . '/edit') ?>" class="btn sm">Edit</a>
                <form method="POST" action="<?= url('/admin/products/' . $p['id'] . '/delete') ?>" onsubmit="return confirm('Hapus produk ini?')">
                    <?= csrf_field() ?><button class="btn sm danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($products)): ?><tr><td colspan="6">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
</table>
