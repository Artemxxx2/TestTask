<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxUploadSizeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): void  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxUpload = min(
            $this->phpSizeToBytes(ini_get('upload_max_filesize')),
            $this->phpSizeToBytes(ini_get('post_max_size'))
        );

        if ($value->getSize() > $maxUpload) {
            $fail("File size '{$attribute}' exceed the maximum upload file size of the server: ({$this->formatBytes($maxUpload)})");
        }
    }

    private function phpSizeToBytes(string $size): int
    {
        $unit = strtoupper(substr($size, -1));
        $bytes = (int) $size;

        return match ($unit) {
            'G' => $bytes * 1024 ** 3,
            'M' => $bytes * 1024 ** 2,
            'K' => $bytes * 1024,
            default => $bytes,
        };
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 ** 3) return round($bytes / 1024 ** 3, 2) . ' GB';
        if ($bytes >= 1024 ** 2) return round($bytes / 1024 ** 2, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
