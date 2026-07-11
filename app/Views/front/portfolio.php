<section class="block">
    <h2>PORTFOLIO</h2>
    <div class="gallery">
        <?php foreach (($portfolio ?? []) as $p): ?>
            <figure class="card"><img src="<?= upload_url('portfolio', $p['image']) ?>" alt="<?= e($p['title']) ?>"><figcaption><?= e($p['title']) ?><?= $p['client'] ? ' — ' . e($p['client']) : '' ?></figcaption></figure>
        <?php endforeach; ?>
        <?php if (empty($portfolio)): ?><p>Belum ada portfolio.</p><?php endif; ?>
    </div>
</section>
