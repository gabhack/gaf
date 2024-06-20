<?php

namespace App\Jobs;

use App\Fiducidiaria;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        try {
            $rowsToInsert = [];
            $now = Carbon::now();

            foreach ($this->batch as $row) {
                if (empty($row['DOCUMENTO'])) {
                    continue;
                }

                $fechaNacimiento = $this->transformDate($row['FECHA_NACIMIENTO_DOCENTE']);
                $valorMesada = $this->sanitizeNumericValue($row['VALOR_MESADA']);
                $valorBruto = $this->sanitizeNumericValue($row['VALORBRUTO']);
                $valorDescuentos = $this->sanitizeNumericValue($row['VALORDESCUENTOS']);
                $pagoNet = $this->sanitizeNumericValue($row['PAGO_NET']);

                $rowsToInsert[] = [
                    'DOCUMENTO' => $row['DOCUMENTO'],
                    'NOMBRES' => $row['NOMBRES'],
                    'APELLIDOS' => $row['APELLIDOS'],
                    'SEXO' => $row['SEXO'],
                    'ESTADO_CIVIL' => $row['ESTADO_CIVIL'],
                    'FECHA_NACIMIENTO_DOCENTE' => $fechaNacimiento,
                    'EDAD_ACTUAL' => $row['EDAD_ACTUAL'],
                    'ESTADO_PENSIONADO' => $row['ESTADO_PENSIONADO'],
                    'NOM_DEPTO' => $row['NOM_DEPTO'],
                    'VALOR_MESADA' => $valorMesada,
                    'VALORBRUTO' => $valorBruto,
                    'VALORDESCUENTOS' => $valorDescuentos,
                    'PAGO_NET' => $pagoNet,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Inserción masiva utilizando DB::table
            if (!empty($rowsToInsert)) {
                DB::table('fiducidiaria')->insert($rowsToInsert);
            }

            Cache::increment($this->progressKey);
            if (Cache::get($this->progressKey) == Cache::get($this->progressKey . '_total')) {
                Cache::forget($this->progressKey);
                Cache::forget($this->progressKey . '_total');
            }
        } catch (\Exception $e) {
            // Aquí puedes agregar un log de error si es necesario.
        }
    }

    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Carbon::createFromDate(1900, 1, 1)->addDays($value - 2)->format('Y-m-d');
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function sanitizeNumericValue($value)
    {
        try {
            $cleanValue = str_replace([',', ' '], ['', ''], $value);
            if (is_numeric($cleanValue)) {
                return floatval($cleanValue);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
