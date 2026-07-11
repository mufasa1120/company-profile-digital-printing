<?php $err = flash_errors(); $isEdit = !empty($product); ?>
<h1><?= $isEdit ? 'EDIT' : 'TAMBAH' ?> PRODUK</h1>
<form method="POST" action="<?= e($action) ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    <label>Nama Produk<input type="text" name="name" value="<?= e($product['name'] ?? old('name')) ?>"></label>
    <?php if (isset($err['name'])): ?><small class="err"><?= e($err['name']) ?></small><?php endif; ?>

    <label>Layanan Terkait (opsional)
        <select name="service_id">
            <option value="">— tidak ada —</option>
            <?php foreach (($services ?? []) as $s): ?>
                <option value="<?= $s['id'] ?>" <?= (($product['service_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Deskripsi<textarea name="description" rows="4"><?= e($product['description'] ?? old('description')) ?></textarea></label>
    <label>Harga (angka, opsional)<input type="number" name="price" step="any" value="<?= e($product['price'] ?? old('price')) ?>"></label>
    <?php if (isset($err['price'])): ?><small class="err"><?= e($err['price']) ?></small><?php endif; ?>

    <label>Gambar <?= $isEdit ? '(kosongkan jika tak ganti)' : '' ?><input type="file" name="image" accept="image/*"></label>
    <?php if (isset($err['image'])): ?><small class="err"><?= e($err['image']) ?></small><?php endif; ?>
    <?php if ($isEdit && !empty($product['image'])): ?><img src="<?= upload_url('products', $product['image']) ?>" class="preview"><?php endif; ?>

    <label class="check"><input type="checkbox" name="is_active" value="1" <?= (!$isEdit || $product['is_active']) ? 'checked' : '' ?>> Aktif</label>
    <div class="form-actions"><button class="btn">SIMPAN</button><a href="<?= url('/admin/products') ?>" class="btn ghost">Batal</a></div>
</form>
