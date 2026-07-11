<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Message;

/**
 * Terima & simpan pesan dari form kontak.
 */
class ContactController extends Controller
{
    public function store(): void
    {
        $v = new Validator($_POST, [
            'name'  => 'required|min:2|max:100',
            'email' => 'email|max:150',
            'phone' => 'max:30',
            'body'  => 'required|min:5',
        ]);

        if ($v->fails()) {
            flash_errors($v->errors());
            $_SESSION['_old'] = $_POST;
            $this->redirect('/kontak');
        }

        (new Message())->create([
            'name'    => $this->input('name'),
            'email'   => $this->input('email'),
            'phone'   => $this->input('phone'),
            'subject' => $this->input('subject'),
            'body'    => $this->input('body'),
        ]);

        flash('success', 'Pesan lu udah masuk. Kami segera hubungi balik ya!');
        $this->redirect('/kontak');
    }
}
