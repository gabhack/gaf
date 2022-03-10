@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Carga de archivo PDF</h2>
		
		<div class="panel panel-default">
			<div class="panel-heading">Carga de archivo</div>
			<div class="panel-body">
				<div role="tabpanel">
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="carga-masiva">
							
							<form action="{{ url('/pdfConvert') }}" enctype="multipart/form-data" method="POST">
								@csrf
								<div class="mb-2"> <span>Attachments</span>
									<div
										class="relative h-40 rounded-lg border-dashed border-2 border-gray-200 bg-white flex justify-center items-center hover:cursor-pointer">
										<div class="absolute">
											<div class="flex flex-col items-center "> 
												<i class="fa fa-cloud-upload fa-3x text-gray-200"></i>
												<span class="block text-gray-400 font-normal">
													Attach you files here
												</span> 
												<span class="block text-gray-400 font-normal">or</span>
												<span class="block text-blue-400 font-normal">Browse files</span>
											</div>
										</div>
										<input type="file" class="h-full w-full opacity-0" name="file" >
									</div>
								</div>
								<div class="mt-3 text-center pb-3">
									<button type="submit" class="w-full h-12 text-lg w-32 bg-blue-600 rounded text-white hover:bg-blue-700">
										Descargar
									</button>
								</div>
							</form>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
