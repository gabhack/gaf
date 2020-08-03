# -*- coding: utf-8 -*-
"""
Created on Wed Jul  1 15:54:04 2020

@author: USUARIO
"""
#USO DEL SCRIPT
#python predict_classdoc.py gs://ami_nlp/ami_documentos_clasificador/pruebas_class_1_jul/acuerdo_pago_embargo_26.pdf projects/55927814408/locations/us-central1/models/TCN6090768851320963072
  

  
import sys

from google.api_core.client_options import ClientOptions
from google.cloud import automl_v1
from google.cloud.automl_v1.proto import service_pb2
import os
import json
os.environ["GOOGLE_APPLICATION_CREDENTIALS"]="../credentials.json"

def inline_text_payload(file_path):
  with open(file_path, 'rb') as ff:
    content = ff.read()
  return {'text_snippet': {'content': content, 'mime_type': 'text/plain'} }

def pdf_payload(file_path):
  return {'document': {'input_config': {'gcs_source': {'input_uris': [file_path] } } } }

def get_prediction(file_path, model_name):
  options = ClientOptions(api_endpoint='automl.googleapis.com')
  prediction_client = automl_v1.PredictionServiceClient(client_options=options)

  #payload = inline_text_payload(file_path)
  # Uncomment the following line (and comment the above line) if want to predict on PDFs.
  payload = pdf_payload(file_path)

  params = {}
  request = prediction_client.predict(model_name, payload, params)
  ######
  """
  for annotation_payload in request.payload:
    print(
        u"Predicted class name: {}".format(annotation_payload.display_name)
    )
    print(
        u"Predicted class score: {}".format(
            annotation_payload.classification.score
        )
    )
   
  
  """
  clase=str(request.payload[0].display_name)
  porcentaje=str(request.payload[0].classification.score)
  print(clase+"|"+porcentaje)
  
  my_details = {
    'tipo_documento': clase,
    'porcentaje': porcentaje
  }
  
  #w = sobreescribe  #a=adiciona
  with open('categoria_docs.json', 'w') as json_file:
    json.dump(my_details, json_file)
    
  #####  
  
  return request  # waits until request is returned

if __name__ == '__main__':
  file_path = sys.argv[1]
  model_name = sys.argv[2]
  get_prediction(file_path, model_name)

  #print (get_prediction(file_path, model_name))