<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Setting (info company, key-value).
 */
class Setting extends Model
{
    protected string $table = 'settings';
    protected array $fillable = ['key_name', 'value'];

    /** Ambil semua setting sebagai array asosiatif [key => value]. */
    public function map(): array
    {
        $out = [];
        foreach ($this->all() as $row) {
            $out[$row['key_name']] = $row['value'];
        }
        return $out;
    }

    /** Ambil satu nilai setting. */
    public function get(string $key, $default = null)
    {
        $row = $this->findBy('key_name', $key);
        return $row['value'] ?? $default;
    }
}
