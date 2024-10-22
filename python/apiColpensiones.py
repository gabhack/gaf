from flask import Flask, request, jsonify
import pandas as pd
from sqlalchemy import create_engine, Table, MetaData, Column, Integer, String, DateTime, Sequence, Numeric
from sqlalchemy.exc import SQLAlchemyError, IntegrityError
from sqlalchemy.dialects.postgresql import insert
from sqlalchemy.sql import func
import os
import time
import logging

app = Flask(__name__)

# Configura la conexión a la base de datos PostgreSQL
DATABASE_URI = 'postgresql+psycopg2://front_web:eR>SUFYe=>~rmP2@production-pdf-load.cvkcoeco45jy.us-east-1.rds.amazonaws.com:5432/postgres'
engine = create_engine(DATABASE_URI)
metadata = MetaData()
colpensiones_table = Table('colpensiones', metadata, autoload_with=engine)
fiducidiaria_table = Table('fiducidiaria', metadata, autoload_with=engine)

# Definir la tabla de logs de carga de archivos
file_upload_logs = Table(
    'file_upload_logs', metadata,
    Column('id', Integer, Sequence('file_upload_logs_id_seq'), primary_key=True),
    Column('file_path', String(255), nullable=False),
    Column('timestamp', DateTime, server_default=func.now()),
    Column('total_registros', Integer, nullable=False),
    Column('registros_procesados', Integer, nullable=False),
    Column('total_por_registrar', Integer, nullable=False),
    Column('http_status', Integer)  # Nueva columna para registrar el estado HTTP
)

# Crear la tabla de logs si no existe
metadata.create_all(engine)

# Configura logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

@app.route('/')
def index():
    return "Flask app is running!"

def clean_and_validate_chunk(chunk, table_columns):
    try:
        # Eliminar espacios en blanco y convertir a minúsculas los nombres de las columnas
        chunk.columns = [col.strip().lower() for col in chunk.columns]
        table_columns = [col.strip().lower() for col in table_columns]

        # Filtrar solo las columnas que existen en la tabla
        chunk = chunk[[col for col in chunk.columns if col in table_columns]]

        # Validaciones
        chunk = chunk.dropna(subset=['documento'])
        chunk = chunk[chunk['documento'].astype(str).str.strip() != '']  # Convertir a string y excluir filas con 'documento' vacío
        chunk = chunk.fillna({
            'primer_apellido': '',
            'segundo_apellido': '',
            'primer_nombre': '',
            'segundo_nombre': '',
            'direccion': '',
            'telefono': '',
            'correo_electronico': '',
            'sexo': '',
            'departamento': '',
            'municipio': '',
            'vpensiones': 0,
            'vsalud': 0,
            'vembargo': 0,
            'vdescuentos': 0,
            'capacidad': 0
        })

        # Limpiar y convertir los campos numéricos
        numeric_fields = ['vpensiones', 'vsalud', 'vembargo', 'vdescuentos', 'capacidad']
        for field in numeric_fields:
            if field in chunk.columns:
                chunk[field] = chunk[field].astype(str).replace({r'[^\d]': ''}, regex=True)
                chunk[field] = chunk[field].replace({'': '0'})
                chunk[field] = pd.to_numeric(chunk[field], errors='coerce').fillna(0)
                # Asegurar que los valores no excedan el límite de 10^8 / 100
                chunk[field] = chunk[field].clip(upper=99999999.99)

        return chunk
    except Exception as e:
        logger.error(f"Error in clean_and_validate_chunk: {str(e)}")
        raise

