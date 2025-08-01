<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSemYopal extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessemyopal';
}
