<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'credit_requests';

    protected $fillable = [
        'doc','name','client_type','pagaduria_id','cuota','monto','tasa',
        'plazo','status','tipo_credito','user_id','tipo_pension','resolucion',
        'visado_id','pdf_path'
    ];
    

    public function carteras()
    {
        return $this->hasMany(CreditCartera::class, 'credit_request_id');
    }

    public function documents()
    {
        return $this->hasMany(CreditDocument::class);
    }

    public function visado()
    {
        return $this->belongsTo(Visado::class, 'visado_id');
    }
}
