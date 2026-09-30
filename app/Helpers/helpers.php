<?php

if (! function_exists('moneyFormat')) {
    /**
     * Format angka ke Rupiah, contoh: 1500000 -> "Rp. 1.500.000"
     *
     * @param  mixed  $amount
     */
    function moneyFormat($amount): string
    {
        return 'Rp. '.number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('escapeLike')) {
    /**
     * Escape wildcard LIKE supaya input pencarian tidak bisa memakai % atau _.
     *
     * @param  mixed  $term
     */
    function escapeLike($term): string
    {
        return addcslashes((string) $term, '%_\\');
    }
}
