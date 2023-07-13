<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSedCaldas extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessedcaldas';
}
