<?php

namespace App\Jobs;

use App\Imports\FiducidiariaImport;
use App\Models\UploadProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessFiducidiariaExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $progressId;

    public function __construct($path, $progressId)
    {
        $this->path = $path;
        $this->progressId = $progressId;
    }

    public function handle()
    {
        $progress = UploadProgress::find($this->progressId);

        if (!$progress) {
            return;
        }

        $progress->status = 'processing';
        $progress->save();

        // Get total rows
        $totalRows = Excel::toCollection(null, $this->path)->first()->count();
        $progress->total_rows = $totalRows;
        $progress->save();

        // Import data
        Excel::import(new FiducidiariaImport($progress), $this->path);

        $progress->status = 'completed';
        $progress->save();
    }
}
