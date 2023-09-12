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
              @if(count($sectorFinanciero)>0)
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
              </tbody>
              </tfoot>
            </table>

            <button type="button" id="btnAgregarFila" class="btn btn-primary">Agregar cartera</button><br><br>
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

@endsection

@section('js')
<script src="{{asset('js/autoNumeric.js')}}"></script>
<script src="{{asset('css/gijgo-combined-1.9.13/js/gijgo.min.js')}}"></script>
<script src="{{asset('js/TablaCarteras.js')}}"></script>
<script src="{{asset('js/init_autoNumeric.js')}}"></script>
@endsection