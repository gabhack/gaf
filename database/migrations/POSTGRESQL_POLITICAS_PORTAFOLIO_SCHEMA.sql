-- =============================================================================
-- DISEÑO DE TABLAS POSTGRESQL PARA POLÍTICAS DE PORTAFOLIO
-- =============================================================================
-- Conexión: DBP_CONNECTION=pgsql
-- Host: amidb.cvkcoeco45jy.us-east-1.rds.amazonaws.com
-- Puerto: 5432
-- Base de datos: postgres
-- Usuario: productionPdf
-- =============================================================================

-- Tabla principal de políticas del portafolio
CREATE TABLE IF NOT EXISTS politicas_portafolio (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,

    -- Porcentajes de la política
    porcentaje_portafolio NUMERIC(10, 6) NOT NULL DEFAULT 0,
    porcentaje_comision_comercial NUMERIC(10, 6) NOT NULL DEFAULT 0,
    porcentaje_reincorporacion_gaf NUMERIC(10, 6) NOT NULL DEFAULT 0,
    porcentaje_coadministracion NUMERIC(10, 6) NOT NULL DEFAULT 0,
    porcentaje_costo_seguro_vd NUMERIC(10, 6) NOT NULL DEFAULT 0,

    -- Estado de la política
    activo BOOLEAN DEFAULT TRUE,

    -- Auditoría
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,
    updated_by INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,

    -- Índices para búsqueda rápida
    CONSTRAINT unique_nombre_politica UNIQUE (nombre)
);

-- Índices
CREATE INDEX idx_politicas_portafolio_activo ON politicas_portafolio(activo);
CREATE INDEX idx_politicas_portafolio_nombre ON politicas_portafolio(nombre);
CREATE INDEX idx_politicas_portafolio_created_at ON politicas_portafolio(created_at DESC);

-- Función para actualizar automáticamente updated_at
CREATE OR REPLACE FUNCTION update_politicas_portafolio_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para actualizar updated_at automáticamente
CREATE TRIGGER trigger_update_politicas_portafolio_updated_at
BEFORE UPDATE ON politicas_portafolio
FOR EACH ROW
EXECUTE FUNCTION update_politicas_portafolio_updated_at();

-- =============================================================================
-- TABLA DE HISTORIAL DE CAMBIOS (OPCIONAL - PARA AUDITORÍA AVANZADA)
-- =============================================================================

CREATE TABLE IF NOT EXISTS politicas_portafolio_historial (
    id SERIAL PRIMARY KEY,
    politica_id UUID NOT NULL,
    accion VARCHAR(50) NOT NULL, -- CREATE, UPDATE, DELETE, TOGGLE_ACTIVO

    -- Datos antes del cambio (JSON)
    datos_anteriores JSONB NULL,

    -- Datos después del cambio (JSON)
    datos_nuevos JSONB NOT NULL,

    -- Usuario que realizó el cambio
    usuario_id INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,
    usuario_nombre VARCHAR(255) NULL,

    -- Timestamp
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,

    -- IP del usuario (opcional)
    ip_address VARCHAR(45) NULL
);

-- Índices para historial
CREATE INDEX idx_politicas_historial_politica_id ON politicas_portafolio_historial(politica_id);
CREATE INDEX idx_politicas_historial_created_at ON politicas_portafolio_historial(created_at DESC);
CREATE INDEX idx_politicas_historial_usuario_id ON politicas_portafolio_historial(usuario_id);

-- =============================================================================
-- DATOS DE EJEMPLO (OPCIONAL - POLÍTICAS PRE-CONFIGURADAS)
-- =============================================================================

INSERT INTO politicas_portafolio (
    nombre,
    descripcion,
    porcentaje_portafolio,
    porcentaje_comision_comercial,
    porcentaje_reincorporacion_gaf,
    porcentaje_coadministracion,
    porcentaje_costo_seguro_vd,
    activo
) VALUES
(
    'Política Estándar',
    'Política predeterminada con porcentajes estándar para operaciones regulares',
    8.00,
    3.00,
    5.00,
    2.00,
    0.0236,
    TRUE
),
(
    'Política Premium',
    'Política para clientes premium con mejores condiciones',
    6.50,
    2.50,
    4.00,
    1.50,
    0.02,
    TRUE
),
(
    'Política Básica',
    'Política básica para operaciones de bajo riesgo',
    10.00,
    4.00,
    6.00,
    2.50,
    0.03,
    TRUE
);

-- =============================================================================
-- CONSULTAS ÚTILES PARA ADMINISTRACIÓN
-- =============================================================================

-- Ver todas las políticas activas
-- SELECT * FROM politicas_portafolio WHERE activo = TRUE ORDER BY created_at DESC;

-- Ver historial de cambios de una política
-- SELECT * FROM politicas_portafolio_historial
-- WHERE politica_id = 'UUID_DE_LA_POLITICA'
-- ORDER BY created_at DESC;

