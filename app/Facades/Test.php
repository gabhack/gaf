<?php
namespace App\Facades;

use Carbon\Carbon;
use App\DatamesFidu;
use App\DatamesFopep;
use App\CoupunsSemCali;
use App\DatamesSemCali;
use App\CouponsSedCauca;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedValle;
use App\DatamesSedCauca;
use App\DatamesSedChoco;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TestCollectionImport;
use Illuminate\Support\Facades\Storage;
use App\Exports\NotFoundExport;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Test{


    public function testPagaduriaIndividual($ciudad,$documento){
        try {
            $dataModel = $this->getModelPagaduriaByCity($ciudad);
            if($dataModel->status==200){
                $pagaduria = $dataModel->data->model::where('doc',$documento)->get();
                if(!is_null($pagaduria)){
                    return json_encode(["status"=>200,"pagaduria"=>$pagaduria]);
                }else{
                    return json_encode(["status"=>404,"pagaduria"=>[]]);
                }
            }else{
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: ".$th->getMessage()]);
        }
    }

    public function testPagaduria($ciudad){
        try {
            $dateInitial = Carbon::now()->toDateTimeString();
            $dataExportFile = [];
            $dataDB = [];
            $count = 0;
            $dataModel = $this->getModelPagaduriaByCity($ciudad);
            if($dataModel->status==200){
                $dataModel->data->model::where('doc','<>','no hay valores')->chunk(1000, function ($pagadurias) use (&$dataDB,&$count) {
                    $count = $count + count($pagadurias); 
                    foreach ($pagadurias as $pag) {
                        $dataDB[]=$pag->doc;                    
                    }
                });
                $dataFileExcel = $this->getDataFile($ciudad,'pagadurias');
                $diff = array_diff($dataFileExcel, $dataDB);
                
                Excel::store(new NotFoundExport($diff), 'public/'.$ciudad.'/results/pagadurias-no-encontradas.xlsx');
                $dateFinal = Carbon::now()->toDateTimeString();
                return json_encode([
                    "quantityDB"=>$count,
                    "quantityExcel"=>count($dataFileExcel),
                    "quantityNotFound"=>count($diff),
                    "Fecha Inicial"=>$dateInitial,
                    "Fecha Final"=>$dateFinal
                ]);
                
            }else{
                return json_encode(["Cuidad no encontrada"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["Ocurrio un error en el test: ".$th->getMessage()]);
        }
    }

    
  /*   public function searchDocumentFileInDataDB($data,$field,$value){
        $first = Arr::first($data, function ($object) use($field,$value) {
            return $object->toArray()[$field] == $value;
        });
        return is_null($first)?false:true;
    } */

    public function getModelPagaduriaByCity($ciudad){
        try {
            $data = NULL;
            $ciudad = trim(strtolower($ciudad));
            switch ($ciudad) {
                case 'cauca':
                    $data = (object)["model"=>DatamesSedCauca::class,'label'=>'doc'];
                break;
                
                case 'valle':
                    $data = (object)["model"=>DatamesSedValle::class,'label'=>'doc'];
                break;
                
                case 'cali':
                    $data = (object)["model"=>DatamesSemCali::class,'label'=>'doc'];
                break;
                
                case 'atlantico':
                    $data = (object)["model"=>DatamesSedAtlantico::class,'label'=>'doc'];
                break;
                
                case 'barranquilla':
                    $data = (object)["model"=>DatamesSemBarranquilla::class,'label'=>'doc'];
                break;
                
                case 'bolivar':
                    $data = (object)["model"=>DatamesSedBolivar::class,'label'=>'doc'];
                break;
                
                case 'choco':
                    $data = (object)["model"=>DatamesSedChoco::class,'label'=>'doc'];
                break;
                
                case 'fidu':
                    $data = (object)["model"=>DatamesFidu::class,'label'=>'doc'];
                break;
                
                case 'fopep':
                    $data = (object)["model"=>DatamesFopep::class,'label'=>'doc'];
                break;
                
                case 'magdalena':
                    $data = (object)["model"=>DatamesSedMagdalena::class,'label'=>'codempleado'];
                break;
                
                case 'narino':
                    $data = (object)["model"=>DatamesSedNarino::class,'label'=>'doc'];
                break;
                
                case 'popayan':
                    $data = (object)["model"=>DatamesSemPopayan::class,'label'=>'doc'];
                break;
                
                case 'quibdo':
                    $data = (object)["model"=>DatamesSemQuibdo::class,'label'=>'doc'];
                break;
                
                case 'sahagun':
                    $data = (object)["model"=>DatamesSemSahagun::class,'label'=>'codempleado'];
                break;           
            }

            if(!is_null($data)){
                return (object)['status'=>200,"data"=>$data];
            }else{
                return (object)['status'=>404,"data"=>"Datames no encontrado"];
            }
        } catch (\Throwable $th) {
            return (object)['status'=>404,"data"=>"Error al consultar el datames"];
        }
    }

    public function getDataFile($ciudad,$type){
        $path_test = public_path().Storage::url($ciudad.'/'.$type.'.xlsx');
        $collection = (new TestCollectionImport)->toCollection($path_test,null,\Maatwebsite\Excel\Excel::XLSX);
        $data_list = $collection->all()[0];
        $data_result = [];
        foreach ($data_list as $data) {
            $data_result[] = trim($data['documento']);
        }
        return $data_result;
    }











//CODIGO ANTES DE REFACTORIZAR







    public function individual($doc){
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
    }


}