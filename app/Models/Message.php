<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model Message (pesan dari form kontak).
 */
class Message extends Model
{
    protected string $table = 'messages';
    protected array $fillable = ['name', 'email', 'phone', 'subject', 'body', 'is_read'];

    public function unreadCount(): int
    {
        return $this->count(['is_read' => 0]);
    }
}
