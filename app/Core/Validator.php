<?php
namespace App\Core;

/**
 * ============================================================
 *  VALIDATOR
 *  Validasi input dengan aturan string ala Laravel-lite.
 *
 *  Contoh:
 *      $v = new Validator($_POST, [
 *          'title'   => 'required|min:3|max:150',
 *          'email'   => 'required|email',
 *          'phone'   => 'required|numeric',
 *      ]);
 *      if ($v->fails()) {
 *          // $v->errors() -> array pesan error per field
 *      }
 * ============================================================
 */
class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
        $this->run();
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleset) {
            $value = $this->data[$field] ?? null;
            $value = is_string($value) ? trim($value) : $value;

            foreach (explode('|', $ruleset) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $this->applyRule($field, $value, $name, $param);
            }
        }
    }

    private function applyRule(string $field, $value, string $rule, ?string $param): void
    {
        // kalau sudah error di field ini, jangan tumpuk lagi
        if (isset($this->errors[$field])) {
            return;
        }

        $label = ucfirst(str_replace('_', ' ', $field));

        switch ($rule) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->addError($field, "{$label} wajib diisi.");
                }
                break;

            case 'email':
                if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$label} harus berupa email valid.");
                }
                break;

            case 'numeric':
                if ($value !== '' && !is_numeric($value)) {
                    $this->addError($field, "{$label} harus berupa angka.");
                }
                break;

            case 'min':
                if ($value !== '' && str_length($value) < (int) $param) {
                    $this->addError($field, "{$label} minimal {$param} karakter.");
                }
                break;

            case 'max':
                if ($value !== '' && str_length($value) > (int) $param) {
                    $this->addError($field, "{$label} maksimal {$param} karakter.");
                }
                break;

            case 'in':
                $allowed = explode(',', (string) $param);
                if ($value !== '' && !in_array((string) $value, $allowed, true)) {
                    $this->addError($field, "{$label} tidak valid.");
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
