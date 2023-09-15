@extends('layouts.app2')

@section('css')
<link href="{{asset('css/gijgo-combined-1.9.13/css/gijgo.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<form id="form_guardar" action="/estudios/actualizar" method="POST" enctype="multipart/form-data">
  {!! Form::token() !!}
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="btn-group mr-2 float-right" role="group">
          <a type="button" class="btn btn-secondary" href="{{url('estudios')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
          <input class="btn btn-success" type="submit" value="Actualizar">
        </div>
        <h3><b>CLIENTE: </b>{{$dataCotizer->firstName}} {{ $dataCotizer->firstLastname}}</h3>

        <h6><b>TIPO DE DOCUMENTO: </b>{{ $dataCotizer->idType }}</h6>
        <h6><b>FECHA EXPEDICION CC: </b>{{ $dataCotizer->idExpeditionDate }}</h6>
        <h6><b>DOCUMENTO: </b>{{ $dataCotizer->idNumber }}</h6>
      </div>

      <div class="col-md-7">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Encuentra</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->idType == '' ? ' label-warning' : '' }}" for="pad"><b>PAIS:</b>
                  <p class="pad">COLOMBIA</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->referenceAddress == '' ? ' label-warning' : '' }}" for="pad"><b>DIRECCIÓN:</b>
                  <p class="pad">NO ESTA</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->nombres == '' ? ' label-warning' : '' }}" for="pad"><b>TIEMPO DE RESIDENCIA:</b>
                  <p class="pad">NO ESTA</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->referenceBarrio == '' ? ' label-warning' : '' }}" for="pad"><b>BARRIO:</b>
                  <p class="pad">NO ESTA</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->city == '' ? ' label-warning' : '' }}" for="pad"><b>CIUDAD:</b>
                  <p class="pad">{{$dataCotizer->city}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->estrato == '' ? ' label-warning' : '' }}" for="pad"><b>ESTRATO:</b>
                  <p class="pad">{{$dataCotizer->estrato}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>DIRECCIÓN LABORAL:</b>
                  <p class="pad">{{ $dataCotizer->addressWork}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>SEDE/LUGAR DE TRABAJO:</b>
                  <p class="pad">{{ $dataCotizer->workCity}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->phoneNumber == '' ? ' label-warning' : '' }}" for="pad"><b>TELÉFONO PERSONAL:</b>
                  <p class="pad">{{ $dataCotizer->phoneNumber}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->phoneWork == '' ? ' label-warning' : '' }}" for="pad"><b>TELÉFONO LABORAL:</b>
                  <p class="pad">{{ $dataCotizer->phoneWork}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->celular == '' ? ' label-warning' : '' }}" for="pad"><b>CELULAR:</b>
                  <p class="pad">{{ $dataCotizer->phoneNumber}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->email == '' ? ' label-warning' : '' }}" for="pad"><b>CORREO ELECTRONICO:</b>
                  <p class="pad">{{ $dataCotizer->email == '' ? '-' : $dataCotizer->email }}</p>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Conocer</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>GENERO:</b>
                  <p class="pad">{{ $dataCotizer->gender == 'F' ? 'Femenino' : 'Masculino' }}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->birthday == '' ? ' label-warning' : '' }}" for="pad"><b>FECHA DE NACIMIENTO:</b>
                  @if ($dataCotizer->birthday == '')
                  <p class="pad">No proporcionado</p>
                  @else
                  <p class="pad">{{ $dataCotizer->birthday }}</p>
                  @endif
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>EDAD:</b>
                  <p class="pad">{{ $dataCotizer->birthday }}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>TIPO DE VIVIENDA:</b>
                  <p class="pad">NO ESTA</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>NIVEL EDUCATIVO:</b>
                  <p class="pad">{{$dataCotizer->studies}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->childs == '' ? ' label-warning' : '' }}" for="pad"><b>PERSONAS A CARGO:</b>
                  <p class="pad">{{$dataCotizer->living}} </p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>CANTIDAD DE HIJOS:</b>
                  <p class="pad">{{$dataCotizer->childs}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->gender == '' ? ' label-warning' : '' }}" for="pad"><b>ESTADO CIVIL:</b>
                  <p class="pad">{{$dataCotizer->maritalStatus}}</p>
                </label>
              </div>
              <div class="col-md-4">
                <label class="label-consulta{{ $dataCotizer->idExpeditionDate == '' ? ' label-warning' : '' }}" for="pad"><b>FECHA EXPEDICION CC:</b>
                  @if ($dataCotizer->idExpeditionDate == '')
                  <p class="pad">No proporcionado</p>
                  @else
                  <p class="pad">{{ $dataCotizer->idExpeditionDate }}</p>
                  @endif
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Habitos</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score AMI:</b>
                  <input class="form-control" type="number" name="puntaje_datacredito" id="puntaje_datacredito" value="" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score EXPERIAN:</b>
                  <input class="form-control" type="number" name="puntaje_datacredito" id="puntaje_datacredito" value="" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score TRANSUNION:</b>
                  <input class="form-control" type="number" name="puntaje_sifin" id="puntaje_sifin" value="582" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cuentas de Ahorros:
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Activa" value="Activa" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Inactiva" value="Inactiva" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Embargada" value="Embargada" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="3" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="5" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="9" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cuentas Corrientes:
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Activa" value="Activa" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Inactiva" value="Inactiva" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Embargada" value="Embargada" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="3" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="1" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="8" min="1" max="99" disabled>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Comportamientos</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <label class="label-consulta" for="pad"><b>Teléfono:</b>
                  <p class="pad">No proporcionado</p>
                </label>
              </div>
              <div class="col-md-6">
                <label class="label-consulta" for="pad"><b>Celular:</b>
                  <p class="pad">No proporcionado</p>
                </label>
              </div>
              <div class="col-md-6">
                <label class="label-consulta" for="pad"><b>Dirección:</b>
                  <p class="pad">No proporcionado</p>
                </label>
              </div>
              <div class="col-md-6">
                <label class="label-consulta" for="pad"><b>Correo electrónico:</b>
                  <p class="pad">No proporcionado</p>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Calificacion de riesgo comercial</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score AMI:</b>
                  <input class="form-control" type="number" name="puntaje_datacredito" id="puntaje_datacredito" value="123" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score EXPERIAN:</b>
                  <input class="form-control" type="number" name="puntaje_datacredito" id="puntaje_datacredito" value="123" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad"><b>Score TRANSUNION:</b>
                  <input class="form-control" type="number" name="puntaje_sifin" id="puntaje_sifin" value="582" placeholder="Opcional" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cuentas de Ahorros:
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Activa" value="Activa" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Inactiva" value="Inactiva" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Embargada" value="Embargada" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="3" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="5" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="9" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cuentas Corrientes:
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Activa" value="Activa" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Inactiva" value="Inactiva" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Embargada" value="Embargada" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="3" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="1" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de cuentas:
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="2" min="1" max="99" disabled>
                  <input class="form-control" type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="8" min="1" max="99" disabled>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Calificacion de riesgo juridico</b></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Estados:
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Activa" value="Activo" min="1" max="99" disabled>
                  <input class="form-control text-left" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Inactiva" value="Cerrado" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Cantidad de procesos:
                  <input class="form-control" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="Proceso judicial" min="1" max="99" disabled>
                  <input class="form-control" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="Proceso judicial" min="1" max="99" disabled>
                </label>
              </div>
              <div class="col-md-4 text-center">
                <label class="label-consulta col-12" for="pad">Numero de proceso:
                  <input class="form-control" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="5" min="1" max="99" disabled>
                  <input class="form-control" type="text" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="0" min="1" max="99" disabled>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel-heading mb-4 text-center">
          <div class="row">
            <b class="col-md-4">Pagaduria: FOPEP</b>
            <b class="col-md-4">Seleccionar Periodo Multiple:</b>
            <b class="col-md-4">Seleccionar Periodo Unico:</b>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-3 text-center">
              <label class="label-consulta" for="pad"><b>AÑO:</b>
                <div class="row">
                  <select class="form-control col-md-12" name="costo_servicio_tr_ptg" id="costo_servicio_tr_ptg">
                    <option value="">2022</option>
                  </select>
                </div>
              </label>
            </div>
            <div class="col-md-5 text-center">
              <label class="label-consulta" for="pad"><b>MES:</b>
                <div class="row">
                  <div class="form-group col-md-5">
                    <select class="form-control" name="costo_servicio_tr_ptg" id="costo_servicio_tr_ptg">
                      <option value="">Seleccione uno...</option>
                      <option value="">ENERO</option>
                      <option value="">FEBRERO</option>
                      <option value="">MARZO</option>
                      <option value="">ABRIL</option>
                      <option value="">MAYO</option>
                      <option value="">JUNIO</option>
                      <option value="">JULIO</option>
                      <option value="">AGOSTO</option>
                      <option value="">SEPTIEMBRE</option>
                      <option value="">OCTUBRE</option>
                      <option value="">NOVIEMBRE</option>
                      <option value="">DICIEMBRE</option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <p class="pt-1 mb-0 font-weight-bold"> - </p>
                  </div>
                  <div class="form-group col-md-5">
                    <select class="form-control" name="costo_servicio_tr_ptg" id="costo_servicio_tr_ptg">
                      <option value="">Seleccione uno...</option>
                      <option value="">ENERO</option>
                      <option value="">FEBRERO</option>
                      <option value="">MARZO</option>
                      <option value="">ABRIL</option>
                      <option value="">MAYO</option>
                      <option value="">JUNIO</option>
                      <option value="">JULIO</option>
                      <option value="">AGOSTO</option>
                      <option value="">SEPTIEMBRE</option>
                      <option value="">OCTUBRE</option>
                      <option value="">NOVIEMBRE</option>
                      <option value="">DICIEMBRE</option>
                    </select>
                  </div>
                </div>
              </label>
            </div>
            <div class="col-md-4 text-center">
              <label class="label-consulta" for="pad"><b>MES:</b>
                <select class="form-control" name="costo_servicio_tr_ptg" id="costo_servicio_tr_ptg">
                  <option value="">Seleccione uno...</option>
                  <option value="">ENERO</option>
                  <option value="">FEBRERO</option>
                  <option value="">MARZO</option>
                  <option value="">ABRIL</option>
                  <option value="">MAYO</option>
                  <option value="">JUNIO</option>
                  <option value="">JULIO</option>
                  <option value="">AGOSTO</option>
                  <option value="">SEPTIEMBRE</option>
                  <option value="">OCTUBRE</option>
                  <option value="">NOVIEMBRE</option>
                  <option value="">DICIEMBRE</option>
                </select>
              </label>
            </div>
          </div>
        </div>



      </div>

      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Ingresos aplicados</b></div>
          <div class="panel-body">
            <table class="table table-hover table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Concepto</th>
                  <th class="text-center">Valor</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>MINDDEFENSA PENSIONADO</td>
                  <td class="text-center">$ 1.757.671</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td><b>TOTAL</b></td>
                  <td style="text-align: center;"><b>$ 1.757.671</b></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Descuentos aplicados</b></div>
          <div class="panel-body">
            <table class="table table-hover table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Concepto</th>
                  <th class="text-center">Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>BAYPORT LB 7887</td>
                  <td class="text-center">$ 759.208</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td><b>TOTAL</b></td>
                  <td style="text-align: center;"><b>$ 759.208</b></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Descuentos no aplicados</b></div>
          <div class="panel-body">
            <table class="table table-hover table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Periodo</th>
                  <th class="text-center">Concepto</th>
                  <th class="text-center">Inconsistencia</th>
                  <th class="text-center">Valor cuota</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">202008</td>
                  <td class="text-center">IVAN BOTERO GOMEZ OBL 3685</td>
                  <td class="text-center">CASTIGADA</td>
                  <td class="text-center">$ 0</td>
                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Embargos</b></div>
          <div class="panel-body">
            <table class="table table-hover table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Reporte</th>
                  <th class="text-center">Fecha Embargo</th>
                  <th class="text-center">Demandante</th>
                  <th class="text-center">Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td class="text-center">241812</td>
                  <td class="text-center">2018-08-15</td>
                  <td class="text-center">CASTIGADA</td>
                  <td class="text-center">$ 0</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Mensajes de liquidación</b></div>
          <div class="panel-body">
            <table class="table table-hover table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Periodo</th>
                  <th class="text-center">Tipo de mensaje</th>
                  <th class="text-center">Mensaje</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">2</td>
                  <td class="text-center">2</td>
                  <td class="text-center">2</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Carteras por comprar</b></div>
          <div class="panel-body">
            <table id="grid" class="table table-hover table-condensed table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">ENTIDAD</th>
                  <th class="text-center">CUOTA</th>
                  <th class="text-center">SALDO INICIAL</th>
                  <th class="text-center">NEGOCIACIÓN</th>
                  <th class="text-center">VALOR A PAGAR</th>
                  <th class="text-center">MONTO MÁX PAGAR</th>
                  <th class="text-center">% NEGOCIACIÓN</th>
                  <th class="text-center">F VENCIMIENTO</th>
                  <th class="text-center">SE COMPRA?</th>
                </tr>
              </thead>
              <tbody>
                <!-- CIFIN -->
                <!-- Verificando si es un arreglo -->
                @if(isset($sectorFinanciero['Obligacion'][0]))
                  @foreach($sectorFinanciero['Obligacion'] as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @else
                  @foreach($sectorFinanciero as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @endif

                <!-- CIFIN -->
                <!-- Verificando si es un arreglo -->
                @if(isset($sectorFinancieroReal['Obligacion'][0]))
                  @foreach($sectorFinancieroReal['Obligacion'] as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @else
                  @foreach($sectorFinancieroReal as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['SaldoObligacion'] }}</td>
                      <td></td>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @endif

                <!-- CIFIN -->
                <!-- Verificando si es un arreglo -->
                @if(isset($cuentas_vigentes['Obligacion'][0]))
                  @foreach($cuentas_vigentes['Obligacion'] as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>   
                      <td>{{ $res['ValorInicial'] }}</td>
                      <td></td>
                      <td>{{ $res['ValorInicial'] }}</td>
                      <td></td>
                      <td></td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @else
                  @foreach($cuentas_vigentes as $res)
                    <tr>
                      <td>{{ $res['PaqueteInformacion'] }}</td>
                      <td>{{ $res['NombreEntidad'] }}</td>
                      <td></td>
                      <td>{{ $res['ValorInicial'] }}</td>
                      <td></td>
                      <td>{{ $res['ValorInicial'] }}</td>
                      <td></td>
                      <td></td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @endif

                <!-- EMBARGOS -->
                <!-- Verificando si es un arreglo -->
                @if(isset($embargos[0]))
                  @foreach($embargos[0] as $key => $res)
                    <tr>
                      <td>{{ $res['id'] }}</td>
                      <td>{{ $res['entidaddeman'] }}</td>
                      <td></td>   
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{ $res['fembfin'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @else
                  @foreach($embargos as $key => $res)
                    <tr>
                      <td>{{ $res['id'] }}</td>
                      <td>{{ $res['entidaddeman'] }}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{ $res['FechaCorte'] }}</td>
                      <td>
                        <select>
                          <option value=""></option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                @endif


                <!-- CARTERAS -->
                @if(count($carteras) > 0)
                  @foreach($carteras as $key => $res)
                    <tr>
                      <td>{{ $res->id }}</td>
                      <td>{{ $res->nombre_obligacion  }}</td>
                      <td>{{ $res->cuota  }}</td>   
                      <td>{{ $res->saldo  }}</td>
                      <td></td>
                      <td>{{ $res->valor_ini  }}</td>
                      <td></td>
                      <td></td>
                      <td>{{ $res->fecha_vence }}</td>
                      <td id="td-compra-{{ $res->id }}">
                        @if($res->estatus == 0)
                          <button type="button" onclick="comprarCartera({{ $res->id }})">Comprar</button>
                        @else
                          <span>Comprada</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCartera">Agregar cartera</button><br><br>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading"><b></b></div>
          <div class="panel-body">
            <table>
              <tr>
                <td valign="top">
                  <br>
                  <table border="0" cellspacing="1" cellpadding="2" class="tab1" width="100%">
                  <tbody><tr>
                    <th colspan="2">OPCIONES DE CRÉDITO</th>
                    <th>OPCIÓN CUOTA</th>
                    <th colspan="2">OPCIÓN DESEMBOLSO</th>
                  </tr>
                  <tr>
                    <td align="center"><input type="radio" name="opcion_credito" value="CLI" onchange="recalcular()" disabled=""></td>
                    <td style="font-size:16"><b>CUPO DE LIBRE INVERSION</b></td>
                    <td><input type="text" id="opcion_cuota_cli" name="opcion_cuota_cli" value="" size="15" style="height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                    <td colspan="2"><input type="text" id="opcion_desembolso_cli" name="opcion_desembolso_cli" value="" style="width:95%; height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                  </tr>
                  <tr>
                    <td align="center"><input type="radio" name="opcion_credito" value="CCC" onchange="recalcular()" checked=""></td>
                    <td style="font-size:16"><b>CUPO CON COMPRAS</b></td>
                    <td><input type="text" id="opcion_cuota_ccc" name="opcion_cuota_ccc" value="" size="15" style="height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                    <td colspan="2"><input type="text" id="opcion_desembolso_ccc" name="opcion_desembolso_ccc" value="" style="width:95%; height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                  </tr>
                  <tr>
                    <td align="center"><input type="radio" name="opcion_credito" value="CMP" onchange="recalcular()"></td>
                    <td style="font-size:16"><b>CUPO MAXIMO POSIBLE</b></td>
                    <td><input type="text" id="opcion_cuota_cmp" name="opcion_cuota_cmp" value="" size="15" style="height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                    <td colspan="2"><input type="text" id="opcion_desembolso_cmp" name="opcion_desembolso_cmp" value="" style="width:95%; height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                  </tr>
                  <tr>
                    <td align="center"><input type="radio" name="opcion_credito" value="CSO" onchange="recalcular()"></td>
                    <td style="font-size:16"><b>CUPO SOLICITADO</b></td>
                    <td><input type="text" id="opcion_cuota_cso" name="opcion_cuota_cso" value="" size="15" onfocus="this.value = this.value.replace(/\,/g, '')" onblur="if(isnumber(this.value)==false) { this.value='0'; return false; } else { if (this.value == '') { this.value = '0'; } if (parseFloat(this.value) > document.formato.opcion_cuota_ccc.value.replace(/\,/g, '')) { this.value = document.formato.opcion_cuota_ccc.value.replace(/\,/g, ''); alert('El valor de la cuota no debe ser mayor a $' + document.formato.opcion_cuota_ccc.value); } recalcular(); separador_miles(this); }" style="height:30; text-align:right; font-size:16; font-weight:bold; color:#CC0000;  "></td>
                    <td colspan="2"><input type="text" id="opcion_desembolso_cso" name="opcion_desembolso_cso" value="" style="width:95%; height:30; text-align:right; font-size:16; font-weight:bold; " readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">FECHA INCORP. PAGADURÍA</td>
                    <td><input type="text" name="fecha_incorp_pagaduria" value="" onchange="if (this.value != '') { if(validarfecha(this.value)==false) {this.value=''; return false} }" style="height:30; text-align:right; font-size:16; font-weight:bold; color:#CC0000; width: 100px"></td>
                  </tr>
                  </tbody></table>
                  <br>
                  <h2>TE RECUPERAMOS</h2>
                  <div class="box1 clearfix">
                  <table border="0" cellspacing="1" cellpadding="2" width="100%">
                    <tbody><tr>
                      <td colspan="3">
                        <input type="checkbox" name="tipo_producto_checked" value="1" onchange="if (this.checked == true) { document.formato.descuento1.value = '0'; document.formato.tipo_producto.value = '1'; document.getElementById('descuento5').style.color = '#000000'; document.getElementById('descuento6').style.color = '#000000'; document.getElementById('descuento5_valor').style.color = '#000000'; document.getElementById('descuento6_valor').style.color = '#000000'; document.getElementById('descuento5').style.backgroundColor = '#EAF1DD'; document.getElementById('descuento6').style.backgroundColor = '#EAF1DD'; document.getElementById('descuento5').readOnly = false; document.getElementById('descuento6').readOnly = false; } else { document.formato.descuento1.value = '3.93'; document.formato.tipo_producto.value = '0'; document.getElementById('descuento5').style.color = '#FFFFFF'; document.getElementById('descuento6').style.color = '#FFFFFF'; document.getElementById('descuento5_valor').style.color = '#FFFFFF'; document.getElementById('descuento6_valor').style.color = '#FFFFFF'; document.getElementById('descuento5').style.backgroundColor = '#FFFFFF'; document.getElementById('descuento6').style.backgroundColor = '#FFFFFF'; document.getElementById('descuento5').readOnly = true; document.getElementById('descuento6').readOnly = true; } recalcular();" hidden="">
                        <input type="hidden" name="tipo_producto" value="">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">TOTAL CARTERAS A COMPRAR</td>
                      <td>&nbsp;</td>
                      <td colspan="2"><input type="text" id="total_carteras_comprar" name="total_carteras_comprar" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                    </tr>
                    <tr>
                      <td colspan="2">TIPO DE CLIENTE</td>
                      <td>&nbsp;</td>
                      <td>
                        <select id="descuento5" name="descuento5" onchange="document.formato.lb_porcentaje_costos.value = tipos_cliente[this.value];recalcular();" style=" color:#FFFFFF;" readonly="">
                          <option value="" hidden="" disabled="" selected="">Seleccione uno...</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="K">K</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">COSTOS</td>
                      <td><input type="text" id="lb_porcentaje_costos" name="lb_porcentaje_costos" value="" size="14" style="text-align:center; " onchange="if(isnumber_punto(this.value)==false) {this.value=''; return false} else { if (this.value == '') { this.value = ''; } recalcular(); }"></td>
                      <td colspan="2"><input type="text" id="descuento5_valor" name="descuento5_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                    </tr>
                    <tr>
                      <td colspan="2">IVA</td>
                      <td><input type="text" id="descuento6" name="descuento6" value="19" size="14" style="text-align:center; color:#FFFFFF;" readonly=""></td>
                      <td colspan="2"><input type="text" id="descuento6_valor" name="descuento6_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                    </tr>
                    <tr>
                      <td colspan="2">TRANSFERENCIA</td>
                      <td>&nbsp;</td>
                      <td colspan="2"><input type="text" name="descuento_transferencia_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                    </tr>
                  </tbody></table>
                  </div>

                  <br>
                  <h2>DESCUENTOS DESEMBOLSO</h2>
                  <div class="box1 oran clearfix">
                  <table border="0" cellspacing="1" cellpadding="2" width="100%">
                  <tbody><tr>
                    <th colspan="2">&nbsp;</th>
                    <th>%</th>
                    <th colspan="2">VALOR</th>
                  </tr>
                  <tr>
                    <td colspan="2">INTERESES ANTICIPADOS</td>
                    <td><input type="text" name="descuento1" value="3.93" size="14" style="text-align:center; color:#FFFFFF;"></td>
                    <td colspan="2"><input type="text" name="descuento1_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">ASESORÍA FINANCIERA</td>
                    <td><input type="text" name="descuento2" value="" onchange="if(isnumber_punto(this.value)==false) {this.value='35'; return false} else { if (this.value == '') { this.value = '35'; } recalcular(); }" size="14" style="text-align:center; "></td>
                    <td colspan="2"><input type="text" name="descuento2_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">IVA</td>
                    <td><input type="text" name="descuento3" value="19" size="14" style="text-align:center; color:#FFFFFF;" readonly=""></td>
                    <td colspan="2"><input type="text" name="descuento3_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">GMF</td>
                    <td><input type="text" name="descuento4" value="0.4" size="14" style="text-align:center; color:#FFFFFF;" readonly=""></td>
                    <td colspan="2"><input type="text" name="descuento4_valor" value="" style="width:95%; text-align:right; color:#FFFFFF;" readonly=""></td>
                  </tr>
                  </tbody></table>
                  </div>
                </td>
                <td width="20">&nbsp;</td>
                <td valign="top">
                  <br>
                  <div class="box1 clearfix">
                  <table border="0" cellspacing="1" cellpadding="2" width="95%">
                  <tbody><tr>
                    <td colspan="2">VALOR CRÉDITO</td>
                    <td colspan="3"><input type="text" name="valor_credito" value="" style="width:100%; text-align:right;" readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="font-size:18" width="170"><b>DESEMBOLSO CLIENTE</b></td>
                    <td colspan="3"><input type="text" id="desembolso_cliente" name="desembolso_cliente" value="" style="width:100%; height:45; text-align:right; font-size:18; font-weight:bold; " readonly=""></td>
                  </tr>
                  <tr><td colspan="5">&nbsp;</td></tr>
                  <tr>
                    <td colspan="2" style="font-size:16"><b>DECISIÓN</b></td>
                    <td colspan="3"><input type="text" id="decision" name="decision" value="" style="width:100%; height:45; text-align:center; font-size:18; font-weight:bold; " readonly=""><input type="hidden" id="decisionh" name="decisionh" value=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">NO. LIBRANZA</td>
                    <td colspan="3"><input type="text" name="nro_libranza" value="" style="width:100%; text-align:center; "></td>
                  </tr>
                  <tr>
                    <td colspan="2">VALOR VISADO</td>
                    <td colspan="3"><input type="text" name="valor_visado" value="" onfocus="this.value = this.value.replace(/\,/g, '')" onblur="if(isnumber(this.value)==false) {this.value='0'; return false} else { separador_miles(this); }" style="width:100%; text-align:right; "></td>
                  </tr>
                  <tr>
                    <td colspan="2">FECHA CONFIRMACIÓN</td>
                    <td colspan="3"><input type="text" name="fecha_llamada_clientef" value="" size="10" onchange="if(validarfecha(this.value)==false) {this.value=''; return false}" style=";">&nbsp;HORA<input type="text" name="fecha_llamada_clienteh" value="" size="5" onchange="if(validarhora(this.value)==false) {this.value=''; return false}" style=";"><select name="fecha_llamada_clientej" style=";"><option value=""></option><option value="AM">AM</option><option value="PM">PM</option></select></td>
                  </tr>
                  <tr>
                    <td colspan="2">SUBESTADO</td>
                    <td colspan="3">
                      <select id="id_subestado" name="id_subestado" style="">
                        <option value=""></option>
                      </select>
                      <input type="hidden" name="id_subestadoh" value="">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">EXTRA PRIMA</td>
                    <td colspan="3">
                      <select name="porcentaje_extraprima" style=";" onchange="if (this.value != '0' &amp;&amp; PorcentajeSeguroExtraPrima('', document.formato.plazo.value, this.value) == '0') { alert('No hay condiciones de Extra Prima para el plazo establecido'); this.value = document.formato.porcentaje_extraprimah.value; } else { recalcular(); }">
                        <option value="0"></option>
                      </select>
                      <input type="hidden" name="porcentaje_extraprimah" value="">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">PORCENTAJE SEGURO</td>
                    <td colspan="3"><input type="text" name="porcentaje_seguro" value="" style="width:100%; text-align:right;" readonly=""></td>
                  </tr>
                  <tr>
                    <td colspan="2">MONTO APROB. POR ALIADO</td>
                    <td colspan="3"><input onfocus="this.value = this.value.replace(/\,/g, '')" onblur="if (isnumber(this.value) == false) { this.value = '0'; return false } else { if (this.value == '') { this.value = '0'; } separador_miles(this); }" type="text" name="monto_aprob_aliado" value="0" style="width:100%; text-align:right;"></td>
                  </tr>

                  <tr>
                    <td>TASA DE INTERÉS DEL CRÉDITO</td>
                    <td width="70">
                      <select id="tasa_interes" class="form-control" name="tasa_interes" onchange="recalcular()">
                        <option value="" selected=""></option>
                        <option value="" selected="">2</option>
                        <option value="" selected="">1.9</option>
                        <option value="" selected="">1.8</option>
                        <option value="" selected="">1.7</option>
                        <option value="" selected="">1.6</option>
                        <option value="" selected="">1.4</option>
                        <option value="" selected="">1.3</option>
                      </select>
                      <input type="hidden" name="tasa_interesh" value="0">
                    </td>
                    <input type="hidden" name="tipo_credito" value="">
                    <td>PLAZO SOLICITADO PARA EL CRÉDITO</td>
                    <td>
                      <input type="text" id="plazo" name="plazo" value="" size="15" onchange="if(isnumber(this.value)==false) {this.value=''; return false} else { if (document.formato.porcentaje_extraprima.value != '0' &amp;&amp; PorcentajeSeguroExtraPrima('', this.value, document.formato.porcentaje_extraprima.value) == '0') { alert('No hay condiciones de Extra Prima para el plazo establecido'); this.value = document.formato.plazoh.value; } else { if (this.value == '') { this.value = ''; } if (parseInt(this.value) > parseInt(document.formato.plazo_maximo_segun_edad.value)) { this.value = document.formato.plazo_maximo_segun_edad.value; alert('El plazo no debe ser mayor a ' + document.formato.plazo_maximo_segun_edad.value);} CargarTasas(this.value); recalcular(); } }" style="text-align:center;  color:#CC0000;">
                      <input type="hidden" name="plazoh" value="">
                    </td>
                    <input type="hidden" name="suma_al_presupuesto" value="0">
                  </tr>
                  </tbody></table>
                  </div>
                  <br>
                  <h2>OBSERVACIONES</h2>
                  <div class="box1 oran clearfix">
                  <table border="0" cellspacing="1" cellpadding="2" width="95%">
                  <tbody><tr>
                    <td colspan="4"><textarea name="observaciones" rows="3" style="width:100%; "></textarea></td>
                  </tr>
                  </tbody></table>
                  </div>
                </td>
              </tr>

            </table>
          </div>
        </div>
      </div>

      <div id="panel-aliados" class="col-md-12 panel-aliados hidden">
        <div class="panel panel-primary">
          <div class="panel-heading"><b>Condiciones Entidades Financieras</b></div>
          <div class="panel-body">
            <div class="row">
              <div id="panel-AF1" class="col-md text-center hidden">
                <h4><b>Entidad 1</b></h4>
                <div class="row justify-content-center">
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Entidad</label>
                      </div>
                      <div class="col-md">
                        <select class="form-control" name="AF1[id]" id="aliadof1">
                          <option disabled value="">Seleccione uno...</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Carteras a comprar</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="carteras_a_comprar_af1" id="carteras_a_comprar_af1" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Cupo máx.</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right font-weight-bold" type="text" name="AF1_cupo_max" id="AF1_cupo_max" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10" id="item-cuota-mensual">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Cuota mensual</label>
                      </div>
                      <div class="col-md">
                        <input class="auto form-control text-right" data-a-sep="." data-a-dec="," data-a-sign="$ " type="text" name="AF1[cuota_mensual]" id="AF1_cuota_mensual" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Tasa %</label>
                      </div>
                      <div class="col-md">
                        <select class="form-control" name="AF1[tasa]" id="AF1_tasa">
                          <option value="">Seleccione uno...</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Plazo</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="AF1[plazo]" id="AF1_plazo" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10" id="item-saldo-ref">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Saldo de Refinanciación</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="AF1[saldo_refinanciacion]" id="AF1_saldo_refinanciacion" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Intereses anticipados (%)</label>
                      </div>
                      <div class="col-md-3">
                        <input class="form-control" type="text" name="AF1[intereses_anticipados]" id="AF1_intereses_anticipados" value="2000">
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="AF1[intereses_anticipados_valor]" id="AF1_intereses_anticipados_valor" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Gastos Administrativos (%)</label>
                      </div>
                      <div class="col-md-3">
                        <select class="form-control" name="AF1[costos]" id="AF1_costos">
                          <option value="">Seleccione uno...</option>
                        </select>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="AF1[costos_valor]" id="AF1_costos_valor" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">Seguro</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="AF1_seguro" id="AF1_seguro" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">GMF</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="AF1_GMF" id="AF1_GMF" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-5 text-right">
                        <label class="label-consulta" for="pad">IVA</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control text-right" type="text" name="AF1_iva" id="AF1_iva" value="" disabled>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <label class="label-consulta" for="pad">Total Crédito</label>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control text-center font-weight-bold" type="text" name="AF1_valor_credito" id="AF1_valor_credito" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="row">
                      <div class="col-md-12 text-center" id="item-cuota-seguro">
                        <label class="label-consulta" for="pad">Cuota + Seguro</label>
                      </div>
                      <div class="col-md-12 text-center" id="item-desembolso-cliente">
                        <label class="label-consulta" for="pad">Desembolso al Cliente</label>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control text-center font-weight-bold" type="text" name="AF1_cuota" id="AF1_cuota" value="" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="panel-AF2" class="col-md text-center hidden">
                <h4><b>Entidad 2</b></h4>
                <div class="row justify-content-center">
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Entidad</label>
                      </div>
                      <div class="col-md">
                        <select class="form-control" name="AF2[id]" id="aliadof2">
                          <option disabled value="">Seleccione uno...</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Carteras a comprar</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="carteras_a_comprar_af2" id="carteras_a_comprar_af2" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Factor x millón <span class="hidden" id="label-saneamiento">(saneamiento)</span></label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="AF2[factor_x_millon]" id="AF2_factor_x_millon" value="0" readonly="readonly">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Plazo</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="AF2[plazo]" id="AF2_plazo" value="120">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Cupo Máximo</label>
                      </div>
                      <div class="col-md">
                        <input class="form-control" type="text" name="AF2_cupo_max" id="AF2_cupo_max" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md text-right">
                        <label class="label-consulta" for="pad">Cuota</label>
                      </div>
                      <div class="col-md">
                        <input class="auto form-control" data-a-sep="." data-a-dec="," data-a-sign="$ " type="text" name="AF2[cuota]" id="AF2_cuota" value="0">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <label class="label-consulta" for="pad">Monto a prestar</label>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control text-center font-weight-bold" type="text" name="AF2_monto_prestar" id="AF2_monto_prestar" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <label class="label-consulta" for="pad">Monto Máx.</label>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control text-center font-weight-bold" type="text" name="AF2_monto_max" id="AF2_monto_max" value="" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <label class="label-consulta" for="pad">Saldo al cliente</label>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control text-center font-weight-bold" type="text" name="AF2_saldo" id="AF2_saldo" value="" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modalCartera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ route('estudios.cartera') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Cartera</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="{{ ($dataCotizer->estudio != null) ? $dataCotizer->estudio->id : null }}" name="estudios_id">

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Sector Data</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" name="sector_data" id="" required>
                                <option selected disabled value="">--Seleccione--</option>
                                @foreach($sectores as $sector)
                                  <option value="{{ $sector->id }}">{{ $sector->sector }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Sector CIFIN</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" name="sector_cifin" id="" required>
                                <option selected disabled value="">--Seleccione--</option>
                                @foreach($sectores as $sector)
                                  <option value="{{ $sector->id }}">{{ $sector->sector }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Estado de la Cartera</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" name="estadoscarteras_id" id="" required>
                                <option selected disabled value="">--Seleccione--</option>
                                @foreach($estadoscartera as $estado)
                                  <option value="{{ $estado->id }}">{{ $estado->estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Nombre</label>
                        </div>
                        <div class="col-md-9">
                          <input type="text" name="nombre_obligacion" class="form-control" required>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Cuota</label>
                        </div>
                        <div class="col-md-9">
                            <input type="number" min="0" name="cuota" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Saldo</label>
                        </div>
                        <div class="col-md-9">
                          <input type="number" min="0" name="saldo" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Valor Inicial</label>
                        </div>
                        <div class="col-md-9">
                          <input type="number" min="0" name="valor_ini" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">* Fecha de Vencimiento</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" name="fecha_vence" required>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{asset('js/autoNumeric.js')}}"></script>
<script src="{{asset('css/gijgo-combined-1.9.13/js/gijgo.min.js')}}"></script>
<script src="{{asset('js/TablaCarteras.js')}}"></script>
<script src="{{asset('js/init_autoNumeric.js')}}"></script>

<script>

  var comprarCartera = (id) => {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/estudios/comprar-cartera',
            data: {
                cartera_id: id,
            },
            success: function(data) {
              if(data.data){
                $("#td-compra-"+id).html("<span>Comprada</span>");
              }
            },
          
            error: function(data) {
              console.log(data);
            }
        });
  };
    
</script>
@endsection