<section class="block">
    <h2>SEMUA LAYANAN</h2>
    <div class="grid">
        <?php foreach (($services ?? []) as $sv): ?>
            <article class="card">
                <h3><?= e($sv['name']) ?></h3>
                <p><?= e($sv['description']) ?></p>
                <?php if ($sv['price_from']): ?>
                    <span class="price">Mulai <?= rupiah($sv['price_from']) ?></span>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (empty($services)): ?>
            <p>Belum ada layanan yang ditampilkan.</p>
        <?php endif; ?>
    </div>
</section>