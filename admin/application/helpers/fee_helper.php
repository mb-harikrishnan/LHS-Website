<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 1234567.5 -> "12,34,567.50" (Indian grouping) */
if (!function_exists('fee_money')) {
    function fee_money($n, $decimals = 2)
    {
        $n    = round((float)$n, $decimals);
        $neg  = $n < 0;
        $n    = abs($n);
        $int  = (string)floor($n);
        $frac = $decimals > 0 ? '.' . str_pad((string)(int)round(($n - floor($n)) * pow(10, $decimals)), $decimals, '0', STR_PAD_LEFT) : '';
        if (strlen($int) > 3) {
            $last = substr($int, -3);
            $rest = substr($int, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $int  = $rest . ',' . $last;
        }
        return ($neg ? '-' : '') . $int . $frac;
    }
}

/* 1250.50 -> "Rupees One Thousand Two Hundred Fifty and Fifty Paise Only" */
if (!function_exists('fee_words')) {
    function fee_words($amount)
    {
        $amount = round((float)$amount, 2);
        $rupees = (int)floor($amount);
        $paise  = (int)round(($amount - $rupees) * 100);

        $w = $rupees > 0 ? _fee_words_int($rupees) : 'Zero';
        $s = 'Rupees ' . $w;
        if ($paise > 0) {
            $s .= ' and ' . _fee_words_int($paise) . ' Paise';
        }
        return $s . ' Only';
    }

    function _fee_words_int($n)
    {
        $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                      'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
        $tens = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');

        $two = function ($x) use ($ones, $tens) {
            if ($x < 20) return $ones[$x];
            return trim($tens[(int)($x / 10)] . ' ' . $ones[$x % 10]);
        };
        $three = function ($x) use ($ones, $two) {
            $h = (int)($x / 100);
            $r = $x % 100;
            return trim(($h ? $ones[$h] . ' Hundred' : '') . ($r ? ' ' . $two($r) : ''));
        };

        $parts = array();
        foreach (array(10000000 => 'Crore', 100000 => 'Lakh', 1000 => 'Thousand') as $div => $label) {
            if ($n >= $div) {
                $q = (int)($n / $div);
                $parts[] = ($q >= 100 ? _fee_words_int($q) : $two($q)) . ' ' . $label;
                $n %= $div;
            }
        }
        if ($n > 0) $parts[] = $three($n);
        return trim(implode(' ', $parts));
    }
}
