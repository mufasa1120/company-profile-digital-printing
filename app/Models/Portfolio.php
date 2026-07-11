<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Portfolio (galeri hasil kerjaan).
 */
class Portfolio extends Model
{
    protected string $table = 'portfolio';
    protected array $fillable = ['title', 'client', 'description', 'image', 'category', 'is_active', 'sort_order'];

    public function active(): array
    {
        return $this->where(['is_active' => 1], 'sort_order', 'ASC');
    }
}
