<h1>PENGATURAN COMPANY</h1>
<form method="POST" action="<?= url('/admin/settings') ?>" class="form" style="max-width:640px">
    <?= csrf_field() ?>
    <?php foreach (($fields ?? []) as $key => $label): ?>
        <label><?= e($label) ?>
            <?php if ($key === 'maps_embed' || $key === 'address'): ?>
                <textarea name="<?= e($key) ?>" rows="3"><?= e($settings[$key] ?? '') ?></textarea>
            <?php else: ?>
                <input type="text" name="<?= e($key) ?>" value="<?= e($settings[$key] ?? '') ?>">
            <?php endif; ?>
        </label>
    <?php endforeach; ?>
    <div class="form-actions"><button class="btn">SIMPAN PENGATURAN</button></div>
</form>
