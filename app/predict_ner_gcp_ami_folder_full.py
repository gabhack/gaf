# Copyright 2020 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
import os
import sys
"""Predict."""
# [START automl_language_entity_extraction_predict]
from google.cloud import automl
from google.api_core.client_options import ClientOptions
from google.cloud import automl_v1
from google.cloud.automl_v1.proto import service_pb2
from google.cloud import storage
import json
import re
import argparse

ap = argparse.ArgumentParser()
ap.add_argument("-b", "--bucket", type=str,
	help="path to input pdf")
ap.add_argument("-f", "--folder", type=str,
	help="path folder pdfs in gcp")
args = vars(ap.parse_args())

# Servidor:
# os.environ["GOOGLE_APPLICATION_CREDENTIALS"]="/var/www/html/ami/app/credentials.json"

# Entorno local de desarrollo Linux:
os.environ["GOOGLE_APPLICATION_CREDENTIALS"]="/opt/lampp/htdocs/ami/credentials.json"

project_id = "warm-helix-277015"

# En caso que el modelo cambie o se re-entrene, este valor de model name se debe actualizar
model_name = ""


def my_min_function(somelist):
    min_value = min(somelist)
    return min_value

def list_blobs_with_prefix(bucket_name, prefix, delimiter=None):

    storage_client = storage.Client()

    # Note: Client.list_blobs requires at least package version 1.17.0.
    blobs = storage_client.list_blobs(
        bucket_name, prefix=prefix, delimiter=delimiter
    )
    
    
    
    #print("Blobs:")
    for blob in blobs:
        #print(blob.name)
        gcs_url_file=blob.name
        if gcs_url_file.endswith(".pdf"):
            #print(gcs_url_file)
            files_names.append(gcs_url_file)

def pdf_payload(file_path):
  return {'document': {'input_config': {'gcs_source': {'input_uris': [file_path] } } } }

