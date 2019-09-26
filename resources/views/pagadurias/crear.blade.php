@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Pagadurias</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('pagadurias')}}">Lista de Pagadurias</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Pagaduria</a>
			</li>
		</ul>
		<br />
		<h4>Crear Pagadurias</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Pagadurias</div>
			<div class="panel-body">
				<form action="{{url('pagadurias/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="pagaduria">Pagaduria</label>
							<input type="text" class="form-control" name="pagaduria" id="pagaduria" placeholder="Pagaduria">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
