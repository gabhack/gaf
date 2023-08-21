<?php

namespace App\Facades;

use Carbon\Carbon;

use App\DatamesSedValle;
use App\DatamesSemCali;
use App\DatamesSedAtlantico;
use App\DatamesSemBarranquilla;
use App\DatamesSedBolivar;
use App\DatamesSedChoco;
use App\DatamesFidu;
use App\DatamesFopep;
use App\DatamesSedMagdalena;
use App\DatamesSedNarino;
use App\DatamesSemPopayan;
use App\DatamesSemQuibdo;
use App\DatamesSemSahagun;

use App\EmbargosSedCauca;
use App\EmbargosSedChoco;
use App\EmbargosSedValle;
use App\EmbargosSemCali;
use App\EmbargosSemPopayan;
use App\EmbargosSemQuibdo;

use App\DescuentosSedCauca;
use App\DescuentosSedChoco;
use App\DescuentosSedValle;
use App\DescuentosSemCali;
use App\DescuentosSemPopayan;
use App\DescuentosSemQuibdo;

use App\CouponsSemBarranquilla;
use App\CouponsSedAtlantico;
use App\CouponsSedCauca;
use App\CouponsSedMagdalena;
use App\CouponsSedBolivar;
use App\CouponsSedChoco;
use App\CouponsSemPopayan;
use App\CouponsSemQuibdo;
use App\CouponsSemSahagun;
use App\CoupunsSemCali;
use App\CouponsSedValle;
use App\CouponsSedFopep;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TestCollectionImport;
use Illuminate\Support\Facades\Storage;
use App\Exports\NotFoundExport;
use App\Exports\NotFoundTwoDataExport;

