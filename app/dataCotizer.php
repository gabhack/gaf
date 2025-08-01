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
        'placeBirth',
        'idType',
        'idNumber',
        'idExpeditionDate',
        'idExpeditionPlace',
        'maritalStatus',
        'studies',
        'country',
        'department',
        'municipality',
        'barrio',
        'houseType',
        'time',
        'living',
        'childs',
        'phoneNumber',
        'phoneNumberFijo',
        'epsEntity',
        'correspondencyType',
        'address',
        'email',
        'transport',
        'others',
        'firstNameSpouse',
        'middleNameSpouse',
        'firstLastnameSpouse',
        'secondLastnameSpouse',
        'idNumberSpouse',
        'idTypeDocSpouse',
        'idExpeditionDateSpouse',
        'phoneNumberSpouse',
        'ocupations',
        'rents',
        'economicActivity',
        'workName',
        'workTitle',
        'workType',
        'startDate',
        'workAddress',
        'phoneWork',
        'workCity',
        'bussinesType',
        'bussinesNit',
        'factoryName', //no
        'workScale', //no
        'addressWork', //no
        'workDepartment', //no
        'workMunicipality', //no
        'bankingEntity',
        'accountNumber',
        'accountType',
        'pinNumber',
        'expenditureType', //no
        'nit',
        'ingreso', //no
        'gasto', //no
        'neto', //no
        'referenceFName',
        'referenceFParent',
        'referenceFActivity',
        'referenceFPhone',
        'referenceFDepartment',
        'referenceFMunicipality',
        'referenceFBarrio',
        'referenceFAddress',
        'referenceFState',
        'referenceName',
        'referenceParent',
        'referenceActivity',
        'referencePhone',
        'referenceDepartment',
        'referenceMunicipality',
        'referenceBarrio',
        'referenceAddress',
        'operationForeign',
        'accountForeign',
        'operationCrypto',
        'activityAPNFD',
        'foreignEntity',
        'foreignAccount',
        'foreignProduct',
        'foreignAmount',
        'foreignCurrency',
        'foreignCity',
        'foreignCountry',
        'personPublic',
        'resourcePublic',
        'politicalInfluence',
        'ethnicGroup',
        'ethnicName',
        'financialEmploymentIncome',
        'financialFeesCommissions',
        'financialOtherIncome',
        'financialTotalIncome',
        'financialFixedAssets',
        'financialTotalAssets',
        'financialFee',
        'financialLiabilities',
        'financialCurrentLiabilities',
        'financialFamilyExpenses',
        'financialOtherLiabilities',
        'financialTotalLiabilities',
        'questionFatca1',
        'questionFatca2',
        'questionFatca3',
        'questionFatca4',
        'questionFatca4Country',
        'questionFatca4TaxId',
        'questionFatca4PowerObject',
        'foreignAccountTypes',
        'question1',
        'question2',
        'question3',
        'question4',
        'question5',
    ];

    protected $attributes = [
        'nit' => '-',
    ];

    public function estudio()
    {
        return $this->hasOne('App\Estudiostr', 'data_cotizer_id', 'id');
    }
}
