<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSemSoledad extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessemsoledad';
}
