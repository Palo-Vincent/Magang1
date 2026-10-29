<?php

namespace App\Support;

use Carbon\Carbon;

class Format
{
    public static function date($value, string $format = 'j M Y'): string
    {
        if (blank($value)) {
            return '-';
        }

        return Carbon::parse($value)->locale('id')->translatedFormat($format);
    }

    public static function number($value): string
    {
        return number_format((float) $value, 0, ',', '.');
    }

    public static function badge(?string $value): string
    {
        return match ($value) {
            'baik', 'dikembalikan' => 'success',
            'rusak ringan', 'menunggu' => 'warning',
            'dipinjam' => 'info',
            'rusak berat', 'terlambat', 'ditolak' => 'danger',
            default => 'gray',
        };
    }
}