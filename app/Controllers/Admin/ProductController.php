<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Product;
use App\Models\Service;

/**
 * CRUD Produk. Punya relasi opsional ke Service (dropdown).
 */
class ProductController extends Controller
{
    private Product $model;
    private const SUB = 'products';

    public function __construct()
    {
        $this->model = new Product();
    }

    public function index(): void
    {
        $this->view('admin/products/index', [
            'products' => $this->model->all('id', 'DESC'),
            'services' => $this->serviceMap(),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->view('admin/products/form', [
            'product'  => null,
            'services' => (new Service())->all('name', 'ASC'),
            'action'   => url('/admin/products'),
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $v = new Validator($_POST, [
            'name'        => 'required|min:2|max:150',
            'description' => 'max:2000',
            'price'       => 'numeric',
        ]);
        if ($v->fails() || !Upload::hasFile($_FILES['image'] ?? [])) {
            $errors = $v->errors();
            if (!Upload::hasFile($_FILES['image'] ?? [])) {
                $errors['image'] = 'Gambar produk wajib diunggah.';
            }
            flash_errors($errors);
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/products/create');
        }

        try {
            $filename = Upload::image($_FILES['image'], self::SUB);
        } catch (\RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/products/create');
        }

        $this->model->create([
            'service_id'  => $this->input('service_id') !== '' ? (int) $this->input('service_id') : null,
            'name'        => $this->input('name'),
            'description' => $this->input('description'),
            'image'       => $filename,
            'price'       => $this->input('price') !== '' ? $this->input('price') : null,
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ]);

        flash('success', 'Produk berhasil ditambahkan.');
        $this->redirect('/admin/products');
    }

    public function edit(string $id): void
    {
        $product = $this->model->find($id);
        if (!$product) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/products');
        }
        $this->view('admin/products/form', [
            'product'  => $product,
            'services' => (new Service())->all('name', 'ASC'),
            'action'   => url('/admin/products/' . $id),
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $product = $this->model->find($id);
        if (!$product) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/products');
        }

        $v = new Validator($_POST, [
            'name'        => 'required|min:2|max:150',
            'description' => 'max:2000',
            'price'       => 'numeric',
        ]);
        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/products/' . $id . '/edit');
        }

        $data = [
            'service_id'  => $this->input('service_id') !== '' ? (int) $this->input('service_id') : null,
            'name'        => $this->input('name'),
            'description' => $this->input('description'),
            'price'       => $this->input('price') !== '' ? $this->input('price') : null,
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ];

        if (Upload::hasFile($_FILES['image'] ?? [])) {
            try {
                $data['image'] = Upload::image($_FILES['image'], self::SUB);
                Upload::delete(self::SUB, $product['image']);
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
                $this->redirect('/admin/products/' . $id . '/edit');
            }
        }

        $this->model->update($id, $data);
        flash('success', 'Produk berhasil diperbarui.');
        $this->redirect('/admin/products');
    }

    public function destroy(string $id): void
    {
        $product = $this->model->find($id);
        if ($product) {
            Upload::delete(self::SUB, $product['image']);
            $this->model->delete($id);
            flash('success', 'Produk berhasil dihapus.');
        }
        $this->redirect('/admin/products');
    }

    /** [id => nama] untuk menampilkan nama layanan di tabel. */
    private function serviceMap(): array
    {
        $map = [];
        foreach ((new Service())->all() as $s) {
            $map[$s['id']] = $s['name'];
        }
        return $map;
    }
}
