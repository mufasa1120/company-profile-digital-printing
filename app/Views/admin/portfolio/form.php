<?php $err = flash_errors(); $isEdit = !empty($item); ?>
<h1><?= $isEdit ? 'EDIT' : 'TAMBAH' ?> PORTFOLIO</h1>
<form method="POST" action="<?= e($action) ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    <label>Judul<input type="text" name="title" value="<?= e($item['title'] ?? old('title')) ?>"></label>
    <?php if (isset($err['title'])): ?><small class="err"><?= e($err['title']) ?></small><?php endif; ?>

    <label>Klien (opsional)<input type="text" name="client" value="<?= e($item['client'] ?? old('client')) ?>"></label>
    <label>Kategori (opsional)<input type="text" name="category" value="<?= e($item['category'] ?? old('category')) ?>"></label>
    <label>Deskripsi<textarea name="description" rows="4"><?= e($item['description'] ?? old('description')) ?></textarea></label>

    <label>Gambar <?= $isEdit ? '(kosongkan jika tak ganti)' : '' ?><input type="file" name="image" accept="image/*"></label>
    <?php if (isset($err['image'])): ?><small class="err"><?= e($err['image']) ?></small><?php endif; ?>
    <?php if ($isEdit && !empty($item['image'])): ?><img src="<?= upload_url('portfolio', $item['image']) ?>" class="preview"><?php endif; ?>

    <label>Urutan<input type="number" name="sort_order" value="<?= e($item['sort_order'] ?? 0) ?>"></label>
    <label class="check"><input type="checkbox" name="is_active" value="1" <?= (!$isEdit || $item['is_active']) ? 'checked' : '' ?>> Aktif</label>
    <div class="form-actions"><button class="btn">SIMPAN</button><a href="<?= url('/admin/portfolio') ?>" class="btn ghost">Batal</a></div>
</form>