def get_prediction(file_path, model_name):
  options = ClientOptions(api_endpoint='automl.googleapis.com')
  prediction_client = automl_v1.PredictionServiceClient(client_options=options)
  # Get the full path of the model.
  model_full_id = prediction_client.model_path(
      project_id, "us-central1", model_name)

  #payload = inline_text_payload(file_path)
  # Uncomment the following line (and comment the above line) if want to predict on PDFs.
  payload = pdf_payload(file_path)
  
  response = prediction_client.predict(model_full_id, payload)
  
  
  
  Secretaria_Educacion=""
  Nit_Pagaduria=""
  nombres=""
  apellidos=""
  documento=""
  grado=""
  tipo_contratacion=""
  ciudad=""
  centro_costos=""
  cargo_docente=""
  cargo_administrativo=""
  periodo=""
  Dias_Laborados=""
  Banco=""
  Cuenta=""
  pension=""
  Caja_CF=""
  cesantias=""
  
  # CONCEPTOS 
  ingresos_base=""
  cod_concepto_ingreso=""
  concepto_ingreso=""
  ingresos=""
  ingresos_totales=""
  cod_concepto_egreso=""
  concepto_egreso=""
  egresos=""
  egresos_totales=""
  Neto_A_Pagar=""
  
  
  for annotation_payload in response.payload:
      
        """  
        print(
            "Text Extract Entity Types: {}".format(
                annotation_payload.display_name
            )
        )
        print(
            "Text Score: {}".format(annotation_payload.text_extraction.score)
        )
        text_segment = annotation_payload.text_extraction.text_segment
        print("Text Extract Entity Content: {}".format(text_segment.content))
        print("Text Start Offset: {}".format(text_segment.start_offset))
        print("Text End Offset: {}".format(text_segment.end_offset))
        """ 
        text_segment = annotation_payload.text_extraction.text_segment
        entidad=annotation_payload.display_name
        contenido_entidad=text_segment.content
        
        #print("Entidad: "+entidad+" Contenido: "+contenido_entidad)
        
        my_details = {
          'entidad': entidad,
          'contenido': contenido_entidad
        }
        ner_array.append(my_details)
        #print(ner_array)
        entidad_detectada=entidad
        contenido_entidad_detectada=contenido_entidad
        
        
        
        
        ######################################################
        
        if entidad_detectada=="pagaduria":
            
            if Secretaria_Educacion == "":

                Secretaria_Educacion = contenido_entidad_detectada
            
        
        elif entidad_detectada=="nit_pagaduria":
            
            if Nit_Pagaduria == "":

                Nit_Pagaduria = contenido_entidad_detectada
          
        elif entidad_detectada=="nombres":
            
            if nombres == "":
   
                nombres=contenido_entidad_detectada
            
        elif entidad_detectada=="apellidos":
            
            if apellidos == "":
                
               apellidos=contenido_entidad_detectada
           
        elif entidad_detectada=="cedula":
            
            if documento == "":

               documento=contenido_entidad_detectada
           
        elif entidad_detectada=="ciudad":
           
            if ciudad == "":

               ciudad=contenido_entidad_detectada
           
        elif entidad_detectada=="centro_costos":
            
            if centro_costos == "":

               centro_costos= contenido_entidad_detectada
    
    
        elif entidad_detectada=="tipo_contratacion":
            
            if tipo_contratacion == "":

                tipo_contratacion=contenido_entidad_detectada
           
        elif entidad_detectada=="grado":
            
            if grado == "":

                grado=contenido_entidad_detectada
                
        elif entidad_detectada=="cargo_docente":
            
            if cargo_docente == "":

               cargo_docente = contenido_entidad_detectada
           
        elif entidad_detectada=="cargo_administrativo":
            
            if cargo_administrativo == "":

               cargo_administrativo = contenido_entidad_detectada
    
           
        elif entidad_detectada=="periodo_pago":
            
            if periodo=="":

                periodo=contenido_entidad_detectada
                periodo_pago = "".join(re.split("[^a-zA-Z0-9]*", periodo))
                periodo=periodo_pago
                
                
                if periodo_pago.find("Jan") !=-1:
                    periodo_pago_v2= periodo_pago.split("Jan")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "01"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Feb") !=-1:
                    periodo_pago_v2= periodo_pago.split("Feb")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "02"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Mar") !=-1:
                    periodo_pago_v2= periodo_pago.split("Mar")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "03"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Apr") !=-1:
                    periodo_pago_v2= periodo_pago.split("Apr")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "04"
                    periodo = periodo_pago_be
                elif periodo_pago.find("May") !=-1:
                    periodo_pago_v2= periodo_pago.split("May")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "05"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Jun") !=-1:
                    periodo_pago_v2= periodo_pago.split("Jun")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "06"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Jul") !=-1:
                    periodo_pago_v2= periodo_pago.split("Jul")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "07"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Aug") !=-1:
                    periodo_pago_v2= periodo_pago.split("Aug")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "08"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Sep") !=-1:
                    periodo_pago_v2= periodo_pago.split("Sep")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "09"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Oct") !=-1:
                    periodo_pago_v2= periodo_pago.split("Oct")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "10"
                    periodo = periodo_pago_be
                elif periodo_pago.find("Nov") !=-1:
                    periodo_pago_v2= periodo_pago.split("Nov")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "11"
                    periodo = periodo_pago_be  
                elif periodo_pago.find("Dic") !=-1:
                    periodo_pago_v2= periodo_pago.split("Dic")
                    year = periodo_pago_v2[len(periodo_pago_v2)-1]
                    periodo_pago_be = "20" + year + "12"
                    periodo = periodo_pago_be
                
                
        elif entidad_detectada=="dias_laborados":
            
            if Dias_Laborados == "":

               Dias_Laborados = contenido_entidad_detectada
           
        elif entidad_detectada=="entidad_financiera":
            
            if Banco == "":

               Banco = contenido_entidad_detectada
               
           
        elif entidad_detectada=="cuenta_bancaria":
            
            if Cuenta == "":

                Cuenta = contenido_entidad_detectada
           
        elif entidad_detectada=="pension":
            
            if pension == "":

               pension = contenido_entidad_detectada
           
        elif entidad_detectada=="caja_compensacion":
            
            if Caja_CF == "":

               Caja_CF = contenido_entidad_detectada
           
        elif entidad_detectada=="cesantias":
            
            if cesantias == "":

               cesantias = contenido_entidad_detectada
               
               
               
        elif entidad_detectada=="ingreso_base":
            
            if ingresos_base == "":

               ingresos_base=contenido_entidad_detectada
               ingresos_base_filtro_1=ingresos_base.replace('.00', '')
               ingresos_base_filtro_2=ingresos_base_filtro_1.replace(",", '')
               ingresos_base_filtro_3=ingresos_base_filtro_2.replace("$", '')
               ingresos_base= ingresos_base_filtro_3
           
        elif entidad_detectada=="cod_concepto_ingreso":

           
           cod_concepto_ingreso = contenido_entidad_detectada
           ner_cod_conceptos_ingreso.append(cod_concepto_ingreso)
           
           
           
        elif entidad_detectada=="concepto_ingreso":
           

           
           concepto_ingreso = contenido_entidad_detectada
           concepto_ingreso_filtro_1=concepto_ingreso.replace('\n', ' ')
           concepto_ingreso_filtro_2=concepto_ingreso_filtro_1.replace('/', ' ') 
           concepto_ingreso_filtro_3=concepto_ingreso_filtro_2.replace('/n', ' ')  
           concepto_ingreso=concepto_ingreso_filtro_3
           ner_conceptos_ingreso.append(concepto_ingreso)
           
           
           
        elif entidad_detectada=="valor_ingreso":
           
           
           
           ingresos = contenido_entidad_detectada
           ingresos_filtro_1=ingresos.replace('.00', '')
           ingresos_filtro_2=ingresos_filtro_1.replace(",", '')
           ingresos_filtro_3=ingresos_filtro_2.replace("$", '')
           ingresos= ingresos_filtro_3
           ner_conceptos_valor_ingreso.append(ingresos)
           
        elif entidad_detectada=="total_ingresos":
            
            if ingresos_totales == "":
    
               ingresos_totales = contenido_entidad_detectada
               ingresos_totales_filtro_1=ingresos_totales.replace('.00', '')
               ingresos_totales_filtro_2=ingresos_totales_filtro_1.replace(",", '')
               ingresos_totales_filtro_3=ingresos_totales_filtro_2.replace("$", '')
               ingresos_totales = ingresos_totales_filtro_3
           
        elif entidad_detectada=="cod_concepto_egreso":
           
       
           cod_concepto_egreso = contenido_entidad_detectada
           ner_cod_conceptos_egreso.append(cod_concepto_egreso)
           
           
        elif entidad_detectada=="concepto_egreso":
           

           concepto_egreso = contenido_entidad_detectada
           concepto_egreso_filtro_1=concepto_egreso.replace('\n', ' ')
           concepto_egreso_filtro_2=concepto_egreso_filtro_1.replace('/', ' ') 
           concepto_egreso_filtro_3=concepto_egreso_filtro_2.replace('/n', ' ')  
           concepto_egreso=concepto_egreso_filtro_3
           ner_conceptos_egreso.append(concepto_egreso)
           
        elif entidad_detectada=="valor_egreso":

           egresos = contenido_entidad_detectada
           egresos_filtro_1=egresos.replace('.00', '')
           egresos_filtro_2=egresos_filtro_1.replace(",", '')
           egresos_filtro_3=egresos_filtro_2.replace("$", '')
           egresos= egresos_filtro_3
           ner_conceptos_valor_egreso.append(egresos)
           
        elif entidad_detectada=="total_egresos":
            
            if egresos_totales == "":
    
               egresos_totales = contenido_entidad_detectada
               egresos_totales_filtro_1=egresos_totales.replace('.00', '')
               egresos_totales_filtro_2=egresos_totales_filtro_1.replace(",", '')
               egresos_totales_filtro_3=egresos_totales_filtro_2.replace("$", '')
               egresos_totales = egresos_totales_filtro_3

          
        elif entidad_detectada=="neto_a_pagar":
            
            if Neto_A_Pagar == "":

               Neto_A_Pagar = contenido_entidad_detectada
               Neto_A_Pagar_filtro_1=Neto_A_Pagar.replace('.00', '')
               Neto_A_Pagar_filtro_2=Neto_A_Pagar_filtro_1.replace(",", '')
               Neto_A_Pagar_filtro_3=Neto_A_Pagar_filtro_2.replace("$", '')
               Neto_A_Pagar = Neto_A_Pagar_filtro_3
               
    

        
        
  size_valores_ingresos= len(ner_conceptos_valor_ingreso)
  size_conceptos_ingresos= len(ner_conceptos_ingreso)
  size_cod_conceptos_ingresos= len(ner_cod_conceptos_ingreso)
      
  sizes_ingresos = [size_valores_ingresos, size_conceptos_ingresos, size_cod_conceptos_ingresos]

  
  #print(sizes_ingresos)
  
  
      
  valor_minimo_ingresos = my_min_function(sizes_ingresos)
  
  if valor_minimo_ingresos !=0:
      
      #print(valor_minimo_ingresos)
      
      if size_valores_ingresos == 0:
          ner_conceptos_valor_ingreso.append("$0.00")
          
          size_valores_ingresos=len(ner_conceptos_valor_ingreso)
          
          sizes_ingresos = [size_valores_ingresos, size_conceptos_ingresos, size_cod_conceptos_ingresos]
          valor_minimo_ingresos = my_min_function(sizes_ingresos)
          
      for i in range(valor_minimo_ingresos):
    
            
            codigo_concepto_ingreso=ner_cod_conceptos_ingreso[i]
            conceptos_ingresos=ner_conceptos_ingreso[i]
            valor_concepto_ingreso=ner_conceptos_valor_ingreso[i]
            
            valor_concepto_ingreso_filtro_1=valor_concepto_ingreso.replace('.00', '')
            valor_concepto_ingreso_filtro_2=valor_concepto_ingreso_filtro_1.replace(",", '')
            valor_concepto_ingreso_filtro_3=valor_concepto_ingreso_filtro_2.replace("$", '')
            
            my_details_todo_conceptos_detallados_ingresos = {
            
                'codConcepto': codigo_concepto_ingreso,
                'concepto': conceptos_ingresos,
                'valor': valor_concepto_ingreso_filtro_3
    
            }
            
            ner_resultado_provisional_conceptos_detallado_ingresos.append(my_details_todo_conceptos_detallados_ingresos)
  else:
      
        
        my_details_todo_conceptos_detallados_ingresos = {
           
           'codConcepto': "NaN",
           'concepto': "NaN",
           'valor': "NaN"
           
           }
        
        ner_resultado_provisional_conceptos_detallado_ingresos.append(my_details_todo_conceptos_detallados_ingresos)
        
          
          
  size_valores_egresos= len(ner_conceptos_valor_egreso)
  size_conceptos_egresos= len(ner_conceptos_egreso)
  size_cod_conceptos_egresos= len(ner_cod_conceptos_egreso)

  sizes_egresos = [size_valores_egresos, size_conceptos_egresos, size_cod_conceptos_egresos]
  
  #print(sizes_egresos)
  
  valor_minimo_egresos = my_min_function(sizes_egresos)
  
  
  if valor_minimo_egresos !=0:

      #print(valor_minimo_egresos)
      
      if size_valores_egresos == 0:
          ner_conceptos_valor_egreso.append("$0.00")
          
          size_valores_egresos=len(ner_conceptos_valor_egreso)
          
          sizes_egresos = [size_valores_egresos, size_conceptos_egresos, size_cod_conceptos_egresos]
          valor_minimo_egresos = my_min_function(sizes_egresos)
      
    
      for i in range(valor_minimo_egresos):
          
          
            
          
          codigo_concepto_egreso=ner_cod_conceptos_egreso[i]
                #print(codigo_concepto_egreso)
          
          conceptos_egresos=ner_conceptos_egreso[i]
                #print(conceptos_egresos)
         
            #print("ner conceptos valor egreso", ner_conceptos_valor_egreso)
          valor_concepto_egreso=ner_conceptos_valor_egreso[i]
            #print(valor_concepto_egreso)
            
                
            
          valor_concepto_egreso_filtro_1=valor_concepto_egreso.replace('.00', '')
          valor_concepto_egreso_filtro_2=valor_concepto_egreso_filtro_1.replace(",", '')
          valor_concepto_egreso_filtro_3=valor_concepto_egreso_filtro_2.replace("$", '')
        
        
        
          my_details_todo_conceptos_detallados_egresos = {
            
                'codConcepto': codigo_concepto_egreso,
                'concepto': conceptos_egresos,
                'valor': valor_concepto_egreso_filtro_3
        
            }
        
          ner_resultado_provisional_conceptos_detallado_egresos.append(my_details_todo_conceptos_detallados_egresos)
          
  
  else:
          my_details_todo_conceptos_detallados_egresos = {
            
                'codConcepto': "NaN",
                'concepto': "NaN",
                'valor': "NaN"
        
            }
        
          ner_resultado_provisional_conceptos_detallado_egresos.append(my_details_todo_conceptos_detallados_egresos)
              
        
  my_details_todo_conceptos_especificos = {
      
      
      'ingresos': ner_resultado_provisional_conceptos_detallado_ingresos,
      'egresos': ner_resultado_provisional_conceptos_detallado_egresos

      }
        
  my_details_todo_conceptos = {
        
        'ingresos_base': ingresos_base,
        'ingresos_totales':ingresos_totales,
        'egresos_totales': egresos_totales,
        'Neto_A_Pagar': Neto_A_Pagar,
        'detallado_conceptos': my_details_todo_conceptos_especificos
        }
        
       
  ner_resultado_provisional_conceptos_individuales.append(my_details_todo_conceptos)
  
  my_details_todo = {
      
        'Secretaria_Educacion': Secretaria_Educacion,
        'Nit_Pagaduria': Nit_Pagaduria,
        'nombres': nombres,
        'apellidos': apellidos,
        'documento': documento,
        'cargo_docente': cargo_docente,
        'cargo_administrativo': cargo_administrativo,
        'grado': grado,
        'tipo_contratacion': tipo_contratacion,
        'ciudad': ciudad,
        'centro_costos': centro_costos,
        'periodo': periodo,
        'Dias_Laborados': Dias_Laborados,
        'Banco': Banco,
        'Cuenta': Cuenta,
        'pension': pension,
        'Caja_CF': Caja_CF,
        'cesantias': cesantias,
        'conceptos_financieros': ner_resultado_provisional_conceptos_individuales
   }
  
  ner_resultado_provisional.append(my_details_todo)
  

