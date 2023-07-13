<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSemPasto extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessempasto';
}
