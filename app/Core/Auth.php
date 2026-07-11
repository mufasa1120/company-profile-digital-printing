<?php
namespace App\Core;

use App\Models\User;

/**
 * ============================================================
 *  AUTH
 *  Login/logout admin + penjaga (guard) halaman admin.
 *  Password disimpan sebagai hash (password_hash / bcrypt).
 * ============================================================
 */
class Auth
{
    /**
     * Coba login. Return true kalau kredensial cocok.
     */
    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findBy('email', $email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        // regenerasi session id -> cegah session fixation
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'] ?? 'admin',
        ];

        return true;
    }

    /**
     * Apakah ada user yang sedang login?
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Ambil data user yang sedang login (atau null).
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Ambil satu field user yang login.
     */
    public static function id()
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Logout: hapus data user dari session.
     */
    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    /**
     * Penjaga halaman admin. Dipakai sebagai middleware 'auth'.
     * Kalau belum login -> redirect ke /admin/login dan hentikan (return false).
     */
    public static function guard(): bool
    {
        if (!self::check()) {
            header('Location: ' . url('/admin/login'));
            return false; // beri tahu router untuk berhenti
        }
        return true;
    }
}
