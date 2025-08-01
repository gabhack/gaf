<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatosHistoricos extends Model
{
    use SoftDeletes;

	protected $table = 'datoshistoricos';
}
