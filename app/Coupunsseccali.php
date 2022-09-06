<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupunsseccali extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'coupunsseccali';
    protected $fillable = [
        'id',
        'doc',
        'code',
        'concept',
        'ingresos',
        'egresos',
        'names',
        'period',
        'pagaduria'
    ];
}
