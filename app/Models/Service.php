<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Service (layanan cetak).
 */
class Service extends Model
{
    protected string $table = 'services';
    protected array $fillable = ['name', 'slug', 'description', 'icon', 'price_from', 'is_active', 'sort_order'];

    public function active(): array
    {
        return $this->where(['is_active' => 1], 'sort_order', 'ASC');
    }
}
