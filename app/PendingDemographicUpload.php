<?


namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingDemographicUpload extends Model
{
    protected $connection = 'pgsql';
    protected $table      = 'pending_demographic_uploads';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id','original_name','stored_path',
        'mes','anio','cedulas',      // ← nueva
        'status','approved_by','approved_at',
        'analyzed_by','analyzed_at',
    ];

    protected $casts = [
        'cedulas'     => 'array',    // ← para que devuelva array php
        'approved_at' => 'datetime',
        'analyzed_at' => 'datetime',
        'created_at'  => 'string',
        'updated_at'  => 'string',
    ];
}

