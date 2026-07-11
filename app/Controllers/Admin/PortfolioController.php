<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Portfolio;

/**
 * CRUD Portfolio (galeri hasil kerjaan). Gambar wajib.
 */
class PortfolioController extends Controller
{
    private Portfolio $model;
    private const SUB = 'portfolio';

    public function __construct()
    {
        $this->model = new Portfolio();
    }

    public function index(): void
    {
        $this->view('admin/portfolio/index', [
            'items' => $this->model->all('sort_order', 'ASC'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->view('admin/portfolio/form', [
            'item'   => null,
            'action' => url('/admin/portfolio'),
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $v = new Validator($_POST, [
            'title'       => 'required|min:2|max:150',
            'client'      => 'max:150',
            'category'    => 'max:80',
            'description' => 'max:2000',
        ]);
        if ($v->fails() || !Upload::hasFile($_FILES['image'] ?? [])) {
            $errors = $v->errors();
            if (!Upload::hasFile($_FILES['image'] ?? [])) {
                $errors['image'] = 'Gambar wajib diunggah.';
            }
            flash_errors($errors);
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/portfolio/create');
        }

        try {
            $filename = Upload::image($_FILES['image'], self::SUB);
        } catch (\RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/portfolio/create');
        }

        $this->model->create([
            'title'       => $this->input('title'),
            'client'      => $this->input('client'),
            'description' => $this->input('description'),
            'image'       => $filename,
            'category'    => $this->input('category'),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'  => (int) $this->input('sort_order', 0),
        ]);

        flash('success', 'Portfolio berhasil ditambahkan.');
        $this->redirect('/admin/portfolio');
    }

    public function edit(string $id): void
    {
        $item = $this->model->find($id);
        if (!$item) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/portfolio');
        }
        $this->view('admin/portfolio/form', [
            'item'   => $item,
            'action' => url('/admin/portfolio/' . $id),
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $item = $this->model->find($id);
        if (!$item) {
            flash('error', 'Data tidak ditemukan.');
            $this->redirect('/admin/portfolio');
        }

        $v = new Validator($_POST, [
            'title'       => 'required|min:2|max:150',
            'client'      => 'max:150',
            'category'    => 'max:80',
            'description' => 'max:2000',
        ]);
        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/portfolio/' . $id . '/edit');
        }

        $data = [
            'title'       => $this->input('title'),
            'client'      => $this->input('client'),
            'description' => $this->input('description'),
            'category'    => $this->input('category'),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'  => (int) $this->input('sort_order', 0),
        ];

        if (Upload::hasFile($_FILES['image'] ?? [])) {
            try {
                $data['image'] = Upload::image($_FILES['image'], self::SUB);
                Upload::delete(self::SUB, $item['image']);
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
                $this->redirect('/admin/portfolio/' . $id . '/edit');
            }
        }

        $this->model->update($id, $data);
        flash('success', 'Portfolio berhasil diperbarui.');
        $this->redirect('/admin/portfolio');
    }

    public function destroy(string $id): void
    {
        $item = $this->model->find($id);
        if ($item) {
            Upload::delete(self::SUB, $item['image']);
            $this->model->delete($id);
            flash('success', 'Portfolio berhasil dihapus.');
        }
        $this->redirect('/admin/portfolio');
    }
}