if __name__ == '__main__':
    
    bucket_name= args["bucket"]
    #prefix carpeta en donde se encuentran todos los pdfs
    prefix=args["folder"]
    model_name = "TEN4499995428254646272"
    personas=[]
    files_names=[]
    files=list_blobs_with_prefix(bucket_name, prefix)  
    c=0
  
    if len(files_names) <= 100000:
        for documentos in files_names:
            #print("procesando: "+documentos)
            url = documentos
            gcs_url_file_prediction="gs://"+bucket_name+"/"+url
            #print(gcs_url_file_prediction)
            
            file_path=gcs_url_file_prediction
            ner_array=[]
            ner_conceptos_valor_ingreso=[]
            ner_conceptos_valor_egreso=[]
            ner_conceptos_ingreso=[]
            ner_conceptos_egreso=[]
            ner_cod_conceptos_ingreso=[]
            ner_cod_conceptos_egreso=[]
            ner_resultado_provisional=[]
            ner_resultado_provisional_conceptos_detallado_egresos=[]
            ner_resultado_provisional_conceptos_detallado_ingresos=[]
            ner_resultado_provisional_conceptos_individuales=[]
            get_prediction(file_path, model_name)
            personas.append(ner_resultado_provisional)
          
          
        data_string = json.dumps(personas, indent=4)
        print (data_string)
      
    else:
        print("0")