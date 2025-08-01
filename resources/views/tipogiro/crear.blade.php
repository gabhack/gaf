@extends('layouts.app2')

@section('js')

@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Tipo de Giro</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('tipogiro')}}">Lista de Tipo de Giro</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Tipo de Giro</a>
			</li>
		</ul>
		<br />
		<h4>Crear Tipo de Giro</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Tipo de Giro</div>
			<div class="panel-body">
				<form action="{{url('tipogiro/store')}}" method="post">
					{!! Form::token() !!}						
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" name="nombre" id="nombre" required >
						</div>
					</div>	
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
