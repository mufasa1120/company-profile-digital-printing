<?php
namespace App\Core;

/**
 * ============================================================
 *  BASE MODEL
 *  Semua Model (Ad, Service, dll) mewarisi ini.
 *  Menyediakan CRUD dasar + query mentah.
 *
 *  Cara pakai di child:
 *      class Ad extends Model {
 *          protected string $table = 'ads';
 *          protected array $fillable = ['title', 'image', 'is_active'];
 *      }
 * ============================================================
 */
abstract class Model
{
    protected string $table;              // nama tabel
    protected string $primaryKey = 'id';  // primary key
    protected array $fillable = [];       // kolom yang boleh diisi massal

    protected function db(): \PDO
    {
        return Database::conn();
    }

    /**
     * Ambil semua baris. Bisa diurutkan.
     */
    public function all(string $orderBy = null, string $dir = 'DESC'): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY {$orderBy} {$dir}";
        }
        return Database::run($sql)->fetchAll();
    }

    /**
     * Cari satu baris berdasarkan primary key.
     */
    public function find($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        $row = Database::run($sql, [$id])->fetch();
        return $row ?: null;
    }

    /**
     * Cari satu baris berdasarkan kolom tertentu.
     */
    public function findBy(string $column, $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $row = Database::run($sql, [$value])->fetch();
        return $row ?: null;
    }

    /**
     * Ambil banyak baris dengan kondisi WHERE sederhana (AND semua).
     * Contoh: where(['is_active' => 1])
     */
    public function where(array $conditions, string $orderBy = null, string $dir = 'DESC'): array
    {
        $clauses = [];
        $params  = [];
        foreach ($conditions as $col => $val) {
            $clauses[] = "{$col} = ?";
            $params[]  = $val;
        }
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $clauses);
        if ($orderBy) {
            $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY {$orderBy} {$dir}";
        }
        return Database::run($sql, $params)->fetchAll();
    }

    /**
     * Insert baris baru. Return id yang baru dibuat.
     * Otomatis hanya ambil kolom yang ada di $fillable.
     */
    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        $cols = array_keys($data);

        $placeholders = implode(', ', array_fill(0, count($cols), '?'));
        $colList      = implode(', ', $cols);

        $sql = "INSERT INTO {$this->table} ({$colList}) VALUES ({$placeholders})";
        Database::run($sql, array_values($data));

        return (int) $this->db()->lastInsertId();
    }

    /**
     * Update baris berdasarkan primary key. Return jumlah baris terpengaruh.
     */
    public function update($id, array $data): int
    {
        $data = $this->filterFillable($data);
        $sets = [];
        foreach (array_keys($data) as $col) {
            $sets[] = "{$col} = ?";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets)
             . " WHERE {$this->primaryKey} = ?";

        $params   = array_values($data);
        $params[] = $id;

        return Database::run($sql, $params)->rowCount();
    }

    /**
     * Hapus baris berdasarkan primary key.
     */
    public function delete($id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return Database::run($sql, [$id])->rowCount();
    }

    /**
     * Hitung total baris (opsional dengan kondisi).
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) AS c FROM {$this->table}";
        $params = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "{$col} = ?";
                $params[]  = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        return (int) Database::run($sql, $params)->fetch()['c'];
    }

    /**
     * Buang key yang tidak terdaftar di $fillable (proteksi mass-assignment).
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
