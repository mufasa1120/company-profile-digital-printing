<?php
namespace App\Core;

/**
 * ============================================================
 *  UPLOAD
 *  Validasi + simpan file gambar ke folder public/uploads/{sub}.
 *  Return nama file yang tersimpan, atau lempar RuntimeException.
 *
 *  Contoh:
 *      $filename = Upload::image($_FILES['image'], 'ads');
 *      // simpan $filename ke kolom DB, tampilkan via upload_url('ads', $filename)
 * ============================================================
 */
class Upload
{
    /** Ekstensi & MIME yang diizinkan */
    private const ALLOWED = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
        'gif'  => 'image/gif',
    ];

    /** Ukuran maksimum (byte). Default 3 MB. */
    private const MAX_SIZE = 3 * 1024 * 1024;

    /**
     * Proses upload gambar tunggal.
     *
     * @param array  $file  entri dari $_FILES['field']
     * @param string $sub   subfolder di dalam uploads (ads|products|portfolio)
     * @return string nama file yang tersimpan
     */
    public static function image(array $file, string $sub): string
    {
        // 1. cek error bawaan PHP
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(self::errorMessage($file['error'] ?? -1));
        }

        // 2. cek ukuran
        if ($file['size'] > self::MAX_SIZE) {
            throw new \RuntimeException('Ukuran file melebihi 3 MB.');
        }

        // 3. cek MIME asli (bukan cuma ekstensi -> lebih aman)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $ext   = array_search($mime, self::ALLOWED, true);

        if ($ext === false) {
            throw new \RuntimeException('Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.');
        }

        // 4. pastikan folder tujuan ada
        $dir = UPLOAD_PATH . '/' . $sub;
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        // 5. nama file unik: <slug-waktu>-<random>.ext
        $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $name;

        // 6. pindahkan dari tmp ke tujuan
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Gagal menyimpan file.');
        }

        return $name;
    }

    /**
     * Hapus file lama (mis. saat update/replace gambar).
     */
    public static function delete(string $sub, ?string $filename): void
    {
        if (!$filename) {
            return;
        }
        $path = UPLOAD_PATH . '/' . $sub . '/' . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Apakah field file benar-benar diisi user?
     */
    public static function hasFile(array $file): bool
    {
        return isset($file['error']) && $file['error'] === UPLOAD_ERR_OK;
    }

    private static function errorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File terlalu besar.',
            UPLOAD_ERR_PARTIAL   => 'Upload terputus, coba lagi.',
            UPLOAD_ERR_NO_FILE   => 'Tidak ada file yang dipilih.',
            default              => 'Gagal upload file.',
        };
    }
}
