<?php

namespace App\Models\Traits;

use Illuminate\Support\Carbon;

trait HasFlexibleDates
{
    /**
     * Helper reusable para transformar formatos latinos de fecha a MySQL.
     */
    public function parseFlexibleDate($value): ?string
    {
        if (empty($value)) return null;

        $normalized = str_replace('/', '-', $value);

        try {
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $normalized)) {
                return Carbon::createFromFormat('d-m-Y', $normalized)->format('Y-m-d');
            }
            return Carbon::parse($normalized)->format('Y-m-d');
        } catch (\Exception $e) {
            return $value;
        }
    }
}
