<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemographicConsultLog extends Model
{
    public $timestamps = false;

    protected $connection = 'pgsql';
    protected $fillable = [
        'user_id', 'consulta_data'
    ];

    protected $casts = [
        'consulta_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

