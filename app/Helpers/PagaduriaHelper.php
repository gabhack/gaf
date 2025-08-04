<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PagaduriaHelper
{
    public static function map(): array
    {
        return Cache::remember('pagaduria_map', 3600, function () {
            return DB::connection('pgsql')
                ->table('panel_pagaduria')
                ->select('id', 'nombre')
                ->get()
                ->mapWithKeys(function ($row) {
                    return [mb_strtolower($row->nombre) => (int) $row->id];
                })
                ->toArray();
        });
    }

    public static function names(): array
    {
        return array_values(array_map('mb_strtoupper', array_keys(self::map())));
    }
}
