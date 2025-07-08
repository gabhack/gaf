<?php
namespace App\Traits;

use Illuminate\Support\Carbon;

trait ParsesPgTzDates
{
    public function asDateTime($value)
    {
        if (is_null($value) || $value instanceof \DateTimeInterface) {
            return $value;
        }
        return Carbon::parse($value);
    }
}
