<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model User (akun admin).
 */
class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'role'];
}
