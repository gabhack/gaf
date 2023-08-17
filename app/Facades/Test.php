<?php
namespace App\Facades;

use App\DatamesFidu;
use App\DatamesFopep;
use App\DatamesSedMeta;
use App\DatamesSemBuga;
use App\DatamesSemCali;
use App\DatamesSedCauca;
use App\DatamesSedCesar;
use App\DatamesSedChoco;
use App\DatamesSedHuila;
use App\DatamesSedSucre;
use App\DatamesSedValle;
use App\DatamesSemNeiva;
use App\DatamesSemPasto;
use App\DatamesSemYopal;
use App\DatamesSemYumbo;
use App\DatamesSedArauca;
use App\DatamesSedBoyaca;
use App\DatamesSedCaldas;
use App\DatamesSedNarino;
use App\DatamesSedTolima;
use App\DatamesSemIbague;
use App\DatamesSemQuibdo;
use App\DatamesSedBolivar;
use App\DatamesSedCordoba;
use App\DatamesSedGuajira;
use App\DatamesSemIpiales;
use App\DatamesSemJamundi;
use App\DatamesSemPalmira;
use App\DatamesSemPopayan;
use App\DatamesSemSahagun;
use App\DatamesSemSoledad;
use App\DatamesSedCasanare;
use App\DatamesSemGirardot;
use App\DatamesSemMagangue;
use App\DatamesSemMedellin;
use App\DatamesSemMonteria;
use App\DatamesSemMosquera;
use App\DatamesSemRioNegro;
use App\DatamesSemSabaneta;
use App\DatamesSedAntioquia;
use App\DatamesSedAtlantico;
use App\DatamesSedMagdalena;
use App\DatamesSedRisaralda;
use App\DatamesSedSantander;
use App\DatamesSemCartagena;
use App\DatamesSemSincelejo;
use App\DatamesSemZipaquira;
use App\DatamesSemValledupar;
use App\DatamesSedCundinamarca;
use App\DatamesSemBarranquilla;

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

use App\CouponsSedAtlantico;
use App\CouponsSemBarranquilla;
use App\CouponsSedBolivar;
use App\CouponsSedCauca;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedMagdalena;
use App\CouponsSemPopayan;
use App\CouponsSemQuibdo;
use App\CouponsSemSahagun;
use App\CoupunsSemCali;
use App\CouponsSedValle;

use Illuminate\Support\Str;
use App\Exports\NotFoundExport;
use App\DatamesSedNorteSantander;
use App\Imports\ClientCollectionImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class Test{
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
        $collection = (new ClientCollectionImport)->toCollection($path_test,null,\Maatwebsite\Excel\Excel::XLSX);
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
            DatamesFidu::class => 'doc',
            DatamesFopep::class => 'doc',
            DatamesSedAntioquia::class => 'doc',
            DatamesSedArauca::class => 'doc',
            DatamesSedAtlantico::class => 'doc',
            DatamesSedBolivar::class => 'doc',
            DatamesSedBoyaca::class => 'doc',
            DatamesSedCaldas::class => 'doc',
            DatamesSedCasanare::class => 'doc',
            DatamesSedCauca::class => 'doc',
            DatamesSedCesar::class => 'doc',
            DatamesSedChoco::class => 'doc',
            DatamesSedCordoba::class => 'doc',
            DatamesSedCundinamarca::class => 'doc',
            DatamesSedGuajira::class => 'doc',
            DatamesSedHuila::class => 'doc',
            DatamesSedMagdalena::class => 'codempleado',
            DatamesSedMeta::class => 'doc',
            DatamesSedNarino::class => 'doc',
            DatamesSedNorteSantander::class => 'doc',
            DatamesSedRisaralda::class => 'doc',
            DatamesSedSantander::class => 'doc',
            DatamesSedSucre::class => 'doc',
            DatamesSedTolima::class => 'doc',
            DatamesSedValle::class => 'doc',
            DatamesSemBarranquilla::class => 'doc',
            DatamesSemBuga::class => 'doc',
            DatamesSemCali::class => 'doc',
            DatamesSemCartagena::class => 'doc',
            DatamesSemGirardot::class => 'doc',
            DatamesSemIbague::class => 'doc',
            DatamesSemIpiales::class => 'doc',
            DatamesSemJamundi::class => 'doc',
            DatamesSemMagangue::class => 'doc',
            DatamesSemMedellin::class => 'doc',
            DatamesSemMonteria::class => 'doc',
            DatamesSemMosquera::class => 'doc',
            DatamesSemNeiva::class => 'doc',
            DatamesSemPalmira::class => 'doc',
            DatamesSemPasto::class => 'doc',
            DatamesSemPopayan::class => 'doc',
            DatamesSemQuibdo::class => 'doc',
            DatamesSemRioNegro::class => 'doc',
            DatamesSemSabaneta::class => 'doc',
            DatamesSemSahagun::class => 'codempleado',
            DatamesSemSincelejo::class => 'doc',
            DatamesSemSoledad::class => 'doc',
            DatamesSemValledupar::class => 'doc',
            DatamesSemYopal::class => 'doc',
            DatamesSemYumbo::class => 'doc',
            DatamesSemZipaquira::class => 'doc',
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