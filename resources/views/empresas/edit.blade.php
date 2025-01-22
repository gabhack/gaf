@extends('layouts.app2')

@section('content')
  <editar-empresas :empresa="{{ $empresa }}" :representante-legal="{{ $representanteLegal }}"
    :documento-empresa="{{ $documentoEmpresa }}"></editar-empresas>
@endsection
