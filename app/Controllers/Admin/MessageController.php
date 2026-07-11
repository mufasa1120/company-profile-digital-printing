<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Message;

/**
 * Kelola pesan masuk dari form kontak.
 * Nggak ada create/edit — pesan datang dari sisi publik.
 * Cuma: lihat daftar, baca detail (tandai dibaca), hapus.
 */
class MessageController extends Controller
{
    private Message $model;

    public function __construct()
    {
        $this->model = new Message();
    }

    public function index(): void
    {
        $this->view('admin/messages/index', [
            'messages' => $this->model->all('created_at', 'DESC'),
        ], 'layouts/admin');
    }

    public function show(string $id): void
    {
        $message = $this->model->find($id);
        if (!$message) {
            flash('error', 'Pesan tidak ditemukan.');
            $this->redirect('/admin/messages');
        }

        // tandai sudah dibaca
        if (!$message['is_read']) {
            $this->model->update($id, ['is_read' => 1]);
            $message['is_read'] = 1;
        }

        $this->view('admin/messages/show', [
            'message' => $message,
        ], 'layouts/admin');
    }

    public function destroy(string $id): void
    {
        if ($this->model->find($id)) {
            $this->model->delete($id);
            flash('success', 'Pesan berhasil dihapus.');
        }
        $this->redirect('/admin/messages');
    }
}
