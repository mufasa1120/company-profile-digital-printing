<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Service;

/**
 * CRUD Layanan cetak. Ikut pola AdController.
 * Bedanya: ada slug otomatis dari nama, gambar (icon) opsional.
 */
class ServiceController extends Controller
{
    private Service $model;
    private const SUB = 'products'; // icon disimpan di uploads/products

    public function __construct()
    {
        $this->model = new Service();
    }

    public function index(): void
    {
        $this->view('admin/services/index', [
            'services' => $this->model->all('sort_order', 'ASC'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->view('admin/services/form', [
            'service' => null,
            'action'  => url('/admin/services'),
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $v = new Validator($_POST, [
            'name'        => 'required|min:3|max:150',
            'description' => 'max:2000',
            'price_from'  => 'numeric',
        ]);
        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/services/create');
        }

        $data = [
            'name'        => $this->input('name'),
            'slug'        => $this->uniqueSlug(slug($this->input('name'))),
            'description' => $this->input('description'),
            'price_from'  => $this->input('price_from') !== '' ? $this->input('price_from') : null,
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'  => (int) $this->input('sort_order', 0),
        ];

        if (Upload::hasFile($_FILES['icon'] ?? [])) {
            try {
                $data['icon'] = Upload::image($_FILES['icon'], self::SUB);
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
                $this->redirect('/admin/services/create');
            }
        }

        $this->model->create($data);
        flash('success', 'Layanan berhasil ditambahkan.');
        $this->redirect('/admin/services');
    }

    public function edit(string $id): void
    {
        $service = $this->model->find($id);
        if (!$service) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/services');
        }
        $this->view('admin/services/form', [
            'service' => $service,
            'action'  => url('/admin/services/' . $id),
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $service = $this->model->find($id);
        if (!$service) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/services');
        }

        $v = new Validator($_POST, [
            'name'        => 'required|min:3|max:150',
            'description' => 'max:2000',
            'price_from'  => 'numeric',
        ]);
        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/services/' . $id . '/edit');
        }

        $data = [
            'name'        => $this->input('name'),
            'description' => $this->input('description'),
            'price_from'  => $this->input('price_from') !== '' ? $this->input('price_from') : null,
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'  => (int) $this->input('sort_order', 0),
        ];

        // slug diperbarui hanya kalau nama berubah
        if ($this->input('name') !== $service['name']) {
            $data['slug'] = $this->uniqueSlug(slug($this->input('name')), (int) $id);
        }

        if (Upload::hasFile($_FILES['icon'] ?? [])) {
            try {
                $data['icon'] = Upload::image($_FILES['icon'], self::SUB);
                Upload::delete(self::SUB, $service['icon']);
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
                $this->redirect('/admin/services/' . $id . '/edit');
            }
        }

        $this->model->update($id, $data);
        flash('success', 'Layanan berhasil diperbarui.');
        $this->redirect('/admin/services');
    }

    public function destroy(string $id): void
    {
        $service = $this->model->find($id);
        if ($service) {
            Upload::delete(self::SUB, $service['icon']);
            $this->model->delete($id);
            flash('success', 'Layanan berhasil dihapus.');
        }
        $this->redirect('/admin/services');
    }

    /**
     * Pastikan slug unik. Kalau bentrok, tambahkan -2, -3, dst.
     */
    private function uniqueSlug(string $base, int $ignoreId = 0): string
    {
        $slug = $base ?: 'layanan';
        $i = 1;
        while (true) {
            $row = $this->model->findBy('slug', $slug);
            if (!$row || (int) $row['id'] === $ignoreId) {
                return $slug;
            }
            $i++;
            $slug = $base . '-' . $i;
        }
    }
}
