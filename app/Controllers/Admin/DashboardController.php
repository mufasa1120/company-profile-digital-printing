<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Ad;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Message;

/**
 * Dashboard admin: ringkasan jumlah data.
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $this->view('admin/dashboard', [
            'stats' => [
                'ads'       => (new Ad())->count(),
                'services'  => (new Service())->count(),
                'portfolio' => (new Portfolio())->count(),
                'messages'  => (new Message())->count(),
                'unread'    => (new Message())->unreadCount(),
            ],
        ], 'layouts/admin');
    }
}
