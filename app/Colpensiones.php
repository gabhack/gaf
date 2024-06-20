<?php

namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Colpensiones extends Model
{

    protected $connection = 'pgsql';


    protected $table = 'colpensiones';

    protected $fillable = [
        'documento',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'direccion',
        'telefono',
        'correo_electronico',
        'nacimiento',
        'sexo',
        'departamento',
        'municipio',
        'vpensiones',
        'vsalud',
        'vembargo',
        'vdescuentos',
        'capacidad',
    ];
}
