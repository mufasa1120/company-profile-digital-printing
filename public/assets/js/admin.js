/* ============================================================
   ADMIN.JS — sisi admin panel
   Vanilla JS, progressive enhancement.
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ---------- 1. Preview gambar sebelum di-upload ---------- */
    // Tiap <input type="file"> di form: begitu user pilih gambar,
    // langsung tampil preview-nya tanpa perlu submit dulu.
    document.querySelectorAll('input[type="file"]').forEach(function (input) {
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            // cari elemen preview yang sudah dibuat JS, atau bikin baru
            var preview = input.parentNode.querySelector('.js-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.className = 'js-preview preview';
                input.parentNode.appendChild(preview);
            }

            var reader = new FileReader();
            reader.onload = function (e) { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        });
    });

    /* ---------- 2. Auto-hilang notifikasi sukses ---------- */
    document.querySelectorAll('.alert.ok').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });

    /* ---------- 3. Konfirmasi hapus yang lebih jelas ---------- */
    // Form apa pun dengan atribut data-confirm akan minta konfirmasi
    // sebelum submit (alternatif dari onsubmit inline).
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

});