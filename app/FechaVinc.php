<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechaVinc extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'fechavinc';
    protected $fillable = [
        'doc',
        'vinc',
        'tp',
    ];
}
