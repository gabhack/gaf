@extends('layouts.app2')

@section('js')

@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Forma de Pago</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('formapago')}}">Lista de Forma de Pago</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Forma de Pago</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('formapago/crear')}}">Crear Forma de Pago</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Forma de Pago</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Forma de Pago</div>
			<div class="panel-body">
				<form action="{{url('formapago/update', ['id' => $forma->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" name="nombre" id="nombre" value="{{ $forma->nombre }}" required >
						</div>
					</div>	
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
