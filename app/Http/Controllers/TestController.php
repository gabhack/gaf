<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Facades\App\Facades\Test;
 
class TestController extends Controller
{
    public function index(){
        return Test::masivo();   
    }
    
    public function search($doc){
        return Test::individual($doc);
    }
}