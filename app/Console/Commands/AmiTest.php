<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Facades\Test;

class AmiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ami:run-test {type} {ciudad}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validacion de datos en la base de datos mediantes un archivo de excel, aplica para pagadurias, embargos, descuentos y cupones';

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
        $type = $this->argument('type');
        $ciudad = $this->argument('ciudad');

        switch ($type) {
            case 'pagaduria':
                Test::testPagaduria($ciudad);
                break;

            case 'embargo':
                Test::testEmbargo($ciudad);
                break;

            case 'cupon':
                Test::testCupon($ciudad);
                break;

            case 'descuento':
                Test::testDescuento($ciudad);
                break;

            default:
                echo "El tipo ingresado no existe | valores permitidos son , pagaduria, embargo, cupon y descuento";
                break;
        }
    }
}
