/* ============================================================
   MAIN.JS — sisi publik (front)
   Vanilla JS, tanpa library. Semua fitur "progressive enhancement":
   kalau JS mati, situs tetap jalan normal.
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ---------- 1. Toggle menu navigasi di layar HP ---------- */
    // Tombol hamburger (.nav-toggle) muncul di mobile via CSS.
    // Diklik -> buka/tutup daftar menu (.nav-links).
    var toggle = document.querySelector('.nav-toggle');
    var links  = document.querySelector('.nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
        });
    }

    /* ---------- 2. Auto-hilang pesan sukses ---------- */
    // Notifikasi hijau (mis. "Pesan lu udah masuk") ilang sendiri
    // setelah 4 detik biar nggak nyangkut di layar.
    document.querySelectorAll('.alert.ok').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });

    /* ---------- 3. Smooth scroll untuk link jangkar ---------- */
    // Link yang nunjuk ke #bagian di halaman yang sama -> scroll halus.
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

});