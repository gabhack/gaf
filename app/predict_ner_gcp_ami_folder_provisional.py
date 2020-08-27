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
import argparse
"""Predict."""
# [START automl_language_entity_extraction_predict]
from google.cloud import automl
from google.api_core.client_options import ClientOptions
from google.cloud import automl_v1
from google.cloud.automl_v1.proto import service_pb2
from google.cloud import storage
import json

ap = argparse.ArgumentParser()
ap.add_argument("-b", "--bucket", type=str,
	help="path to cedula folder")
ap.add_argument("-f", "--folder", type=str,
	help="path to input pdf")
# ap.add_argument("-g", "--gcp_credentials", type=str,
#	help="path to credentials file")
args = vars(ap.parse_args())

# os.environ["GOOGLE_APPLICATION_CREDENTIALS"]=args["gcp_credentials"]
os.environ["GOOGLE_APPLICATION_CREDENTIALS"]="/var/www/html/ami/app/credentials.json"

project_id = "warm-helix-277015"


def my_min_function(somelist):
    min_value = None
    for value in somelist:
        if not min_value:
            min_value = value
        elif value < min_value:
            min_value = value
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
  
  nombres=""
  apellidos=""
  documento=""
  grado=""
  tipo_contratacion=""
  ciudad=""
  centro_costos=""
 
  
  
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
    """
    if entidad_detectada=="pagaduria":
        
    
        my_details_ami = {
          'Secretaria_Educacion': contenido_entidad_detectada
        }
        ner_result_ami.append(my_details_ami)
    
    elif entidad_detectada=="nit_pagaduria":
        
    
        my_details_ami = {
          'Nit_Pagaduria': contenido_entidad_detectada
        }
        ner_result_ami.append(my_details_ami)
    """    
    if entidad_detectada=="nombres":
        
        if nombres == "":
            
        
    
            my_details_ami = {
              'nombres': contenido_entidad_detectada
            }
            ner_result_ami.append(my_details_ami)
            
            nombres=contenido_entidad_detectada
        
    elif entidad_detectada=="apellidos":
        
        if apellidos == "":
            
           my_details_ami = {
             'apellidos': contenido_entidad_detectada
           }
           ner_result_ami.append(my_details_ami)
           apellidos=contenido_entidad_detectada
       
    elif entidad_detectada=="cedula":
        
        if documento == "":

           my_details_ami = {
             'documento': contenido_entidad_detectada
           }
           ner_result_ami.append(my_details_ami)
           documento=contenido_entidad_detectada
       
    elif entidad_detectada=="ciudad":
       
        if ciudad == "":
            
   
           my_details_ami = {
             'ciudad': contenido_entidad_detectada
           }
           ner_result_ami.append(my_details_ami)
           ciudad=contenido_entidad_detectada
       
    elif entidad_detectada=="centro_costos":
        
        if centro_costos == "":
       
   
           my_details_ami = {
             'centro_costos': contenido_entidad_detectada
           }
           ner_result_ami.append(my_details_ami)
           centro_costos= contenido_entidad_detectada


    elif entidad_detectada=="tipo_contratacion":
        
        if tipo_contratacion == "":
       
            my_details_ami = {
              'tipo_contratacion': contenido_entidad_detectada
            }
            ner_result_ami.append(my_details_ami)
            tipo_contratacion=contenido_entidad_detectada
       
    elif entidad_detectada=="grado":
        
        if grado == "":
        

            my_details_ami = {
              'grado': contenido_entidad_detectada
            }
            ner_result_ami.append(my_details_ami)
            grado=contenido_entidad_detectada
    
    
    my_details_todo = {
          'nombres': nombres,
          'apellidos': apellidos,
          'documento': documento,
          'grado': grado,
          'tipo_contratacion': tipo_contratacion,
          'ciudad': ciudad,
          'centro_costos': centro_costos
          
        }
  
  
    
       
    """   
    elif entidad_detectada=="cargo_docente":
       
   
       my_details_ami = {
         'cargo_docente': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="cargo_administrativo":
       
   
       my_details_ami = {
         'cargo_administrativo': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
    """


    """   
    elif entidad_detectada=="ingreso_base":
       
   
       my_details_ami = {
         'ingresos_base': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="periodo_pago":
       
   
       my_details_ami = {
         'periodo': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="dias_laborados":
       
   
       my_details_ami = {
         'Dias_Laborados': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="entidad_financiera":
       
   
       my_details_ami = {
         'Banco': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="cuenta_bancaria":
       
   
       my_details_ami = {
         'Cuenta': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="pension":
       
   
       my_details_ami = {
         'pension': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="caja_compensacion":
       
   
       my_details_ami = {
         'Caja_CF': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
       
    elif entidad_detectada=="cesantias":
       
   
       my_details_ami = {
         'cesantias': contenido_entidad_detectada
       }
       ner_result_ami.append(my_details_ami)
    """    
   ######################################################################
    """

    
    elif entidad_detectada=="cod_concepto_ingreso":
       
   
       my_details_ami_concepto = {
         'cod_concepto_ingreso': contenido_entidad_detectada
       }
       
       ner_cod_conceptos_ingreso.append(my_details_ami_concepto)
       
    elif entidad_detectada=="concepto_ingreso":
       
   
       my_details_ami_concepto = {
         'concepto_ingreso': contenido_entidad_detectada
       }
       
       ner_conceptos_ingreso.append(my_details_ami_concepto)
       
    elif entidad_detectada=="valor_ingreso":
       
   
       my_details_ami_concepto = {
         'ingresos': contenido_entidad_detectada
       }
       
       ner_conceptos_valor_ingreso.append(my_details_ami)
       
    elif entidad_detectada=="total_ingresos":
       
   
       my_details_ami = {
         'ingresos_totales': contenido_entidad_detectada
       }
       
    elif entidad_detectada=="cod_concepto_egreso":
       
   
       my_details_ami_concepto = {
         'cod_concepto_egreso': contenido_entidad_detectada
       }
       ner_cod_conceptos_egreso.append(my_details_ami_concepto)
       
    elif entidad_detectada=="concepto_egreso":
       
   
       my_details_ami_concepto = {
         'concepto_egreso': contenido_entidad_detectada
       }
       ner_conceptos_egreso.append(my_details_ami_concepto)
       
    elif entidad_detectada=="valor_egreso":
       
   
       my_details_ami_concepto = {
         'egresos': contenido_entidad_detectada
       }
       ner_conceptos_valor_egreso.append(my_details_ami_concepto)
       
    elif entidad_detectada=="total_egresos":
       
   
       my_details_ami = {
         'egresos_totales': contenido_entidad_detectada
       }
      
    elif entidad_detectada=="neto_a_pagar":
       
   
       my_details_ami = {
         'Neto_A_Pagar': contenido_entidad_detectada
       }
    """   
   #####################################################################
       
    
    
    """
    if my_details_ami != None:
        #print(my_details_ami)
        ner_result_ami.append(my_details_ami)
        
        #print(ner_result_ami)
    else:
        ner_conceptos_ami.append(my_details_ami_concepto)
    
    #ner_result_ami.append(resultado)
    """
    
    """
    file_name=file_path.split("/")
    array_size=len(file_name)-1
    filtered_file_name=file_name[array_size]
    new_file_name=filtered_file_name.replace(".pdf","")
    json_name=new_file_name+".json"
    #print(json_name)
    
    #w = sobreescribe  #a=adiciona
    with open(json_name, 'w') as json_file:
      json.dump(my_details, json_file, ensure_ascii=False)
    """
  #print(my_details_todo)
  ner_resultado_provisional.append(my_details_todo)