-- Contar políticas activas vs inactivas
-- SELECT activo, COUNT(*) as total
-- FROM politicas_portafolio
-- GROUP BY activo;

-- Ver políticas con sus porcentajes promedio
-- SELECT
--     AVG(porcentaje_portafolio) as promedio_portafolio,
--     AVG(porcentaje_comision_comercial) as promedio_comision,
--     AVG(porcentaje_reincorporacion_gaf) as promedio_reincorporacion,
--     AVG(porcentaje_coadministracion) as promedio_coadministracion,
--     AVG(porcentaje_costo_seguro_vd) as promedio_seguro
-- FROM politicas_portafolio
-- WHERE activo = TRUE;

-- =============================================================================
-- NOTAS IMPORTANTES
-- =============================================================================
-- 1. La tabla usa UUID como clave primaria para mejor compatibilidad
-- 2. Se incluyen campos de auditoría (created_by, updated_by)
-- 3. El trigger actualiza automáticamente updated_at
-- 4. La tabla de historial guarda todos los cambios en formato JSONB
-- 5. Los porcentajes usan NUMERIC(10,6) para precisión decimal
-- =============================================================================

-- =============================================================================
-- TABLA DE POLÍTICAS DEL FONDO
-- =============================================================================

CREATE TABLE IF NOT EXISTS politicas_fondo (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre_fondo VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,

    -- Campos fijos
    saldo_max NUMERIC(15, 2) NOT NULL DEFAULT 0,
    dias_mora_max INTEGER NOT NULL DEFAULT 0,
    plazo_max INTEGER NOT NULL DEFAULT 0, -- en meses
    ta_min_ea NUMERIC(10, 6) NOT NULL DEFAULT 0, -- Tasa Anual Mínima Efectiva Anual
    t_usura_ea NUMERIC(10, 6) NOT NULL DEFAULT 0, -- Tasa de Usura Efectiva Anual
    tasa_usura NUMERIC(10, 6) NOT NULL DEFAULT 0,

    -- Campos calculados
    ta_min_em NUMERIC(10, 6) NOT NULL DEFAULT 0, -- T.A MIN (EA) / 12
    t_usura_menos2_ea NUMERIC(10, 6) NOT NULL DEFAULT 0, -- T. USURA (EA) - 2
    t_usura_em NUMERIC(10, 6) NOT NULL DEFAULT 0, -- T. USURA (EA) / 12
    t_usura_menos2_em NUMERIC(10, 6) NOT NULL DEFAULT 0, -- T. USURA -2 (EA) / 12
    t_usura_dia NUMERIC(10, 6) NOT NULL DEFAULT 0, -- T. USURA (EA) / 365

    -- Estado del fondo
    activo BOOLEAN DEFAULT TRUE,

    -- Auditoría
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,
    updated_by INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,

    -- Índices para búsqueda rápida
    CONSTRAINT unique_nombre_fondo UNIQUE (nombre_fondo)
);

-- Índices para políticas del fondo
CREATE INDEX idx_politicas_fondo_activo ON politicas_fondo(activo);
CREATE INDEX idx_politicas_fondo_nombre ON politicas_fondo(nombre_fondo);
CREATE INDEX idx_politicas_fondo_created_at ON politicas_fondo(created_at DESC);

-- Trigger para actualizar updated_at automáticamente en fondos
CREATE TRIGGER trigger_update_politicas_fondo_updated_at
BEFORE UPDATE ON politicas_fondo
FOR EACH ROW
EXECUTE FUNCTION update_politicas_portafolio_updated_at();

-- Función para recalcular campos derivados automáticamente
CREATE OR REPLACE FUNCTION calculate_fondo_derived_fields()
RETURNS TRIGGER AS $$
BEGIN
    -- Calcular T.A MIN (EM) = T.A MIN (EA) / 12
    NEW.ta_min_em := ROUND((NEW.ta_min_ea / 12)::numeric, 6);

    -- Calcular T. USURA -2 (EA) = T. USURA (EA) - 2
    NEW.t_usura_menos2_ea := ROUND((NEW.t_usura_ea - 2)::numeric, 6);

    -- Calcular T. USURA (EM) = T. USURA (EA) / 12
    NEW.t_usura_em := ROUND((NEW.t_usura_ea / 12)::numeric, 6);

    -- Calcular T. USURA -2 (EM) = T. USURA -2 (EA) / 12
    NEW.t_usura_menos2_em := ROUND((NEW.t_usura_menos2_ea / 12)::numeric, 6);

    -- Calcular T. USURA (DIA) = T. USURA (EA) / 365
    NEW.t_usura_dia := ROUND((NEW.t_usura_ea / 365)::numeric, 6);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para calcular automáticamente los campos derivados
CREATE TRIGGER trigger_calculate_fondo_derived_fields
BEFORE INSERT OR UPDATE ON politicas_fondo
FOR EACH ROW
EXECUTE FUNCTION calculate_fondo_derived_fields();

