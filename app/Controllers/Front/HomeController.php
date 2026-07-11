<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\Ad;
use App\Models\Service;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Setting;

/**
 * Controller halaman publik.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('front/home', [
            'ads'       => (new Ad())->active(),
            'services'  => (new Service())->active(),
            'products'  => (new Product())->active(),
            'portfolio' => (new Portfolio())->active(),
            'setting'   => (new Setting())->map(),
        ], 'layouts/front');
    }

    public function services(): void
    {
        $this->view('front/services', [
            'services' => (new Service())->active(),
            'setting'  => (new Setting())->map(),
        ], 'layouts/front');
    }

    public function portfolio(): void
    {
        $this->view('front/portfolio', [
            'portfolio' => (new Portfolio())->active(),
            'setting'   => (new Setting())->map(),
        ], 'layouts/front');
    }

    public function contact(): void
    {
        $this->view('front/contact', [
            'setting' => (new Setting())->map(),
        ], 'layouts/front');
    }
}