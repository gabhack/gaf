<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditDocument extends Model
{
    protected $table = 'credit_documents';
    protected $connection = 'pgsql';


    protected $fillable = [
        'credit_request_id',
        'file_path'
    ];

    public function creditRequest()
    {
        return $this->belongsTo(CreditRequest::class);
    }
}
