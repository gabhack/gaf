<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class ProcessColpensionesExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $batchSize;
    protected $progressKey;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param int $batchSize
     * @param string $progressKey
     */
    public function __construct(string $filePath, int $batchSize = 1000, string $progressKey)
    {
        $this->filePath = $filePath;
        $this->batchSize = $batchSize;
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
            $csv = Reader::createFromPath(Storage::path($this->filePath), 'r');
            $csv->setHeaderOffset(0);

            $header = $csv->getHeader();
            $records = $csv->getRecords();

            $batch = [];
            $batchCount = 0;
            $rowCount = 0;
            $totalCount = 0;

            foreach ($records as $record) {
                $rowCount++;

                // Skip empty rows
                if (empty(array_filter($record))) {
                    continue;
                }

                $batch[] = $record;

                if (count($batch) == $this->batchSize) {
                    $batchCount++;
                    Bus::dispatch(new ProcessColpensionesBatch($batch, $this->progressKey));
                    $batch = [];
                }

                $totalCount++;
            }

            if (!empty($batch)) {
                Bus::dispatch(new ProcessColpensionesBatch($batch, $this->progressKey));
                $batchCount++;
            }

            Cache::put($this->progressKey . '_total', $batchCount);

        } catch (\Exception $e) {
            // Aquí puedes agregar un log de error si es necesario.
        } finally {
            // Clean up the file
            Storage::delete($this->filePath);
        }
    }
}
