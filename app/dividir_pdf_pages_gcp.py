# -*- coding: utf-8 -*-
"""
Created on Thu Jul  9 15:07:31 2020

@author: USUARIO
"""
import argparse
import os
import PyPDF2
from PyPDF2 import PdfFileWriter, PdfFileReader
from tensorflow.python.platform import gfile
import time
import datetime
import sys
import glob
import shutil

# [START storage_upload_file]
from google.cloud import storage


ap = argparse.ArgumentParser()
ap.add_argument("-g", "--gcpfolder", type=str,
	help="path to cedula folder")
ap.add_argument("-i", "--pdfs", type=str,
	help="path to input pdf")
ap.add_argument("-o", "--output", type=str,
	help="path to output the classified pdf type")
ap.add_argument("-c", "--cedula", type=str,
	help="path to new cedula folder")
ap.add_argument("-p", "--gcp_credentials", type=str,
	help="path to credentials file")
args = vars(ap.parse_args())

os.environ["GOOGLE_APPLICATION_CREDENTIALS"]=args["gcp_credentials"]

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





def download_blob(bucket_name, source_blob_name, destination_file_name):
    """Downloads a blob from the bucket."""
    # bucket_name = "your-bucket-name"
    # source_blob_name = "storage-object-name"
    # destination_file_name = "local/path/to/file"

    storage_client = storage.Client()

    bucket = storage_client.bucket(bucket_name)
    blob = bucket.blob(source_blob_name)
    descarga=blob.download_to_filename(destination_file_name)

    print(
        "Blob {} downloaded to {}.".format(
            source_blob_name, destination_file_name
        )
    )
    
    



def upload_blob(bucket_name, source_file_name, destination_blob_name):
    """Uploads a file to the bucket."""
    # bucket_name = "your-bucket-name"
    # source_file_name = "local/path/to/file"
    # destination_blob_name = "storage-object-name"

    storage_client = storage.Client()
    bucket = storage_client.bucket(bucket_name)
    blob = bucket.blob(destination_blob_name)

    blob.upload_from_filename(source_file_name)

    print(
        "File {} uploaded to {}.".format(
            source_file_name, destination_blob_name
        )
    )
    
    
if __name__ == "__main__":
    files_names=[]
    files=list_blobs_with_prefix("ami_laravel", prefix=args["gcpfolder"])
    print(files_names)
    for document in files_names:
        
        print("Guardando Archivo: ", document)
    
        file_name=document.split("/")
        array_size=len(file_name)-1
        filtered_file_name=file_name[array_size]
        #new_file_name=filtered_file_name.replace(".pdf","")
        #print(filtered_file_name)
        
        
        download_blob(
            bucket_name="ami_laravel",
            source_blob_name=document,
            destination_file_name=args["pdfs"]+"//"+filtered_file_name,
        )
        print("Guardado con exito")
        

        


    file_list=[]
    file_glob = os.path.join(args["pdfs"], '*.pdf')
    file_list.extend(gfile.Glob(file_glob))
    c = 0
    
    
    
    # BUCLE PARA RECORRER TODOS LOS ARCHIVOS PDF PRESENTES EN UNA CARPETA
    for documento in file_list:
        now = datetime.datetime.now()
        hora= now.strftime('%m-%d-%y_%H-%M-%S')
        f = open(documento, "rb")
        inputpdf = PdfFileReader(f, strict=False)
        for i in range(inputpdf.numPages):
            output = PdfFileWriter()
            output.addPage(inputpdf.getPage(i))
            file_name=documento.split("\\")
            array_size=len(file_name)-1
            filtered_file_name=file_name[array_size]
            new_file_name=filtered_file_name.replace(".pdf","")
            with open(args["output"]+"//"+new_file_name+"_"+str(c)+"_"+hora+"_"+"page%s.pdf" % i, "wb") as outputStream:
                output.write(outputStream)
            # [END storage_upload_file]
    
       
            upload_blob(
                bucket_name="ami_laravel",
                source_file_name=args["output"]+"//"+new_file_name+"_"+str(c)+"_"+hora+"_"+"page%s.pdf" % i,
                destination_blob_name=args["cedula"]+"/"+new_file_name+"_"+str(c)+"_"+hora+"_"+"page%s.pdf" % i
            )
        # Cerrando archivo PDF
        f.close()
            
    
    
        print("procesando documento #: "+str(c))
        c=c+1

   
file_list_delete=[]
file_glob_delete = os.path.join(args["pdfs"], '*.pdf')
file_list_delete.extend(gfile.Glob(file_glob_delete))
for documento in file_list_delete:

        # Cerrando archivo
        try:
            os.remove(documento)
            
        except OSError:
            pass
print("Se Eliminaron Archivos Temporales Descargados")
file_list_delete2=[]
file_glob_delete2= os.path.join(args["output"], '*.pdf')
file_list_delete2.extend(gfile.Glob(file_glob_delete2))
for documento2 in file_list_delete2:

        # Cerrando archivo
        try:
            os.remove(documento2)
            
        except OSError:
            pass
print("Se Eliminaron Archivos Temporales Generados")


#print("Se Eliminaron Archivos Temporales")
    
    
    