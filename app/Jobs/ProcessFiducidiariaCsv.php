<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessFiducidiariaCsv implements ShouldQueue
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
        $this->progressKey = 'fiducidiaria_progress_' . now()->timestamp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Processing CSV file: ' . $this->filePath);

            $handle = fopen(storage_path('app/' . $this->filePath), 'r');
            if ($handle === false) {
                Log::error('Failed to open CSV file.');
                return;
            }

            $header = null;
            $rows = [];
            $batchSize = 1000; // Tamaño del lote
            $lineNumber = 0;

            while (($row = fgetcsv($handle, 0, ';')) !== false) { // Asegúrate de que el delimitador sea ';'
                $lineNumber++;

                if (!$header) {
                    $header = $row;
                    continue;
                }

                if (count($header) !== count($row)) {
                    continue;
                }

                $rowData = array_combine($header, $row);

                if ($this->isSerializable($rowData)) {
                    $rows[] = $rowData;
                }

                if (count($rows) >= $batchSize) {
                    $this->dispatchBatch($rows);
                    $rows = [];
                }
            }

            if (count($rows) > 0) {
                $this->dispatchBatch($rows);
            }

            fclose($handle);
        } catch (\Exception $e) {
            Log::error('Error processing CSV file: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a batch of rows to be processed.
     *
     * @param array $rows
     * @return void
     */
    private function dispatchBatch($rows)
    {
        try {
            ProcessFiducidiariaBatch::dispatch($rows, $this->progressKey)->onQueue('high');
        } catch (\Exception $e) {
            Log::error('Error dispatching batch: ' . $e->getMessage());
        }
    }

    /**
     * Check if the data is serializable
     *
     * @param array $data
     * @return bool
     */
    private function isSerializable($data)
    {
        try {
            json_encode($data);
            return json_last_error() === JSON_ERROR_NONE;
        } catch (\Exception $e) {
            Log::error('Serialization failed: ' . $e->getMessage());
            return false;
        }
    }
}
