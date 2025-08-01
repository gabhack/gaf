@extends('layouts.app')

@section('content')
	<div class="col-md-6 col-md-offset-3">
		
		<div class="panel panel-default">
			<div class="panel-heading">Generar dataset</div>
			<div class="panel-body">
				<form action="{{url('dataset/get')}}" method="get" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row" id="panel-pagaduria">
                        <div class="form-group-col-md-2">
                            <button type="submit" class="btn btn-primary">Generar</button>
                        </div>
					</div>
					
				</form>
			</div>
		</div>
	</div>

@endsection
