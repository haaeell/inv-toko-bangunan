<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format number to Rupiah currency.
     *
     * @param float $amount
     * @return string
     */
    public static function formatRupiah($amount)
    {
        return "Rp. " . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date to Indonesian date format.
     *
     * @param string $date
     * @return string
     */
    public static function formatTanggal($date)
    {
        return \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y');
    }
}
