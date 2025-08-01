@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Factores X Millon Kredit</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('factorxmillonkredit')}}">Lista de Factores X Millon Kredit</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Parametro</a>
			</li>
			<!--
			<li class="nav-item">
				<a class="nav-link" href="{{url('factorxmillonkredit/crear')}}">Crear Factores X Millon Kredit</a>
			</li>			
			-->
		</ul>
		<br />		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Factores X Millon Kredit</div>
			<div class="panel-body">
				<form action="{{url('factorxmillonkredit/update', ['id' => $factorxmillonkredit->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="valor">{{$factorxmillonkredit->llave}}</label>
							<input type="text" class="form-control" name="valor" id="valor" placeholder="Valor" value="{{$factorxmillonkredit->valor}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
