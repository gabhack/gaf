<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'timestamp', 'company', 'user', 'documentId', 'fullName', 
        'documentType', 'status', 'pdfUrl'
    ];

    public $timestamps = false;
}
