<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Facades\Test;

class MasivoTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkin:test-masivo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprueba pagadurias , embargos y descuentos por documento desde un archivo de excel previamente ubicado en storage';

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
        Test::masivo();        
    }
}
