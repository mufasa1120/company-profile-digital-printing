<?php $s = $setting ?? []; ?>
<section class="hero">
    <span class="deco deco-1 star" aria-hidden="true"></span>
    <span class="deco deco-2 star" aria-hidden="true"></span>
    <span class="deco deco-3 burst" aria-hidden="true"></span>
    <?php $hero = $ads[0] ?? null; ?>
    <div class="hero-text">
        <span class="eyebrow">Percetakan Digital</span>
        <h1><?= e($s['company_name'] ?? 'DIGITAL PRINTING') ?></h1>
        <p class="tagline"><?= e($s['tagline'] ?? 'Cetak cepat, hasil nampol.') ?></p>
        <div class="hero-cta">
            <a class="btn" href="<?= url('/kontak') ?>">PESAN SEKARANG →</a>
            <?php if (!empty($s['whatsapp'])): ?>
                <a class="btn wa" target="_blank" href="https://wa.me/<?= e($s['whatsapp']) ?>">CHAT WA →</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($hero): ?>
        <div class="hero-img">
            <img src="<?= upload_url('ads', $hero['image']) ?>" alt="<?= e($hero['title']) ?>">
        </div>
    <?php endif; ?>
</section>

<section class="block">
    <span class="deco star deco-l1" aria-hidden="true"></span>
    <span class="deco burst deco-l2" aria-hidden="true"></span>
    <h2>LAYANAN KAMI</h2>
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
            <p>Belum ada layanan. Tambah lewat admin panel.</p>
        <?php endif; ?>
    </div>
</section>

<section class="block alt">
    <span class="deco burst deco-p1" aria-hidden="true"></span>
    <span class="deco star deco-p2" aria-hidden="true"></span>
    <span class="deco star deco-p3" aria-hidden="true"></span>
    <h2>PRODUK KAMI</h2>
    <div class="grid">
        <?php foreach (($products ?? []) as $p): ?>
            <article class="card">
                <img src="<?= upload_url('products', $p['image']) ?>" alt="<?= e($p['name']) ?>">
                <h3><?= e($p['name']) ?></h3>
                <p><?= e($p['description']) ?></p>
                <?php if ($p['price']): ?>
                    <span class="price"><?= rupiah($p['price']) ?></span>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <p>Belum ada produk. Tambah lewat admin panel.</p>
        <?php endif; ?>
    </div>
</section>

<section class="block">
    <span class="deco star deco-f1" aria-hidden="true"></span>
    <span class="deco burst deco-f2" aria-hidden="true"></span>
    <h2>PORTFOLIO</h2>
    <div class="gallery">
        <?php foreach (($portfolio ?? []) as $p): ?>
            <figure class="card">
                <img src="<?= upload_url('portfolio', $p['image']) ?>" alt="<?= e($p['title']) ?>">
                <figcaption><?= e($p['title']) ?></figcaption>
            </figure>
        <?php endforeach; ?>
        <?php if (empty($portfolio)): ?>
            <p>Belum ada portfolio...</p>
        <?php endif; ?>
    </div>
</section>