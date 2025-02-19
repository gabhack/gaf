<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'credit_requests';

    protected $fillable = [
        'doc',
        'name',
        'client_type',
        'pagaduria_id',  
        'cuota',
        'monto',
        'tasa',
        'plazo',
        'status' 
    ];

    /**
     * Relación con las carteras asociadas.
     */
    public function carteras()
    {
        return $this->hasMany(CreditCartera::class, 'credit_request_id');
    }

    public function documents()
{
    return $this->hasMany(CreditDocument::class);
}

}
