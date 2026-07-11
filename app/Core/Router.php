<?php
namespace App\Core;

/**
 * ============================================================
 *  ROUTER
 *  Mendaftarkan rute lalu mencocokkan URL yang masuk.
 *  Mendukung parameter dinamis {id} dan middleware (mis. 'auth').
 *
 *  Contoh daftar rute (di routes/web.php):
 *      $router->get('/',                 [HomeController::class, 'index']);
 *      $router->get('/portfolio/{id}',   [PortfolioController::class, 'show']);
 *      $router->post('/kontak',          [ContactController::class, 'store']);
 *
 *  Rute admin dengan middleware:
 *      $router->get('/admin/dashboard', [DashboardController::class, 'index'], ['auth']);
 * ============================================================
 */
class Router
{
    /** @var array<int, array> daftar rute terdaftar */
    private array $routes = [];

    /** @var array<string, callable> daftar middleware bernama */
    private array $middlewares = [];

    // --- Pendaftaran rute per method ---
    public function get(string $path, array $handler, array $mw = []): void
    {
        $this->add('GET', $path, $handler, $mw);
    }

    public function post(string $path, array $handler, array $mw = []): void
    {
        $this->add('POST', $path, $handler, $mw);
    }

    private function add(string $method, string $path, array $handler, array $mw): void
    {
        // ubah {param} jadi regex bernama, tambahkan anchor
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#';

        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'mw'      => $mw,
        ];
    }

    /**
     * Daftarkan middleware bernama.
     * Contoh: $router->middleware('auth', fn() => Auth::guard());
     */
    public function middleware(string $name, callable $fn): void
    {
        $this->middlewares[$name] = $fn;
    }

    /**
     * Cocokkan request saat ini lalu jalankan handler.
     */
    public function dispatch(string $uri, string $method): void
    {
        // buang query string & normalisasi
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        if (BASE_URI !== '' && str_starts_with($uri, BASE_URI)) {
            $uri = substr($uri, strlen(BASE_URI));
        }
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }

            // jalankan semua middleware; kalau ada yang stop (false), berhenti
            foreach ($route['mw'] as $name) {
                if (isset($this->middlewares[$name])) {
                    $result = ($this->middlewares[$name])();
                    if ($result === false) {
                        return; // middleware sudah handle redirect/response
                    }
                }
            }

            // ambil hanya parameter bernama (bukan index numerik)
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            [$class, $action] = $route['handler'];
            $controller = new $class();
            call_user_func_array([$controller, $action], array_values($params));
            return;
        }

        // tidak ada rute cocok
        $this->abort404();
    }

    private function abort404(): void
    {
        http_response_code(404);
        $view = VIEWS_PATH . '/errors/404.php';
        if (is_file($view)) {
            require $view;
        } else {
            echo '<h1>404 - Halaman tidak ditemukan</h1>';
        }
    }
}