def clean_and_validate_chunk_fiducidiaria(chunk, table_columns):
    try:
        # Eliminar espacios en blanco y convertir a minúsculas los nombres de las columnas
        chunk.columns = [col.strip().lower() for col in chunk.columns]
        table_columns = [col.strip().lower() for col in table_columns]

        # Filtrar solo las columnas que existen en la tabla
        chunk = chunk[[col for col in chunk.columns if col in table_columns]]

        # Validaciones
        chunk = chunk.dropna(subset=['documento'])
        chunk = chunk[chunk['documento'].astype(str).str.strip() != '']  # Convertir a string y excluir filas con 'documento' vacío
        chunk = chunk.fillna({
            'nombres': '',
            'apellidos': '',
            'sexo': '',
            'estado_civil': '',
            'edad_actual': 0,
            'estado_pensionado': '',
            'nom_depto': '',
            'valor_mesada': 0,
            'valorbruto': 0,
            'valordescuentos': 0,
            'pago_net': 0
        })

        # Limpiar y convertir los campos numéricos
        numeric_fields = ['valor_mesada', 'valorbruto', 'valordescuentos', 'pago_net']
        for field in numeric_fields:
            if field in chunk.columns:
                chunk[field] = chunk[field].astype(str).replace({r'[^\d]': ''}, regex=True)
                chunk[field] = chunk[field].replace({'': '0'})
                chunk[field] = pd.to_numeric(chunk[field], errors='coerce').fillna(0)
                # Asegurar que los valores no excedan el límite de 10^8 / 100
                chunk[field] = chunk[field].clip(upper=99999999.99)

        # Eliminar duplicados en el chunk basado en 'documento'
        chunk = chunk.drop_duplicates(subset=['documento'])
        
        return chunk
    except Exception as e:
        logger.error(f"Error in clean_and_validate_chunk_fiducidiaria: {str(e)}")
        raise

def count_total_records(file_path):
    total_records = 0
    for chunk in pd.read_csv(file_path, delimiter=';', chunksize=10000, encoding='latin1'):
        total_records += len(chunk)
    return total_records

@app.route('/process_csv', methods=['POST'])
def process_csv():
    start_time = time.time()
    log_id = None
    try:
        # Obtiene la ruta del archivo y el tipo de documento desde el cuerpo de la solicitud
        data = request.get_json()
        file_path = data.get('file_path')
        document_type = data.get('document_type')

        # Imprimir el tipo de documento recibido
        logger.info(f"Received document type: {document_type}")

        if not file_path or not os.path.exists(file_path):
            return jsonify({'error': 'Invalid or missing file path'}), 400
        if document_type not in ['colpensiones', 'fiducidiaria']:
            return jsonify({'error': 'Invalid or missing document type'}), 400

        # Definir las columnas que existen en la tabla correspondiente
        if document_type == 'colpensiones':
            table = colpensiones_table
            table_columns = [
                'documento', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 
                'segundo_nombre', 'direccion', 'telefono', 'correo_electronico', 
                'sexo', 'departamento', 'municipio', 'vpensiones', 
                'vsalud', 'vembargo', 'vdescuentos', 'capacidad'
            ]
            clean_and_validate = clean_and_validate_chunk
        else:
            table = fiducidiaria_table
            table_columns = [
                'documento', 'nombres', 'apellidos', 'sexo', 'estado_civil', 
                'edad_actual', 'estado_pensionado', 
                'nom_depto', 'valor_mesada', 'valorbruto', 'valordescuentos', 'pago_net'
            ]
            clean_and_validate = clean_and_validate_chunk_fiducidiaria

        logger.info(f"Using table: {table}")
        logger.info(f"Using table columns: {table_columns}")

        # Contar el total de registros en el archivo
        total_registros = count_total_records(file_path)
        registros_procesados = 0

        # Insertar registro inicial en file_upload_logs
        with engine.begin() as connection:
            result = connection.execute(file_upload_logs.insert().values(
                file_path=file_path,
                total_registros=total_registros,
                registros_procesados=registros_procesados,
                total_por_registrar=total_registros
            ))
            log_id = result.inserted_primary_key[0]

        chunk_count = 0
        # Lee y procesa el archivo CSV en bloques con la codificación correcta
        chunksize = 50000  # Tamaño del lote
        for chunk in pd.read_csv(file_path, delimiter=';', chunksize=chunksize, encoding='latin1'):
            try:
                chunk = clean_and_validate(chunk, table_columns)
                chunk_start_time = time.time()
                
                with engine.begin() as connection:
                    upsert_chunk(connection, chunk, table, document_type)
                    registros_procesados += len(chunk)
                    total_por_registrar = total_registros - registros_procesados
                    update_upload_progress(connection, log_id, registros_procesados, total_por_registrar)
                
                chunk_count += 1
                chunk_end_time = time.time()
                logger.info(f'Lote {chunk_count} procesado en {chunk_end_time - chunk_start_time:.2f} segundos.')
            except Exception as e:
                logger.error(f"Error processing chunk {chunk_count}: {str(e)}")
                raise

        total_time = time.time() - start_time
        logger.info(f'Tiempo total de procesamiento: {total_time:.2f} segundos.')

        # Actualizar el estado HTTP a 200 (OK) al finalizar el procesamiento
        with engine.begin() as connection:
            update_upload_status(connection, log_id, 200)

        return jsonify({'success': True, 'message': 'File processed and data inserted successfully', 'total_time': total_time})

    except SQLAlchemyError as e:
        logger.error(f"Database error: {str(e)}")
        # Actualizar el estado HTTP a 500 (Error del Servidor)
        if log_id is not None:
            with engine.begin() as connection:
                update_upload_status(connection, log_id, 500)
        return jsonify({'error': 'Database error: ' + str(e)}), 500
    except Exception as e:
        logger.error(f"Processing error: {str(e)}")
        # Actualizar el estado HTTP a 500 (Error del Servidor)
        if log_id is not None:
            with engine.begin() as connection:
                update_upload_status(connection, log_id, 500)
        return jsonify({'error': 'Processing error: ' + str(e)}), 500

