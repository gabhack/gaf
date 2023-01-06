<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuentossemsahagun extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossemsahagun';

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'fecdata', 'mesdata', 'anodata', 'pagaduria', 'noent', 'causal'];
}
