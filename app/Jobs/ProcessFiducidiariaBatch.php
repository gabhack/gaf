<?php

namespace App\Jobs;

use App\Fiducidiaria;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ProcessFiducidiariaBatch implements ShouldQueue
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
            if (empty($row['DOCUMENTO'])) {
                continue; // Ignorar filas con 'DOCUMENTO' nulo
            }

            $nacimiento = $this->transformDate($row['FECHA_NACIMIENTO_DOCENTE']);

            Fiducidiaria::updateOrCreate(
                [
                    'DOCUMENTO' => $row['DOCUMENTO'],
                ],
                [
                    'NOMBRES' => $row['NOMBRES'],
                    'APELLIDOS' => $row['APELLIDOS'],
                    'SEXO' => $row['SEXO'],
                    'ESTADO_CIVIL' => $row['ESTADO_CIVIL'],
                    'FECHA_NACIMIENTO_DOCENTE' => $nacimiento,
                    'EDAD_ACTUAL' => $row['EDAD_ACTUAL'],
                    'ESTADO_PENSIONADO' => $row['ESTADO_PENSIONADO'],
                    'NOM_DEPTO' => $row['NOM_DEPTO'],
                    'VALOR_MESADA' => $row['VALOR_MESADA'],
                    'VALORBRUTO' => $row['VALORBRUTO'],
                    'VALORDESCUENTOS' => $row['VALORDESCUENTOS'],
                    'PAGO_NET' => $row['PAGO_NET'],
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
