<?php
namespace App\Jobs;

use App\Colpensiones;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ColpensionesImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessColpensionesExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;
    public $progressKey;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->progressKey = 'colpensiones_progress_' . now()->timestamp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $import = new ColpensionesImport();
        $rows = Excel::toArray($import, storage_path('app/' . $this->filePath))[0];
        
        $batchSize = 10; // Tamaño del lote
        $totalRows = count($rows);

        Cache::put($this->progressKey, 0);
        Cache::put($this->progressKey . '_total', ceil($totalRows / $batchSize));

        for ($i = 0; $i < $totalRows; $i += $batchSize) {
            $batch = array_slice($rows, $i, $batchSize);
            ProcessColpensionesBatch::dispatch($batch, $this->progressKey);
        }
    }
}
