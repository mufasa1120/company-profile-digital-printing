<?php $err = flash_errors(); $isEdit = !empty($service); ?>
<h1><?= $isEdit ? 'EDIT' : 'TAMBAH' ?> LAYANAN</h1>
<form method="POST" action="<?= e($action) ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    <label>Nama Layanan<input type="text" name="name" value="<?= e($service['name'] ?? old('name')) ?>"></label>
    <?php if (isset($err['name'])): ?><small class="err"><?= e($err['name']) ?></small><?php endif; ?>

    <label>Deskripsi<textarea name="description" rows="4"><?= e($service['description'] ?? old('description')) ?></textarea></label>
    <label>Harga Mulai (angka, opsional)<input type="number" name="price_from" step="any" value="<?= e($service['price_from'] ?? old('price_from')) ?>"></label>
    <?php if (isset($err['price_from'])): ?><small class="err"><?= e($err['price_from']) ?></small><?php endif; ?>

    <label>Ikon/Gambar <?= $isEdit ? '(kosongkan jika tak ganti)' : '(opsional)' ?><input type="file" name="icon" accept="image/*"></label>
    <?php if ($isEdit && !empty($service['icon'])): ?><img src="<?= upload_url('products', $service['icon']) ?>" class="preview"><?php endif; ?>

    <label>Urutan<input type="number" name="sort_order" value="<?= e($service['sort_order'] ?? 0) ?>"></label>
    <label class="check"><input type="checkbox" name="is_active" value="1" <?= (!$isEdit || $service['is_active']) ? 'checked' : '' ?>> Aktif</label>
    <div class="form-actions"><button class="btn">SIMPAN</button><a href="<?= url('/admin/services') ?>" class="btn ghost">Batal</a></div>
</form>
