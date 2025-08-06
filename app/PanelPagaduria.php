<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PanelPagaduria extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'panel_pagaduria';

    public $timestamps = false;

    protected $fillable = ['id', 'nombre'];
}
