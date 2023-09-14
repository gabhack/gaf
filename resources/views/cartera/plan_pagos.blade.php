@extends('layouts.app2')

@section('title')
HEGO | Plan de Pagos
@endsection

@section('header-content')
Plan de Pagos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Plan de Pagos</li>
@endsection

@section('content')

<div class="container-fluid" style="display: flex; justify-content: center;">
    <div class="row">

        <div class="col-md-12">
            <div class="btn-group mr-2 float-right" role="group">
                <a type="button" class="btn btn-secondary" href="{{url('estudios')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
            </div>
        </div>

        <form>
            <table border="0" cellspacing="1" cellpadding="2" class="table table-striped" style="margin-top: 30px;">
                <thead>
                    <tr>
                        <th>No. Cuota</th>
                        <th>Fecha</th>
                        <th>Capital</th>
                        <th>Interés</th>
                        <th>Seguro de Vida</th>
                        <th>Total Cuota</th>
                        <th>Saldo de Capital</th>
                    </tr>
                </thead>
                </tbody>
                    <tr>
                        <td>1</td>
                        <td>01/01/2023</td>
                        <td>124456</td>
                        <td>124124</td>
                        <td>124124124</td>
                        <td>5</td>
                        <td>300000</td>
                    </tr>
                </tbody>
               
            </table>
            <br>
            </form>

        
    </div>
</div>
@endsection

@section('css')

    <style>
        .tablee, .tablee thead, .tablee thead tr th, .tablee tbody tr td{
            border: 1px solid black;
        }

        .tablee tbody{
            border: 1px solid black;
            font-size: smaller;
        }

    </style>
@endsection