<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Facades\App\Facades\Test;
 
class TestController extends Controller
{

    public function testPagaduriaIndividual($ciudad,$documento){
        return Test::testPagaduriaIndividual($ciudad,$documento);
    }
    public function testPagaduria($ciudad){
        return Test::testPagaduria($ciudad);
    }

    public function testEmbagoIndividual($ciudad,$documento){
        return Test::testEmbargoIndividual($ciudad,$documento);
    }
    public function testEmbargo($ciudad){
        return Test::testEmbargo($ciudad);
    }


    public function testDescuentoIndividual($ciudad,$documento){
        return Test::testDescuentoIndividual($ciudad,$documento);
    }
    public function testDescuento($ciudad){
        return Test::testDescuento($ciudad);
    }


    public function testCuponIndividual($ciudad,$documento){
        return Test::testCuponIndividual($ciudad,$documento);
    }
    public function testCupon($ciudad){
        return Test::testCupon($ciudad);
    }










    public function index(){
        return Test::masivo();   
    }
    
    public function search($doc){
        return Test::individual($doc);
    }
}