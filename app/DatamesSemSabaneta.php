<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSemSabaneta extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessemsabaneta';
}