def upsert_chunk(connection, chunk, table, document_type):
    try:
        logger.info(f"Upserting chunk with {len(chunk)} records.")
        insert_stmt = insert(table).values(chunk.to_dict(orient='records'))

        if document_type == 'colpensiones':
            update_stmt = insert_stmt.on_conflict_do_update(
                index_elements=['documento'],
                set_={
                    'primer_apellido': insert_stmt.excluded.primer_apellido,
                    'segundo_apellido': insert_stmt.excluded.segundo_apellido,
                    'primer_nombre': insert_stmt.excluded.primer_nombre,
                    'segundo_nombre': insert_stmt.excluded.segundo_nombre,
                    'direccion': insert_stmt.excluded.direccion,
                    'telefono': insert_stmt.excluded.telefono,
                    'correo_electronico': insert_stmt.excluded.correo_electronico,
                    'sexo': insert_stmt.excluded.sexo,
                    'departamento': insert_stmt.excluded.departamento,
                    'municipio': insert_stmt.excluded.municipio,
                    'vpensiones': insert_stmt.excluded.vpensiones,
                    'vsalud': insert_stmt.excluded.vsalud,
                    'vembargo': insert_stmt.excluded.vembargo,
                    'vdescuentos': insert_stmt.excluded.vdescuentos,
                    'capacidad': insert_stmt.excluded.capacidad
                }
            )
        else:
            update_stmt = insert_stmt.on_conflict_do_update(
                index_elements=['documento'],
                set_={
                    'nombres': insert_stmt.excluded.nombres,
                    'apellidos': insert_stmt.excluded.apellidos,
                    'sexo': insert_stmt.excluded.sexo,
                    'estado_civil': insert_stmt.excluded.estado_civil,
                    'edad_actual': insert_stmt.excluded.edad_actual,
                    'estado_pensionado': insert_stmt.excluded.estado_pensionado,
                    'nom_depto': insert_stmt.excluded.nom_depto,
                    'valor_mesada': insert_stmt.excluded.valor_mesada,
                    'valorbruto': insert_stmt.excluded.valorbruto,
                    'valordescuentos': insert_stmt.excluded.valordescuentos,
                    'pago_net': insert_stmt.excluded.pago_net
                }
            )

        connection.execute(update_stmt)
    except IntegrityError as e:
        if "ON CONFLICT DO UPDATE command cannot affect row a second time" in str(e):
            logger.warning(f"Ignored conflict error: {str(e)}")
        else:
            logger.error(f"IntegrityError in upsert_chunk: {str(e)}")
            raise
    except Exception as e:
        logger.error(f"Error in upsert_chunk: {str(e)}")
        raise

def update_upload_progress(connection, log_id, registros_procesados, total_por_registrar):
    try:
        logger.info(f"Updating upload progress: {registros_procesados}/{total_por_registrar}")
        connection.execute(
            file_upload_logs.update().where(file_upload_logs.c.id == log_id).values(
                registros_procesados=registros_procesados,
                total_por_registrar=total_por_registrar
            )
        )
    except Exception as e:
        logger.error(f"Error in update_upload_progress: {str(e)}")
        raise

def update_upload_status(connection, log_id, status):
    try:
        logger.info(f"Updating upload status to {status} for log id {log_id}")
        connection.execute(
            file_upload_logs.update().where(file_upload_logs.c.id == log_id).values(
                http_status=status
            )
        )
    except Exception as e:
        logger.error(f"Error in update_upload_status: {str(e)}")
        raise

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
