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

os.environ["GOOGLE_APPLICATION_CREDENTIALS"]=sys.argv[3]

project_id = "warm-helix-277015"
"""
model_id = "TEN1795742181693063168"
file_path = "gs://ami_nlp/ami_ner/jsonl_8/fopep_10.pdf"
"""

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


#get_prediction(file_path, model_name)


if __name__ == '__main__':
    
  bucket_name= sys.argv[1]
  #prefix carpeta en donde se encuentran todos los pdfs
  prefix=sys.argv[2]
  model_name = "TEN1795742181693063168"
  files_names=[]
  files=list_blobs_with_prefix(bucket_name, prefix)  
  c=0
  if len(files_names) <= 30:
      for documentos in files_names:
          print("procesando: "+documentos)
          url = documentos
          gcs_url_file_prediction="gs://"+bucket_name+"/"+url
          print(gcs_url_file_prediction)
          
          file_path=gcs_url_file_prediction
          ner_array=[]
          get_prediction(file_path, model_name)
          print(ner_array)
          
      
  else:
      print("0")
  




        


