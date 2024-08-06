<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount)
    {
        return \App\Helpers\FormatHelper::formatRupiah($amount);
    }
}

if (!function_exists('formatTanggal')) {
    function formatTanggal($date)
    {
        return \App\Helpers\FormatHelper::formatTanggal($date);
    }
}
