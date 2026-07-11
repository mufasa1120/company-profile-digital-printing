<?php $err = flash_errors(); $isEdit = !empty($ad); ?>
<h1><?= $isEdit ? 'EDIT' : 'TAMBAH' ?> IKLAN</h1>
<form method="POST" action="<?= e($action) ?>" enctype="multipart/form-data" class="form">
    <?= csrf_field() ?>
    <label>Judul<input type="text" name="title" value="<?= e($ad['title'] ?? old('title')) ?>"></label>
    <?php if (isset($err['title'])): ?><small class="err"><?= e($err['title']) ?></small><?php endif; ?>

    <label>Subjudul<input type="text" name="subtitle" value="<?= e($ad['subtitle'] ?? old('subtitle')) ?>"></label>
    <label>Link URL (opsional)<input type="text" name="link_url" value="<?= e($ad['link_url'] ?? old('link_url')) ?>"></label>

    <label>Gambar <?= $isEdit ? '(kosongkan jika tak ganti)' : '' ?><input type="file" name="image" accept="image/*"></label>
    <?php if (isset($err['image'])): ?><small class="err"><?= e($err['image']) ?></small><?php endif; ?>
    <?php if ($isEdit && $ad['image']): ?><img src="<?= upload_url('ads', $ad['image']) ?>" class="preview"><?php endif; ?>

    <label>Urutan<input type="number" name="sort_order" value="<?= e($ad['sort_order'] ?? 0) ?>"></label>
    <label class="check"><input type="checkbox" name="is_active" value="1" <?= (!$isEdit || $ad['is_active']) ? 'checked' : '' ?>> Aktif</label>

    <div class="form-actions">
        <button type="submit" class="btn">SIMPAN</button>
        <a href="<?= url('/admin/ads') ?>" class="btn ghost">Batal</a>
    </div>
</form>