#get_prediction(file_path, model_name)


if __name__ == '__main__':
    
  bucket_name= args["bucket"]
  #prefix carpeta en donde se encuentran todos los pdfs
  prefix=args["folder"]
  model_name = "TEN1795742181693063168"
  files_names=[]
  list_blobs_with_prefix(bucket_name, prefix)  
  c=0
  
  if len(files_names) <= 30:
      for documentos in files_names:
          #print("procesando: "+documentos)
          url = documentos
          gcs_url_file_prediction="gs://"+bucket_name+"/"+url
          #print(gcs_url_file_prediction)
          
          file_path=gcs_url_file_prediction
          ner_array=[]
          ner_result_ami=[]
          
          ner_conceptos_ami=[]
          
          ner_conceptos_valor_ingreso=[]
          ner_conceptos_valor_egreso=[]
          
          ner_conceptos_ingreso=[]
          ner_conceptos_egreso=[]
          
          ner_cod_conceptos_ingreso=[]
          ner_cod_conceptos_egreso=[]
          
          ner_detallado_conceptos_ingreso=[]
          ner_detallado_conceptos_egreso=[]
          #c_grado=False
          c_grados = []
          ner_resultado_provisional=[]
          get_prediction(file_path, model_name)
          #print(ner_array)
          #print(ner_result_ami)
          print(ner_resultado_provisional)
          
          """
          final_provisional=[]
          
          for i in range(len(ner_result_ami)):
              
              x=ner_result_ami[i]
              y=json.dumps(x) 
              #print(y)
              #z=x.replace("{", "")
              #print(y.translate({ord('{'): None}))
              #print(y.translate({ord('}'): None}))
              y.translate({ord('}'): None})
              #y=z.replace("}", "")
              
              final_provisional.append(y)
              
          print(final_provisional)
          """
          
        
                      
          """
          size_valores_ingresos= len(ner_conceptos_valor_ingreso)
          size_conceptos_ingresos= len(ner_conceptos_ingreso)
          size_cod_conceptos_ingresos= len(ner_cod_conceptos_ingreso)
          
          sizes_ingresos = [size_valores_ingresos, size_conceptos_ingresos, size_cod_conceptos_ingresos]
          
          valor_minimo_ingresos = my_min_function(sizes_ingresos)
          
          for i in range(valor_minimo_ingresos):
              
              ner_detallado_conceptos_ingreso.append(ner_cod_conceptos_ingreso[i])
              ner_detallado_conceptos_ingreso.append(ner_conceptos_ingreso[i])
              ner_detallado_conceptos_ingreso.append(ner_conceptos_valor_ingreso[i])
              
              
          size_valores_egresos= len(ner_conceptos_valor_egreso)
          size_conceptos_egresos= len(ner_conceptos_egreso)
          size_cod_conceptos_egresos= len(ner_cod_conceptos_egreso)
          
          sizes_egresos = [size_valores_egresos, size_conceptos_egresos, size_cod_conceptos_egresos]
          
          valor_minimo_egresos = my_min_function(sizes_egresos)
          
          for i in range(valor_minimo_egresos):
              
              ner_detallado_conceptos_egreso.append(ner_cod_conceptos_egreso[i])
              ner_detallado_conceptos_egreso.append(ner_conceptos_egreso[i])
              ner_detallado_conceptos_egreso.append(ner_conceptos_valor_egreso[i])
              
              
              
         # print(ner_result_ami)
         # print(ner_conceptos_ami)
          
          #print(ner_result_ami)
          #print(ner_detallado_conceptos_ingreso)
          #print(ner_detallado_conceptos_egreso)
          
          #print(ner_conceptos)
          #print(ner_conceptos_egreso)
          """
      
  else:
      print("0")
  





        


