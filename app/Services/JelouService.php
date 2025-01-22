<?php

namespace App\Services;

use App\{BaseReporteBiometria, ReporteBiometria, InformacionUsuario};

class JelouService
{
    public function createBaseReporteBiometria($request)
    {
        $baseReporteBiometria = new BaseReporteBiometria();
        $baseReporteBiometria->codigo_biometria = $request->codigo_biometria;
        $baseReporteBiometria->tipo_biometria = $request->tipo_biometria;
        $baseReporteBiometria->resultado_biometria = $request->resultado_biometria;
        $baseReporteBiometria->celular = $request->celular;
        $baseReporteBiometria->nacionalidad = $request->nacionalidad;
        $baseReporteBiometria->numero_identidad = $request->numero_identidad;
        $baseReporteBiometria->nombres = $request->nombres;
        $baseReporteBiometria->apellidos = $request->apellidos;
        $baseReporteBiometria->fecha_nacimiento = $request->fecha_nacimiento;
        $baseReporteBiometria->liveness_check = $request->liveness_check;
        $baseReporteBiometria->video_selfie = $request->video_selfie;
        $baseReporteBiometria->foto_video_selfie = $request->foto_video_selfie;
        $baseReporteBiometria->document_check = $request->document_check;
        $baseReporteBiometria->tipo_documento = $request->tipo_documento;
        $baseReporteBiometria->foto_documento = $request->foto_documento;
        $baseReporteBiometria->resultado_facematch = $request->resultado_facematch;
        $baseReporteBiometria->porcentaje_similitud_facematch = $request->porcentaje_similitud_facematch;
        $baseReporteBiometria->reporte_biometria = $request->reporte_biometria;
        $baseReporteBiometria->apto_para_auth = $request->apto_para_auth;
        $baseReporteBiometria->save();

        return $baseReporteBiometria;
    }

    public function createReporteBiometria($request)
    {
        $reporteBiometria = new ReporteBiometria();
        $reporteBiometria->codigo_biometria = $request->codigo_biometria;
        $reporteBiometria->fecha_hora = $request->fecha_hora;
        $reporteBiometria->resultado_biometria = $request->resultado_biometria;
        $reporteBiometria->numero_identificacion = $request->numero_identificacion;
        $reporteBiometria->tipo_identificacion = $request->tipo_identificacion;
        $reporteBiometria->nacionalidad = $request->nacionalidad;
        $reporteBiometria->nombres = $request->nombres;
        $reporteBiometria->apellidos = $request->apellidos;
        $reporteBiometria->celular = $request->celular;
        $reporteBiometria->email = $request->email;
        $reporteBiometria->direccion = $request->direccion;
        $reporteBiometria->prueba_vida = $request->prueba_vida;
        $reporteBiometria->video_selfie = $request->video_selfie;
        $reporteBiometria->foto_de_rostro = $request->foto_de_rostro;
        $reporteBiometria->focface_match = $request->focface_match;
        $reporteBiometria->foto_de_documento_delantera = $request->foto_de_documento_delantera;
        $reporteBiometria->foto_de_documento_posterior = $request->foto_de_documento_posterior;
        $reporteBiometria->foto_rostro_en_documento = $request->foto_rostro_en_documento;
        $reporteBiometria->porcentaje_similitud_facial_doc = $request->porcentaje_similitud_facial_doc;
        $reporteBiometria->govfacematch = $request->govfacematch;
        $reporteBiometria->fuente = $request->fuente;
        $reporteBiometria->porcentaje_similitud_facial_gov = $request->porcentaje_similitud_facial_gov;
        $reporteBiometria->gov_data_check = $request->gov_data_check;
        $reporteBiometria->fuente_verificacion = $request->fuente_verificacion;
        $reporteBiometria->other_data_check = $request->other_data_check;
        $reporteBiometria->otra_fuente_verificacion = $request->otra_fuente_verificacion;
        $reporteBiometria->reporte_biometria = $request->reporte_biometria;
        $reporteBiometria->usuario_id_postgrest = $request->usuario_id_postgrest;
        $reporteBiometria->usuario_id_mysql = $request->usuario_id_mysql;
        $reporteBiometria->save();

        return $reporteBiometria;
    }

    public function createInformacionUsuario($request)
    {
        $informacionUsuario = new InformacionUsuario();
        $informacionUsuario->telefono = $request->telefono;
        $informacionUsuario->aceptacion_tc = $request->aceptacion_tc;
        $informacionUsuario->cedula = $request->cedula;
        $informacionUsuario->nombre = $request->nombre;
        $informacionUsuario->oferta_seleccionada = $request->oferta_seleccionada;
        //$informacionUsuario->usuario_id_postgre = $request->usuario_id_postgre;
        //$informacionUsuario->usuario_id_mysql = $request->usuario_id_mysql;
        $informacionUsuario->save();

        return $informacionUsuario;
    }
}
