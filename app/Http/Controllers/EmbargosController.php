<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;
use App\EmbargosSedCauca;
use App\EmbargosSedChoco;
use App\EmbargosSedCordoba;
use App\EmbargosSedAtlantico;
use App\EmbargosSedValle;
use App\EmbargosSedCaldas;
use App\EmbargosSedBolivar;
use App\EmbargosSemBarranquilla;
use App\EmbargosSemCali;
use App\EmbargosSemPopayan;
use App\EmbargosSemMonteria;
use App\EmbargosSemQuibdo;
use App\EmbargosGen;

class EmbargosController extends Controller
{
    use ParsesPgTzDates;

    private static $modelsMap   = [];
    private static $columnMap   = [];
    private static $pagMap      = [];
    private static $pagNoSpaces = [];

    public function __construct()
    {
        if (empty(self::$modelsMap)) {
            self::$modelsMap = [
                'sedcauca'        => EmbargosSedCauca::class,
                'sedchoco'        => EmbargosSedChoco::class,
                'sedcordoba'      => EmbargosSedCordoba::class,
                'semcali'         => EmbargosSemCali::class,
                'sedvalle'        => EmbargosSedValle::class,
                'sedcaldas'       => EmbargosSedCaldas::class,
                'sedatlantico'    => EmbargosSedAtlantico::class,
                'sedbolivar'      => EmbargosSedBolivar::class,
                'sembarranquilla' => EmbargosSemBarranquilla::class,
                'sempopayan'      => EmbargosSemPopayan::class,
                'semmonteria'     => EmbargosSemMonteria::class,
                'semquibdo'       => EmbargosSemQuibdo::class,
            ];
            foreach (self::$modelsMap as $cls) {
                self::$columnMap[$cls] = in_array($cls, [EmbargosSemQuibdo::class, EmbargosSedBolivar::class]) ? 'idemp' : 'doc';
            }
        }
        if (empty(self::$pagMap)) {
            self::$pagMap = PagaduriaHelper::map();
            self::$pagNoSpaces = collect(self::$pagMap)->mapWithKeys(function ($id, $name) {
                return [str_replace(' ', '', mb_strtolower($name)) => $id];
            })->all();
        }
    }

    public function index(Request $r)
    {
        Log::info('EmbargosController@index start', $r->all());

        $doc        = $r->input('doc');
        $rawType    = $r->input('pagaduria');
        $rawLabel   = $r->input('pagaduriaLabel');
        $monthParam = $r->input('month');
        $yearParam  = $r->input('year');

        $typeNorm  = trim(mb_strtolower($rawType));
        $labelNorm = trim(mb_strtolower($rawLabel));

        $idPag = $this->pagId($typeNorm) ?: $this->pagId($labelNorm);
        Log::info('Resolved pagaduria id', ['id' => $idPag]);

        $results = [];

        $modelKey = str_replace(['embargos', 'descuentos', 'coupons'], '', $typeNorm);
        if (isset(self::$modelsMap[$modelKey])) {
            $model   = self::$modelsMap[$modelKey];
            $column  = self::$columnMap[$model];
            Log::info('Querying specific model', ['model' => $model, 'column' => $column]);
            $part    = $model::on('pgsql')->where($column, 'LIKE', "%{$doc}%")->get()->map(function ($r) {
                return $r->getAttributes();
            })->all();
            Log::info('Specific model rows', ['count' => count($part)]);
            $results = array_merge($results, $part);
        } else {
            Log::info('No specific model matched', ['key' => $modelKey]);
        }

        $gen = EmbargosGen::on('pgsql')->where('doc', 'LIKE', "%{$doc}%");
        if ($idPag) {
            $gen->where('idpagaduria', $idPag);
        } else {
            $gen->where(function ($q) use ($rawType, $rawLabel) {
                $q->where('pagaduria', $rawType)->orWhere('pagaduria', $rawLabel);
            });
        }
        if (is_numeric($monthParam) && is_numeric($yearParam)) {
            $m  = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
            $s  = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->startOfMonth()->toDateString();
            $e  = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->endOfMonth()->toDateString();
            $gen->whereRaw("to_date(nomina,'YYYY-MM-DD') BETWEEN ? AND ?", [$s, $e]);
        }
        Log::info('EmbargosGen SQL', ['sql' => $gen->toSql(), 'bindings' => $gen->getBindings()]);
        $genRows = $gen->get()->map(function ($r) {
            return $r->getAttributes();
        })->all();
        Log::info('EmbargosGen rows', ['count' => count($genRows)]);

        $results = array_merge($results, $genRows);
        $results = $this->normNomina($results);

        Log::info('EmbargosController@index end', ['total' => count($results)]);
        return response()->json($results, 200);
    }

    private function pagId($str)
    {
        if (!$str) {
            return null;
        }
        $clean = preg_replace('/^(coupons|embargos|descuentos)/', '', $str);
        if (isset(self::$pagMap[$clean])) {
            return self::$pagMap[$clean];
        }
        $noSpace = str_replace(' ', '', $clean);
        return isset(self::$pagNoSpaces[$noSpace]) ? self::$pagNoSpaces[$noSpace] : null;
    }

    private function normNomina(array $rows)
    {
        foreach ($rows as &$row) {
            if (isset($row['nomina']) && $row['nomina']) {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row['nomina'])) {
                    $row['nomina'] = Carbon::createFromFormat('d/m/Y', $row['nomina'])->toDateString();
                } else {
                    $row['nomina'] = Carbon::parse($row['nomina'])->toDateString();
                }
            }
        }
        return $rows;
    }
}