-- =============================================================================
-- TABLA DE HISTORIAL DE CAMBIOS PARA FONDOS (OPCIONAL)
-- =============================================================================

CREATE TABLE IF NOT EXISTS politicas_fondo_historial (
    id SERIAL PRIMARY KEY,
    fondo_id UUID NOT NULL,
    accion VARCHAR(50) NOT NULL, -- CREATE, UPDATE, DELETE, TOGGLE_ACTIVO

    -- Datos antes del cambio (JSON)
    datos_anteriores JSONB NULL,

    -- Datos después del cambio (JSON)
    datos_nuevos JSONB NOT NULL,

    -- Usuario que realizó el cambio
    usuario_id INTEGER NULL REFERENCES users(id) ON DELETE SET NULL,
    usuario_nombre VARCHAR(255) NULL,

    -- Timestamp
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,

    -- IP del usuario (opcional)
    ip_address VARCHAR(45) NULL
);

-- Índices para historial de fondos
CREATE INDEX idx_politicas_fondo_historial_fondo_id ON politicas_fondo_historial(fondo_id);
CREATE INDEX idx_politicas_fondo_historial_created_at ON politicas_fondo_historial(created_at DESC);
CREATE INDEX idx_politicas_fondo_historial_usuario_id ON politicas_fondo_historial(usuario_id);

-- =============================================================================
-- DATOS DE EJEMPLO PARA FONDOS (OPCIONAL - FONDOS PRE-CONFIGURADOS)
-- =============================================================================

INSERT INTO politicas_fondo (
    nombre_fondo,
    descripcion,
    saldo_max,
    dias_mora_max,
    plazo_max,
    ta_min_ea,
    t_usura_ea,
    tasa_usura,
    activo
) VALUES
(
    'Fondo Principal',
    'Fondo principal con parámetros estándar',
    50000000,
    90,
    180,
    15.50,
    34.11,
    34.11,
    TRUE
),
(
    'Fondo Premium',
    'Fondo para clientes premium con mejores tasas',
    100000000,
    60,
    240,
    12.00,
    32.00,
    32.00,
    TRUE
),
(
    'Fondo Básico',
    'Fondo básico para operaciones de bajo monto',
    25000000,
    120,
    120,
    18.00,
    36.00,
    36.00,
    TRUE
);

-- =============================================================================
-- CONSULTAS ÚTILES PARA FONDOS
-- =============================================================================

-- Ver todos los fondos activos
-- SELECT * FROM politicas_fondo WHERE activo = TRUE ORDER BY created_at DESC;

-- Ver historial de cambios de un fondo
-- SELECT * FROM politicas_fondo_historial
-- WHERE fondo_id = 'UUID_DEL_FONDO'
-- ORDER BY created_at DESC;

-- Contar fondos activos vs inactivos
-- SELECT activo, COUNT(*) as total
-- FROM politicas_fondo
-- GROUP BY activo;

-- Ver promedios de tasas de todos los fondos activos
-- SELECT
--     AVG(saldo_max) as promedio_saldo_max,
--     AVG(dias_mora_max) as promedio_dias_mora,
--     AVG(plazo_max) as promedio_plazo,
--     AVG(ta_min_ea) as promedio_ta_min_ea,
--     AVG(t_usura_ea) as promedio_t_usura_ea,
--     AVG(tasa_usura) as promedio_tasa_usura
-- FROM politicas_fondo
-- WHERE activo = TRUE;

-- =============================================================================
-- MIGRACIÓN DESDE SESIÓN A POSTGRES
-- =============================================================================
-- Para migrar los datos desde sesión (JSON) a PostgreSQL:
--
-- 1. Exportar datos desde el endpoint: /politicas-portafolio/export-json
-- 2. Transformar el JSON a formato SQL INSERT
-- 3. Ejecutar los INSERT en la base de datos
--
-- Ejemplo de transformación (en PHP):
/*
$politicas = session('politicas_portafolio', []);

foreach ($politicas as $politica) {
    DB::connection('pgsql')->table('politicas_portafolio')->insert([
        'id' => $politica['id'],
        'nombre' => $politica['nombre'],
        'descripcion' => $politica['descripcion'] ?? null,
        'porcentaje_portafolio' => $politica['porcentaje_portafolio'],
        'porcentaje_comision_comercial' => $politica['porcentaje_comision_comercial'],
        'porcentaje_reincorporacion_gaf' => $politica['porcentaje_reincorporacion_gaf'],
        'porcentaje_coadministracion' => $politica['porcentaje_coadministracion'],
        'porcentaje_costo_seguro_vd' => $politica['porcentaje_costo_seguro_vd'],
        'activo' => $politica['activo'],
        'created_at' => $politica['created_at'],
        'updated_at' => $politica['updated_at']
    ]);
}
*/
-- =============================================================================
