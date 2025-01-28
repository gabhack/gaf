<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCartera extends Model
{

    protected $connection = 'pgsql';

    protected $table = 'credit_carteras';

    
    protected $fillable = [
        'credit_request_id',
        'valor_cuota',
        'saldo'
    ];

    public function creditRequest()
    {
        return $this->belongsTo(CreditRequest::class, 'credit_request_id');
    }
}
