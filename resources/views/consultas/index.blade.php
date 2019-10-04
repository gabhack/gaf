@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<div class="panel panel-default">
			<div class="panel-heading">Consultar persona</div>
			<div class="panel-body">
				<form action="{{url('consultas/consultar')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row" id="panel-pagaduria">
						<div class="form-group col-md-4">
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento">
                        </div>
                        <div class="form-group-col-md-2">
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </div>
					</div>
					
				</form>
			</div>
		</div>
	</div>

@endsection
