<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * ============================================================
 *  DATABASE (PDO Singleton)
 *  Satu koneksi dipakai bersama seluruh aplikasi.
 *  Panggil: Database::conn()  -> dapat objek PDO.
 * ============================================================
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Ambil (atau buat sekali) koneksi PDO.
     */
    public static function conn(): PDO
    {
        if (self::$instance === null) {
            $cfg = require CONFIG_PATH . '/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['port'],
                $cfg['dbname'],
                $cfg['charset']
            );

            try {
                self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], $cfg['options']);
            } catch (PDOException $e) {
                if (APP_DEBUG) {
                    die('Koneksi DB gagal: ' . $e->getMessage());
                }
                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('Terjadi kesalahan server.');
            }
        }

        return self::$instance;
    }

    /**
     * Helper cepat menjalankan query berparameter.
     * Return: PDOStatement (bisa di-fetch lebih lanjut).
     */
    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
