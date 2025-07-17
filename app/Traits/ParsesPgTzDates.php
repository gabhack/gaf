<?php

namespace App\Traits;

use DateTimeInterface;
use Carbon\Carbon;

trait ParsesPgTzDates
{
    public function asDateTime($value)
    {
        if (is_null($value) || $value instanceof DateTimeInterface) {
            return parent::asDateTime($value);
        }

        if ($value === '' || trim($value) === '') {
            return null;
        }

        if (is_string($value)) {
            if (preg_match('/^(\d{1,2})-([a-z]{3})-(\d{2})$/i', $value, $m)) {
                $months = [
                    'ene'=>'01','feb'=>'02','mar'=>'03','abr'=>'04',
                    'may'=>'05','jun'=>'06','jul'=>'07','ago'=>'08',
                    'sep'=>'09','oct'=>'10','nov'=>'11','dic'=>'12',
                ];
                $day      = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                $abbr     = strtolower($m[2]);
                $yearFull = '20' . $m[3];
                if (isset($months[$abbr])) {
                    $value = sprintf('%s-%s-%s', $yearFull, $months[$abbr], $day);
                    return parent::asDateTime($value);
                }
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+\+\d{2}$/', $value)) {
                return Carbon::parse($value);
            }
        }

        return parent::asDateTime($value);
    }
}
