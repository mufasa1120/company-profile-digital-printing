<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Ad (iklan/banner yang tampil di hero landing page).
 */
class Ad extends Model
{
    protected string $table = 'ads';
    protected array $fillable = ['title', 'subtitle', 'image', 'link_url', 'is_active', 'sort_order'];

    /** Hanya iklan aktif, terurut untuk ditampilkan di front. */
    public function active(): array
    {
        return $this->where(['is_active' => 1], 'sort_order', 'ASC');
    }
}
