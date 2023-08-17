<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Facades\Test;

class IndividualTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkin:test-individual {doc}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprueba pagadurias , embargos y descuentos, recibe como parametro el documento ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $doc = $this->argument('doc');
        Test::individual($doc);
    }
}
