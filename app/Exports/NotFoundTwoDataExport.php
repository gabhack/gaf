<?php

namespace App\Exports;

use App\Models\Call;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class NotFoundTwoDataExport implements FromView
{
    use Exportable;
    private $clients;

    public function __construct($clients)
    {
        $this->clients = $clients;
    }

    public function view(): View
    {
        return view('test.not-found-two-data', [
            'clients' => $this->clients
        ]);
    }
}
