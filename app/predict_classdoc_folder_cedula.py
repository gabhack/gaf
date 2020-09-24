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
from google.cloud import storage
import os
import json
import random
import time

os.environ["GOOGLE_APPLICATION_CREDENTIALS"]=sys.argv[3]


def rename_blob(bucket_name, blob_name, new_name):
    """Renames a blob."""
    # bucket_name = "your-bucket-name"
    # blob_name = "your-object-name"
    # new_name = "new-object-name"

    storage_client = storage.Client()
    bucket = storage_client.bucket(bucket_name)
    blob = bucket.blob(blob_name)

    new_blob = bucket.rename_blob(blob, new_name)

    #print("Blob {} has been renamed to {}".format(blob.name, new_blob.name))

def delete_blob(bucket_name, blob_name):
    """Deletes a blob from the bucket."""
    # bucket_name = "your-bucket-name"
    # blob_name = "your-object-name"

    storage_client = storage.Client()

    bucket = storage_client.bucket(bucket_name)
    blob = bucket.blob(blob_name)
    blob.delete()

    #print("Blob {} deleted.".format(blob_name))



def copy_blob(
    bucket_name, blob_name, destination_bucket_name, destination_blob_name
):
    """Copies a blob from one bucket to another with a new name."""
    # bucket_name = "your-bucket-name"
    # blob_name = "your-object-name"
    # destination_bucket_name = "destination-bucket-name"
    # destination_blob_name = "destination-object-name"

    storage_client = storage.Client()

    source_bucket = storage_client.bucket(bucket_name)
    source_blob = source_bucket.blob(blob_name)
    destination_bucket = storage_client.bucket(destination_bucket_name)

    blob_copy = source_bucket.copy_blob(
        source_blob, destination_bucket, destination_blob_name
    )
    """
    print(
        "Blob {} in bucket {} copied to blob {} in bucket {}.".format(
            source_blob.name,
            source_bucket.name,
            blob_copy.name,
            destination_bucket.name,
        )
    )
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
  
  for annotation_payload in request.payload:
      
        """
        print(
            u"Predicted class name: {}".format(annotation_payload.display_name)
        )
        print(
            u"Predicted class score: {}".format(
                annotation_payload.classification.score
            )
        )
        """
        clase = annotation_payload.display_name
        porcentaje = annotation_payload.classification.score
            
        clases.append(clase)
              
        porcentajes.append(float(porcentaje))
        
  max_porcentaje_pos = porcentajes.index(max(porcentajes))
  max_porcentaje = porcentajes[max_porcentaje_pos]
  #print("clase: ", clases[max_porcentaje_pos])
  #print("porcentaje: ", max_porcentaje)
  clase = clases[max_porcentaje_pos]
  

  
  """
  clase=str(request.payload[0].display_name)
  porcentaje=str(request.payload[0].classification.score)
  print("Clase Detectada: "+clase)
  print("Porcentaje de Afinidad: "+porcentaje)
  
  my_details = {
    'tipo_documento': clase,
    'porcentaje': porcentaje
  }
  
  #print(my_details)
  
  """
  
  """
  #w = sobreescribe  #a=adiciona
  with open('categoria_docs.json', 'w') as json_file:
    json.dump(my_details, json_file)
  """
  #####  
  
  return request , clase  # waits until request is returned


    

if __name__ == '__main__':
    bucket_name= sys.argv[1]
    #prefix carpeta en donde se encuentran todos los pdfs
    prefix=sys.argv[2]
    files_names=[]
    files=list_blobs_with_prefix(bucket_name, prefix)
    #print(files_names)
    #print(files)
    c=0

    results=""
    
    json_array=[]
 
      
    for documentos in files_names:
    
        #print("procesando: "+documentos)
        
        clases=[]
        porcentajes=[]
           
        file_path = documentos
        gcs_url_file_prediction="gs://"+bucket_name+"/"+file_path
        model_name = "projects/55927814408/locations/us-central1/models/TCN6090768851320963072"
        c=c+1
        #print("Clasificando documento #: "+str(c))
        
        results = get_prediction(gcs_url_file_prediction, model_name)
        
        #print("Todos los documentos pertenecen a la categoria: "+results[0])
        clase = results[1]
        files_names_divididos=file_path
        
        #print(files_names_divididos)
        #files=list_blobs_with_prefix(bucket_name, prefix, delimiter=None)
        #print(files_names_divididos)
       
        blob_name=files_names_divididos
        new_name= files_names_divididos.split("/")
        size_name_final= len(new_name)-1
        size_name_initial=len(new_name)-2
        initial_new_name=new_name[:size_name_initial]
        final_new_name=new_name[size_name_final]
        #print("initial name: " , new_name[:size_name_initial])
        #print("final name: " , new_name[size_name_final])
        folder_name_init=""
        for i in range(len(initial_new_name)):
            folder_name_init += initial_new_name[i] + "/"
        #print(folder_name_init)
        folder_name_mid=folder_name_init + results[1]
        folder_name_final= folder_name_mid + "/"+ final_new_name
        
        my_details = {
            'nombre_archivo': final_new_name,
            'url_gcs': folder_name_final,  
            'tipo_documento': clase
          }
          
        json_array.append(my_details)
        
        #print(folder_name_final)
 
        rename_blob(bucket_name, blob_name, folder_name_final)
        
    data_string = json.dumps(json_array, indent=4)
    print (data_string)
        

            