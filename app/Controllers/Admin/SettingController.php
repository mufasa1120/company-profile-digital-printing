<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;

/**
 * Pengaturan company (key-value).
 * Satu halaman form berisi semua setting; update sekaligus.
 */
class SettingController extends Controller
{
    private Setting $model;

    /** Daftar field yang boleh diedit lewat panel. */
    private const FIELDS = [
        'company_name' => 'Nama Perusahaan',
        'tagline'      => 'Tagline',
        'address'      => 'Alamat',
        'phone'        => 'Telepon',
        'whatsapp'     => 'WhatsApp (628xxx)',
        'email'        => 'Email',
        'instagram'    => 'Link Instagram',
        'open_hours'   => 'Jam Buka',
        'maps_embed'   => 'Embed Google Maps (iframe/URL)',
    ];

    public function __construct()
    {
        $this->model = new Setting();
    }

    public function index(): void
    {
        $this->view('admin/settings/index', [
            'settings' => $this->model->map(),
            'fields'   => self::FIELDS,
        ], 'layouts/admin');
    }

    public function update(): void
    {
        foreach (array_keys(self::FIELDS) as $key) {
            $value = trim($_POST[$key] ?? '');
            $existing = $this->model->findBy('key_name', $key);

            if ($existing) {
                $this->model->update($existing['id'], ['value' => $value]);
            } else {
                $this->model->create(['key_name' => $key, 'value' => $value]);
            }
        }

        flash('success', 'Pengaturan berhasil disimpan.');
        $this->redirect('/admin/settings');
    }
}
