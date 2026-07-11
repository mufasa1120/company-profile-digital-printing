<?php
namespace App\Core;

/**
 * ============================================================
 *  BASE CONTROLLER
 *  Menyediakan render view dengan layout, redirect, dan JSON.
 *
 *  view('front/home', ['ads' => $ads], 'layouts/front')
 *    -> render app/Views/front/home.php di dalam layout front.
 * ============================================================
 */
abstract class Controller
{
    /**
     * Render sebuah view (opsional dibungkus layout).
     *
     * @param string $view   path relatif dari Views tanpa .php, mis: 'front/home'
     * @param array  $data   variabel yang dilempar ke view
     * @param string|null $layout  path layout, mis: 'layouts/front'. null = tanpa layout.
     */
    protected function view(string $view, array $data = [], ?string $layout = 'layouts/front'): void
    {
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException("View tidak ditemukan: {$view}");
        }

        // ekstrak array jadi variabel lokal ($data['ads'] -> $ads)
        extract($data, EXTR_SKIP);

        // tangkap output view ke dalam $content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        // render layout; view punya akses ke $content
        $layoutFile = VIEWS_PATH . '/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            throw new \RuntimeException("Layout tidak ditemukan: {$layout}");
        }
        require $layoutFile;
    }

    /**
     * Kirim response JSON lalu berhenti.
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirect ke path internal lalu berhenti.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    /**
     * Ambil input request (POST/GET) yang sudah di-trim.
     */
    protected function input(string $key, $default = null)
    {
        $val = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($val) ? trim($val) : $val;
    }
}
