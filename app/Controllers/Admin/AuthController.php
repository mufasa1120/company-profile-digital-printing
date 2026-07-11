<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;

/**
 * Login / logout admin.
 */
class AuthController extends Controller
{
    public function showLogin(): void
    {
        // sudah login? langsung ke dashboard
        if (Auth::check()) {
            $this->redirect('/admin/dashboard');
        }
        $this->view('admin/login', [], null); // login pakai layout sendiri
    }

    public function login(): void
    {
        $email    = $this->input('email');
        $password = $this->input('password');

        if (!Auth::attempt($email, $password)) {
            flash('error', 'Email atau password salah.');
            $_SESSION['_old'] = ['email' => $email];
            $this->redirect('/admin/login');
        }

        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/admin/login');
    }
}
