<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Ad;

/**
 * ============================================================
 *  CRUD IKLAN/BANNER
 *  Ini POLA CONTOH untuk semua modul admin lain
 *  (Service, Product, Portfolio tinggal ikut struktur ini).
 * ============================================================
 */
class AdController extends Controller
{
    private Ad $model;
    private const SUB = 'ads'; // subfolder upload

    public function __construct()
    {
        $this->model = new Ad();
    }

    /** Daftar semua iklan. */
    public function index(): void
    {
        $this->view('admin/ads/index', [
            'ads' => $this->model->all('sort_order', 'ASC'),
        ], 'layouts/admin');
    }

    /** Form tambah. */
    public function create(): void
    {
        $this->view('admin/ads/form', [
            'ad'     => null,
            'action' => url('/admin/ads'),
        ], 'layouts/admin');
    }

    /** Simpan data baru. */
    public function store(): void
    {
        $v = new Validator($_POST, [
            'title'    => 'required|min:3|max:150',
            'subtitle' => 'max:255',
            'link_url' => 'max:255',
        ]);

        // gambar wajib saat create
        if (!Upload::hasFile($_FILES['image'] ?? [])) {
            $errors = $v->errors();
            $errors['image'] = 'Gambar wajib diunggah.';
            flash_errors($errors);
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ads/create');
        }

        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ads/create');
        }

        try {
            $filename = Upload::image($_FILES['image'], self::SUB);
        } catch (\RuntimeException $e) {
            flash('error', $e->getMessage());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ads/create');
        }

        $this->model->create([
            'title'      => $this->input('title'),
            'subtitle'   => $this->input('subtitle'),
            'image'      => $filename,
            'link_url'   => $this->input('link_url'),
            'is_active'  => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);

        flash('success', 'Iklan berhasil ditambahkan.');
        $this->redirect('/admin/ads');
    }

    /** Form edit. */
    public function edit(string $id): void
    {
        $ad = $this->model->find($id);
        if (!$ad) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/ads');
        }
        $this->view('admin/ads/form', [
            'ad'     => $ad,
            'action' => url('/admin/ads/' . $id),
        ], 'layouts/admin');
    }

    /** Update data. */
    public function update(string $id): void
    {
        $ad = $this->model->find($id);
        if (!$ad) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/ads');
        }

        $v = new Validator($_POST, [
            'title'    => 'required|min:3|max:150',
            'subtitle' => 'max:255',
            'link_url' => 'max:255',
        ]);
        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ads/' . $id . '/edit');
        }

        $data = [
            'title'      => $this->input('title'),
            'subtitle'   => $this->input('subtitle'),
            'link_url'   => $this->input('link_url'),
            'is_active'  => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int) $this->input('sort_order', 0),
        ];

        // ganti gambar hanya jika user upload baru
        if (Upload::hasFile($_FILES['image'] ?? [])) {
            try {
                $data['image'] = Upload::image($_FILES['image'], self::SUB);
                Upload::delete(self::SUB, $ad['image']); // hapus file lama
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
                $this->redirect('/admin/ads/' . $id . '/edit');
            }
        }

        $this->model->update($id, $data);
        flash('success', 'Iklan berhasil diperbarui.');
        $this->redirect('/admin/ads');
    }

    /** Hapus data + file gambarnya. */
    public function destroy(string $id): void
    {
        $ad = $this->model->find($id);
        if ($ad) {
            Upload::delete(self::SUB, $ad['image']);
            $this->model->delete($id);
            flash('success', 'Iklan berhasil dihapus.');
        }
        $this->redirect('/admin/ads');
    }
}
