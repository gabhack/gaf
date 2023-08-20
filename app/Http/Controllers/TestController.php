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

    public function index(){
        return Test::masivo();   
    }
    
    public function search($doc){
        return Test::individual($doc);
    }
}