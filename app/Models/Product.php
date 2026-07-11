<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Product (produk cetak per item).
 */
class Product extends Model
{
    protected string $table = 'products';
    protected array $fillable = ['service_id', 'name', 'description', 'image', 'price', 'is_active'];

    public function active(): array
    {
        return $this->where(['is_active' => 1]);
    }
}