class Test
{
    public function testPagaduriaIndividual($ciudad, $documento)
    {
        try {
            $dataModel = $this->getModelPagaduriaByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;
                $pagaduria = $dataModel->data->model::where($label, $documento)->get();
                if (!is_null($pagaduria)) {
                    return json_encode(["status" => 200, "pagaduria" => $pagaduria]);
                } else {
                    return json_encode(["status" => 404, "pagaduria" => []]);
                }
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function testPagaduria($ciudad)
    {
        try {
            $dateInitial = Carbon::now()->toDateTimeString();
            $dataDB = [];
            $count = 0;
            $dataModel = $this->getModelPagaduriaByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;

                $dataModel->data->model::where('doc', '<>', 'no hay valores')->chunk(1000, function ($pagadurias) use (&$dataDB, &$count, &$label) {
                    $count = $count + count($pagadurias);
                    foreach ($pagadurias as $pag) {
                        $dataDB[] = $pag->$label;
                    }
                });

                $dataFileExcel = $this->getDataFile($ciudad, 'pagadurias');
                $diff = array_diff($dataFileExcel, $dataDB);

                Excel::store(new NotFoundExport($diff), 'public/' . $ciudad . '/results/pagadurias-no-encontradas.xlsx');
                $dateFinal = Carbon::now()->toDateTimeString();

                dd([
                    "Tipo" => "Pagaduria",
                    "Ciudad" => $ciudad,
                    "quantityDB" => $count,
                    "quantityExcel" => count($dataFileExcel),
                    "quantityNotFound" => count($diff),
                    "Fecha Inicial" => $dateInitial,
                    "Fecha Final" => $dateFinal
                ]);
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function getModelPagaduriaByCity($ciudad)
    {
        try {
            $data = NULL;
            $ciudad = trim(strtolower($ciudad));

            switch ($ciudad) {
                case 'cauca':
                    $data = (object)["model" => DatamesSedCauca::class, 'label' => 'doc'];
                    break;

                case 'valle':
                    $data = (object)["model" => DatamesSedValle::class, 'label' => 'doc'];
                    break;

                case 'cali':
                    $data = (object)["model" => DatamesSemCali::class, 'label' => 'doc'];
                    break;

                case 'atlantico':
                    $data = (object)["model" => DatamesSedAtlantico::class, 'label' => 'doc'];
                    break;

                case 'barranquilla':
                    $data = (object)["model" => DatamesSemBarranquilla::class, 'label' => 'doc'];
                    break;

                case 'bolivar':
                    $data = (object)["model" => DatamesSedBolivar::class, 'label' => 'doc'];
                    break;

                case 'choco':
                    $data = (object)["model" => DatamesSedChoco::class, 'label' => 'doc'];
                    break;

                case 'fidu':
                    $data = (object)["model" => DatamesFidu::class, 'label' => 'doc'];
                    break;

                case 'fopep':
                    $data = (object)["model" => DatamesFopep::class, 'label' => 'doc'];
                    break;

                case 'magdalena':
                    $data = (object)["model" => DatamesSedMagdalena::class, 'label' => 'codempleado'];
                    break;

                case 'narino':
                    $data = (object)["model" => DatamesSedNarino::class, 'label' => 'doc'];
                    break;

                case 'popayan':
                    $data = (object)["model" => DatamesSemPopayan::class, 'label' => 'doc'];
                    break;

                case 'quibdo':
                    $data = (object)["model" => DatamesSemQuibdo::class, 'label' => 'doc'];
                    break;

                case 'sahagun':
                    $data = (object)["model" => DatamesSemSahagun::class, 'label' => 'codempleado'];
                    break;
            }

            if (!is_null($data)) {
                return (object)['status' => 200, "data" => $data];
            } else {
                return (object)['status' => 404, "data" => "Datames no encontrado"];
            }
        } catch (\Throwable $th) {
            return (object)['status' => 404, "data" => "Error al consultar el datames"];
        }
    }

    //FIN PAGADURIAS

    //INICIO EMBARGOS

    public function testEmbagoIndividual($ciudad, $documento)
    {
        try {
            $dataModel = $this->getModelEmbagoByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;

                $pagaduria = $dataModel->data->model::where($label, $documento)->get();

                if (!is_null($pagaduria)) {
                    return json_encode(["status" => 200, "pagaduria" => $pagaduria]);
                } else {
                    return json_encode(["status" => 404, "pagaduria" => []]);
                }
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function testEmbargo($ciudad)
    {
        try {
            $dateInitial = Carbon::now()->toDateTimeString();
            $dataDB = [];
            $count = 0;
            $dataModel = $this->getModelEmbagoByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;

                $dataModel->data->model::chunk(1000, function ($embargos) use (&$dataDB, &$count, &$label) {
                    $count = $count + count($embargos);

                    foreach ($embargos as $emb) {
                        $dataDB[] = $emb->$label . '&&' . $emb->nomina;
                    }
                });

                $dataFileExcel = $this->getDataFileExtra($ciudad, 'embargos');

                $diff = array_diff($dataFileExcel, $dataDB);
                $diffFormater = [];

                foreach ($diff as $new) {
                    $arrayNew = explode('&&', $new);
                    $diffFormater[] = [$arrayNew[0], $arrayNew[1]];
                }

                Excel::store(new NotFoundTwoDataExport($diffFormater), 'public/' . $ciudad . '/results/embargos-no-encontradas.xlsx');
                $dateFinal = Carbon::now()->toDateTimeString();

                dd([
                    "Tipo" => "Embargo",
                    "Ciudad" => $ciudad,
                    "quantityDB" => $count,
                    "quantityExcel" => count($dataFileExcel),
                    "quantityNotFound" => count($diff),
                    "Fecha Inicial" => $dateInitial,
                    "Fecha Final" => $dateFinal
                ]);
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function getModelEmbagoByCity($ciudad)
    {
        try {
            $data = NULL;
            $ciudad = trim(strtolower($ciudad));

            switch ($ciudad) {
                case 'cauca':
                    $data = (object)["model" => EmbargosSedCauca::class, 'label' => 'doc'];
                    break;

                case 'valle':
                    $data = (object)["model" => EmbargosSedValle::class, 'label' => 'doc'];
                    break;

                case 'cali':
                    $data = (object)["model" => EmbargosSemCali::class, 'label' => 'doc'];
                    break;

                case 'choco':
                    $data = (object)["model" => EmbargosSedChoco::class, 'label' => 'doc'];
                    break;

                case 'popayan':
                    $data = (object)["model" => EmbargosSemPopayan::class, 'label' => 'doc'];
                    break;

                case 'quibdo':
                    $data = (object)["model" => EmbargosSemQuibdo::class, 'label' => 'doc'];
                    break;
            }

            if (!is_null($data)) {
                return (object)['status' => 200, "data" => $data];
            } else {
                return (object)['status' => 404, "data" => "Embargos no encontrado"];
            }
        } catch (\Throwable $th) {
            return (object)['status' => 404, "data" => "Error al consultar el datames"];
        }
    }

    //FIN EMBARGOS

    //INICIO DESCUENTO

    public function testDescuentoIndividual($ciudad, $documento)
    {
        try {
            $dataModel = $this->getModelDescuentoByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;
                $descuentos = $dataModel->data->model::where($label, $documento)->get();

                if (!is_null($descuentos)) {
                    return json_encode(["status" => 200, "descuentos" => $descuentos]);
                } else {
                    return json_encode(["status" => 404, "descuentos" => []]);
                }
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function testDescuento($ciudad)
    {
        try {
            $dateInitial = Carbon::now()->toDateTimeString();
            $dataDB = [];
            $count = 0;
            $dataModel = $this->getModelDescuentoByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;

                $dataModel->data->model::chunk(1000, function ($descuentos) use (&$dataDB, &$count, &$label) {
                    $count = $count + count($descuentos);

                    foreach ($descuentos as $des) {
                        $dataDB[] = $des->$label . '&&' . $des->nomina;
                    }
                });

                $dataFileExcel = $this->getDataFileExtra($ciudad, 'descuentos');

                $diff = array_diff($dataFileExcel, $dataDB);
                $diffFormater = [];

                foreach ($diff as $new) {
                    $arrayNew = explode('&&', $new);
                    $diffFormater[] = [$arrayNew[0], $arrayNew[1]];
                }

                Excel::store(new NotFoundTwoDataExport($diffFormater), 'public/' . $ciudad . '/results/descuentos-no-encontradas.xlsx');
                $dateFinal = Carbon::now()->toDateTimeString();

                dd([
                    "Tipo" => "Descuento",
                    "Ciudad" => $ciudad,
                    "quantityDB" => $count,
                    "quantityExcel" => count($dataFileExcel),
                    "quantityNotFound" => count($diff),
                    "Fecha Inicial" => $dateInitial,
                    "Fecha Final" => $dateFinal
                ]);
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function getModelDescuentoByCity($ciudad)
    {
        try {
            $data = NULL;
            $ciudad = trim(strtolower($ciudad));

            switch ($ciudad) {
                case 'cauca':
                    $data = (object)["model" => DescuentosSedCauca::class, 'label' => 'doc'];
                    break;

                case 'valle':
                    $data = (object)["model" => DescuentosSedValle::class, 'label' => 'doc'];
                    break;

                case 'cali':
                    $data = (object)["model" => DescuentosSemCali::class, 'label' => 'doc'];
                    break;

                case 'choco':
                    $data = (object)["model" => DescuentosSedChoco::class, 'label' => 'doc'];
                    break;

                case 'popayan':
                    $data = (object)["model" => DescuentosSemPopayan::class, 'label' => 'doc'];
                    break;

                case 'quibdo':
                    $data = (object)["model" => DescuentosSemQuibdo::class, 'label' => 'idemp'];
                    break;
            }

            if (!is_null($data)) {
                return (object)['status' => 200, "data" => $data];
            } else {
                return (object)['status' => 404, "data" => "Descuentos no encontrado"];
            }
        } catch (\Throwable $th) {
            return (object)['status' => 404, "data" => "Error al consultar el datames"];
        }
    }

    //FIN DESCUENTO

    //INICIO CUPONES
    public function testCuponIndividual($ciudad, $documento)
    {
        try {
            $dataModel = $this->getModelCuponByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;
                $descuentos = $dataModel->data->model::where($label, $documento)->get();

                if (!is_null($descuentos)) {
                    return json_encode(["status" => 200, "descuentos" => $descuentos]);
                } else {
                    return json_encode(["status" => 404, "descuentos" => []]);
                }
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function testCupon($ciudad)
    {
        try {
            $dateInitial = Carbon::now()->toDateTimeString();
            $dataDB = [];
            $count = 0;
            $dataModel = $this->getModelCuponByCity($ciudad);

            if ($dataModel->status == 200) {
                $label = $dataModel->data->label;

                $dataModel->data->model::chunk(1000, function ($cupones) use (&$dataDB, &$count, $label) {
                    $count = $count + count($cupones);

                    foreach ($cupones as $des) {
                        $dataDB[] = $des->$label . '&&' . trim($des->period);
                    }
                });

                $dataFileExcel = $this->getDataFileExtra($ciudad, 'cupones');

                $diff = array_diff($dataFileExcel, $dataDB);
                $diffFormater = [];

                foreach ($diff as $new) {
                    $arrayNew = explode('&&', $new);
                    $diffFormater[] = [$arrayNew[0], $arrayNew[1]];
                }

                Excel::store(new NotFoundTwoDataExport($diffFormater), 'public/' . $ciudad . '/results/cupones-no-encontradas.xlsx');
                $dateFinal = Carbon::now()->toDateTimeString();

                dd([
                    "Tipo" => "Cupon",
                    "Ciudad" => $ciudad,
                    "quantityDB" => $count,
                    "quantityExcel" => count($dataFileExcel),
                    "quantityNotFound" => count($diff),
                    "Fecha Inicial" => $dateInitial,
                    "Fecha Final" => $dateFinal
                ]);
            } else {
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return json_encode(["Ocurrio un error en el test: " . $th->getMessage()]);
        }
    }

    public function getModelCuponByCity($ciudad)
    {
        try {
            $data = NULL;
            $ciudad = trim(strtolower($ciudad));

            switch ($ciudad) {
                case 'cauca':
                    $data = (object)["model" => CouponsSedCauca::class, 'label' => 'doc'];
                    break;

                case 'valle':
                    $data = (object)["model" => CouponsSedValle::class, 'label' => 'doc'];
                    break;

                case 'cali':
                    $data = (object)["model" => CoupunsSemCali::class, 'label' => 'doc'];
                    break;

                case 'choco':
                    $data = (object)["model" => CouponsSedChoco::class, 'label' => 'doc'];
                    break;

                case 'popayan':
                    $data = (object)["model" => CouponsSemPopayan::class, 'label' => 'doc'];
                    break;

                case 'quibdo':
                    $data = (object)["model" => CouponsSemQuibdo::class, 'label' => 'doc'];
                    break;

                case 'barranquilla':
                    $data = (object)["model" => CouponsSemBarranquilla::class, 'label' => 'doc'];
                    break;

                case 'atlantico':
                    $data = (object)["model" => CouponsSedAtlantico::class, 'label' => 'doc'];
                    break;

                case 'magdalena':
                    $data = (object)["model" => CouponsSedMagdalena::class, 'label' => 'doc'];
                    break;

                case 'bolivar':
                    $data = (object)["model" => CouponsSedBolivar::class, 'label' => 'doc'];
                    break;

                case 'fopep':
                    $data = (object)["model" => CouponsSedFopep::class, 'label' => 'doc'];
                    break;

                case 'sahagun':
                    $data = (object)["model" => CouponsSemSahagun::class, 'label' => 'doc'];
                    break;
            }

            if (!is_null($data)) {
                return (object)['status' => 200, "data" => $data];
            } else {
                return (object)['status' => 404, "data" => "Coupons no encontrado"];
            }
        } catch (\Throwable $th) {
            return (object)['status' => 404, "data" => "Error al consultar el Coupons"];
        }
    }

    //FIN CUPONES

    public function getDataFile($ciudad, $type)
    {
        $path_test = public_path() . Storage::url($ciudad . '/' . $type . '.xlsx');
        $collection = (new TestCollectionImport)->toCollection($path_test, null, \Maatwebsite\Excel\Excel::XLSX);
        $data_list = $collection->all()[0];
        $data_result = [];

        foreach ($data_list as $data) {
            $data_result[] = trim($data['documento']);
        }

        return $data_result;
    }

    public function getDataFileExtra($ciudad, $type)
    {
        $path_test = public_path() . Storage::url($ciudad . '/' . $type . '.xlsx');
        $collection = (new TestCollectionImport)->toCollection($path_test, null, \Maatwebsite\Excel\Excel::XLSX);
        $data_list = $collection->all()[0];
        $data_result = [];

        foreach ($data_list as $data) {
            $data_result[] = trim($data['documento']) . '&&' . trim($data['periodo']);
        }

        return $data_result;
    }


















    //CODIGO ANTES DE REFACTORIZAR




    /*   public function searchDocumentFileInDataDB($data,$field,$value){
        $first = Arr::first($data, function ($object) use($field,$value) {
            return $object->toArray()[$field] == $value;
        });
        return is_null($first)?false:true;
    } */




    /*    public function individual($doc){
        $data = [];
        $pagadurias =  $this->pagaduriaPerDoc($doc);
        $embargos =  $this->embargos($doc,'',$all=true);
        $descuentos =  $this->descuentos($doc,'',$all=true);
        $cupones =  $this->cupones($doc,'',$all=true);
        $descuentos = [];
        $cupones = [];

        $data[] = (object)[
            'doc'=>$doc,
            "pagadurias"=>$pagadurias,
            "embargos"=>$embargos,
            "descuentos"=>$descuentos,
            "cupones"=>$cupones
        ];
        echo "Documento : ".$doc."\n";
        echo "Pagadurias : ".count($pagadurias)."\n";
        echo "embargos : ".count($embargos)."\n";
        echo "Descuentos : ".count($descuentos)."\n";
        echo "Cupones : ".count($cupones)."\n";
        return $data;
        //echo "Se ejecuto el test individual desde el facade, documento: ".(string)$data;    
    }

    public function masivo(){
        $path_test = public_path().Storage::url('test.xlsx');
        $collection = (new TestCollectionImport)->toCollection($path_test,null,\Maatwebsite\Excel\Excel::XLSX);
        $users_list = $collection->all()[0];
        $processed = 0;       
        foreach ($users_list as $user) {
            $processed++;
            $pagadurias =  $this->pagaduriaPerDoc($user['doc']);
            if(count($pagadurias) == 0){
                $data[] = (object)['doc'=>$user['doc'],"pagadurias"=>"No se encontraron pagadurias"];
            }           
        }
        Excel::store(new NotFoundExport($data), 'public/no-found.xlsx');
        echo "Se ejecuto el test masivo desde el facade, cantidad procesada: ".$processed;
    }

    public function pagaduriaPerDoc($doc)
    {
        $models = [
            DatamesSedCauca::class=> 'doc',
            DatamesSedValle::class=> 'doc',
            DatamesSemCali::class=> 'doc',
            DatamesSedAtlantico::class=> 'doc',
            DatamesSemBarranquilla::class=> 'doc',
            DatamesSedBolivar::class=> 'doc',
            DatamesSedChoco::class=> 'doc',
            DatamesFidu::class=> 'doc',
            DatamesFopep::class=> 'doc',
            DatamesSedMagdalena::class=> 'codempleado',
            DatamesSedNarino::class=> 'doc',
            DatamesSemPopayan::class=> 'doc',
            DatamesSemQuibdo::class=> 'doc',
            DatamesSemSahagun::class=> 'codempleado'
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $data = $model::where($column, $doc)->first();

            if ($data) {
                $modelName = class_basename($model);
                $results[] = [Str::camel($modelName)=>$data];
            }
        }
        return $results;
    }


    public function embargos($doc,$embargoType,$all=false)
    {
        $models = [
            EmbargosSedCauca::class => 'doc',
            EmbargosSedChoco::class => 'doc',
            EmbargosSedValle::class => 'doc',
            EmbargosSemCali::class => 'doc',
            EmbargosSemPopayan::class => 'doc',
            EmbargosSemQuibdo::class => 'idemp',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);
            if($all){
                $data = $model::where($column, $doc)->first();
                if ($data) {
                    $results[] = [Str::camel($className)=>$data];
                }
            }else{
                if ($className == $embargoType) {
                    $data = $model::where($column, $doc)->first();
                    if ($data) {
                        $results[] = [Str::camel($className)=>$data];
                    }
                }
            }            
        }
        return $results;
    }


    public function descuentos($doc,$descuentoType,$all=false)
    {
        $models = [
            DescuentosSedCauca::class => 'doc',
            DescuentosSedChoco::class => 'doc',
            DescuentosSedValle::class => 'doc',
            DescuentosSemCali::class => 'doc',
            DescuentosSemPopayan::class => 'doc',
            DescuentosSemQuibdo::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);
            if($all){
                $data = $model::where($column, $doc)->first();
                if ($data) {
                    $results[] = [Str::camel($className)=>$data];
                }
            }else{
                if ($className == $descuentoType) {
                    $data = $model::where($column, $descuentoType)->first();
                    if ($data) {
                        $results[] = [Str::camel($className)=>$data];
                    }
                }
            }            
        }
        return $results;
    }


    public function cupones($doc,$cupoType,$all=false)
    {
        $models = [
            CouponsSemBarranquilla::class => 'doc',
            CouponsSedAtlantico::class => 'doc',
            CouponsSedCauca::class => 'doc',
            CouponsSedMagdalena::class => 'doc',
            CouponsSedBolivar::class => 'doc',
            CouponsSedChoco::class => 'doc',
            CouponsSemPopayan::class => 'doc',
            CouponsSemQuibdo::class => 'doc',
            CouponsSemSahagun::class => 'doc',
            CoupunsSemCali::class => 'doc',
            CouponsSedValle::class => 'doc',
            CouponsSedFopep::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);
            if($all){
                $data = $model::where($column, $doc)->first();
                if ($data) {
                    $results[] = [Str::camel($className)=>$data];
                }
            }else{
                if ($className == $cupoType) {
                    $data = $model::where($column, $cupoType)->first();
                    if ($data) {
                        $results[] = [Str::camel($className)=>$data];
                    }
                }
            }            
        }
        return $results;

    }


    public function testDocsExcel(){

        $path_test = public_path().Storage::url('test.xlsx');
        $collection = (new ClientCollectionImport)->toCollection($path_test,null,\Maatwebsite\Excel\Excel::XLSX);
        $users_list = $collection->all()[0];
        $processed = 0;
        $found = 0;
        $updated = 0;
        $not_updated = 0;
        $not_found = 0;
        $data_not_fount = [];
        $data_fount = [];
        foreach ($users_list as $user) {
            $processed++;
        
        }

        $response = (object)[
            'processed'                           => $processed,
            'found'                               => $found,
            'updated'                             => $updated,
            'not_updated'                         => $not_updated,
            'not_found'                           => $not_found,
            'data_not_fount'                      => $data_not_fount,
            'data'                                => $users_list
        ];

        dd($users_list);
        return $response;
    } */
}
