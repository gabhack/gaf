<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dataCotizer extends Model
{
    protected $table = 'data_cotizer';
    protected $fillable = [
        'gender',
        'firstName',
        'middleName',
        'firstLastname',
        'secondLastname',
        'birthday',
        'idType',
        'idNumber',
        'idExpeditionDate',
        'maritalStatus',
        'childs',
        'living',
        'studies',
        'city',
        'time',
        'estrato',
        'phoneNumber',
        'phoneNumberFijo',
        'email',
        'transport',
        'others',
        'factoryName',
        'startDate',
        'workCity',
        'addressWork',
        'phoneWork',
        'nit',
        'ingreso',
        'gasto',
        'neto',
        'accountType',
        'accountNumber',
        'referenceName',
        'referencePhone',
        'referenceCity',
        'referenceAddress',
        'referenceBarrio',
        'referenceParent',
        'referenceFName',
        'referenceFPhone',
        'referenceFCity',
        'referenceFAddress',
        'referenceFBarrio',
        'referenceFParent',
        'referenceFState',
    ];

    protected $attributes = [
        'nit' => '-',
    ];
}
