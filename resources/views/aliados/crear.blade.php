@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Aliados</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('aliados')}}">Lista de Aliados</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Aliado</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('aliados/parametrizar')}}">Parametrizar Aliados</a>
			</li>
		</ul>
		<br />
		<h4>Crear Aliados</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Aliados</div>
			<div class="panel-body">
				<form action="{{url('aliados/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="aliado">Aliado</label>
							<input type="text" class="form-control" name="aliado" id="aliado" placeholder="Aliado">
						</div>
						<div class="form-group col-md-6">
							<label for="plazo">PLazo M&aacute;ximo</label>
							<input type="number" class="form-control" name="plazo" id="plazo" placeholder="PLazo M&aacute;ximo">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="estado">Estado</label>
							<select class="form-control" name="estado" id="estado">
								<option value="">-Seleccione-</option>
								@foreach(estados_aliados() as $key => $val)
									<option value="{{$key}}">{{$val}}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
