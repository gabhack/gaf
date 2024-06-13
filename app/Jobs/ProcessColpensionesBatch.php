<?php
namespace App\Jobs;

use App\Colpensiones;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ProcessColpensionesBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;
    protected $progressKey;

    /**
     * Create a new job instance.
     *
     * @param array $batch
     * @param string $progressKey
     */
    public function __construct(array $batch, string $progressKey)
    {
        $this->batch = $batch;
        $this->progressKey = $progressKey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->batch as $row) {
            if (empty($row['documento'])) {
                continue; // Ignorar filas con 'documento' nulo
            }

            $nacimiento = $this->transformDate($row['nacimiento']);
        
            Colpensiones::updateOrCreate(
                [
                    'documento' => $row['documento'],
                ],
                [
                    'primer_apellido' => $row['primer_apellido'],
                    'segundo_apellido' => $row['segundo_apellido'],
                    'primer_nombre' => $row['primer_nombre'],
                    'segundo_nombre' => $row['segundo_nombre'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'correo_electronico' => $row['correo_electronico'],
                    'nacimiento' => $nacimiento,
                    'sexo' => $row['sexo'],
                    'departamento' => $row['departamento'],
                    'municipio' => $row['municipio'],
                    'vpensiones' => $row['vpensiones'],
                    'vsalud' => $row['vsalud'],
                    'vembargo' => $row['vembargo'],
                    'vdescuentos' => $row['vdescuentos'],
                    'capacidad' => $row['capacidad'],
                ]
            );
        }
        Cache::increment($this->progressKey);
        if (Cache::get($this->progressKey) == Cache::get($this->progressKey . '_total')) {
            Cache::forget($this->progressKey);
            Cache::forget($this->progressKey . '_total');
        }
    }

    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($value - 2)->format('Y-m-d');
        }
        return null;
    }
}
