<template>
    <div style="padding: 30px">
        <!-- Header -->
        <b-row class="mb-4">
            <b-col cols="12" md="8">
                <h3 class="heading-title">
                    <i class="fa fa-history mr-2"></i>
                    Historial de Análisis de Cartera
                </h3>
                <p style="font-size: 14px; line-height: 1.6; color: #6c757d; margin-top: 15px">
                    Consulte y gestione todos los estudios de cartera guardados. Puede visualizar, exportar o eliminar análisis anteriores.
                </p>
            </b-col>
            <b-col cols="12" md="4" class="text-right">
                <b-button
                    variant="success"
                    @click="irANuevoAnalisis"
                    style="background-color: #2c8c73; border-color: #2c8c73;"
                >
                    <i class="fa fa-plus-circle mr-2"></i>
                    Nuevo Análisis
                </b-button>
            </b-col>
        </b-row>

        <!-- Filtros -->
        <div class="panel mb-4">
            <div class="panel-body">
                <h5 class="mb-3" style="color: #2c8c73; font-weight: 600;">
                    <i class="fa fa-filter mr-2"></i>
                    Filtros de Búsqueda
                </h5>
                <b-row>
                    <b-col cols="12" md="3">
                        <b-form-group label="Fecha Desde:" label-for="fecha-desde">
                            <b-form-input
                                id="fecha-desde"
                                v-model="filtros.fechaDesde"
                                type="date"
                                size="sm"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label="Fecha Hasta:" label-for="fecha-hasta">
                            <b-form-input
                                id="fecha-hasta"
                                v-model="filtros.fechaHasta"
                                type="date"
                                size="sm"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="2">
                        <b-form-group label="Mes:" label-for="mes">
                            <b-form-input
                                id="mes"
                                v-model="filtros.mes"
                                placeholder="MM"
                                maxlength="2"
                                size="sm"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="2">
                        <b-form-group label="Año:" label-for="anio">
                            <b-form-input
                                id="anio"
                                v-model="filtros.anio"
                                placeholder="YYYY"
                                maxlength="4"
                                size="sm"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="2" class="d-flex align-items-end">
                        <b-button
                            variant="primary"
                            @click="aplicarFiltros"
                            size="sm"
                            class="mr-2"
                            :disabled="isLoading"
                        >
                            <i class="fa fa-search"></i> Buscar
                        </b-button>
                        <b-button
                            variant="secondary"
                            @click="limpiarFiltros"
                            size="sm"
                            :disabled="isLoading"
                        >
                            <i class="fa fa-eraser"></i>
                        </b-button>
                    </b-col>
                </b-row>
            </div>
        </div>

        <!-- Tabla de Estudios -->
        <div class="panel">
            <div class="panel-body">
                <!-- Loading State -->
                <div v-if="isLoading" class="text-center py-5">
                    <b-spinner variant="primary" label="Cargando..."></b-spinner>
                    <p class="mt-3 text-muted">Cargando estudios...</p>
                </div>

                <!-- Empty State -->
                <div v-else-if="!estudios.length" class="text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron estudios</h5>
                    <p class="text-muted">
                        {{ filtrosAplicados ? 'No hay resultados con los filtros aplicados.' : 'Aún no hay análisis guardados.' }}
                    </p>
                    <b-button
                        v-if="filtrosAplicados"
                        variant="primary"
                        size="sm"
                        @click="limpiarFiltros"
                    >
                        Limpiar Filtros
                    </b-button>
                </div>

                <!-- Tabla -->
                <div v-else class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #2c8c73; color: white;">
                            <tr>
                                <th>#ID</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Periodo</th>
                                <th>Archivo</th>
                                <th>Registros</th>
                                <th>Política Portafolio</th>
                                <th>Política Fondo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="estudio in estudios" :key="estudio.id">
                                <td><strong>{{ estudio.id }}</strong></td>
                                <td>{{ formatFecha(estudio.created_at) }}</td>
                                <td>
                                    <i class="fa fa-user mr-1"></i>
                                    {{ estudio.user ? estudio.user.name : 'N/D' }}
                                </td>
                                <td>
                                    <b-badge variant="info">{{ estudio.mes }}/{{ estudio.anio }}</b-badge>
                                </td>
                                <td>
                                    <small>{{ estudio.nombre_archivo }}</small>
                                </td>
                                <td>
                                    <b-badge variant="success">{{ estudio.total_registros }}</b-badge>
                                </td>
                                <td>
                                    <small>{{ getNombrePoliticaPortafolio(estudio) }}</small>
                                </td>
                                <td>
                                    <small>{{ getNombrePoliticaFondo(estudio) }}</small>
                                </td>
                                <td>
                                    <b-button-group size="sm">
                                        <b-button
                                            variant="info"
                                            @click="verEstudio(estudio)"
                                            v-b-tooltip.hover
                                            title="Ver detalles"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </b-button>
                                        <b-button
                                            variant="success"
                                            @click="exportarExcel(estudio)"
                                            v-b-tooltip.hover
                                            title="Exportar Excel"
                                        >
                                            <i class="fa fa-file-excel-o"></i>
                                        </b-button>
                                        <b-button
                                            variant="danger"
                                            @click="exportarPDF(estudio)"
                                            v-b-tooltip.hover
                                            title="Exportar PDF"
                                        >
                                            <i class="fa fa-file-pdf-o"></i>
                                        </b-button>
                                        <b-button
                                            variant="warning"
                                            @click="confirmarEliminar(estudio)"
                                            v-b-tooltip.hover
                                            title="Eliminar"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </b-button>
                                    </b-button-group>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <b-row class="mt-3">
                        <b-col cols="12" md="6">
                            <p class="text-muted">
                                Mostrando {{ estudios.length }} de {{ total }} estudios
                            </p>
                        </b-col>
                        <b-col cols="12" md="6" class="text-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="total"
                                :per-page="perPage"
                                @change="cargarEstudios"
                                size="sm"
                                first-text="Primera"
                                prev-text="Anterior"
                                next-text="Siguiente"
                                last-text="Última"
                            ></b-pagination>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>

        <!-- Modal Ver Estudio -->
        <b-modal
            id="modal-ver-estudio"
            title="Detalle del Estudio"
            size="xl"
            hide-footer
        >
            <template #modal-title>
                <span class="heading-title">
                    <i class="fa fa-file-text-o mr-2" style="color: #2c8c73;"></i>
                    Detalle del Análisis #{{ estudioSeleccionado.id }}
                </span>
            </template>

            <div v-if="estudioSeleccionado.id">
                <!-- Información General -->
                <b-card class="mb-3">
                    <h5 style="color: #2c8c73;"><strong>Información General</strong></h5>
                    <b-row>
                        <b-col cols="6" md="3">
                            <strong>ID:</strong> {{ estudioSeleccionado.id }}
                        </b-col>
                        <b-col cols="6" md="3">
                            <strong>Fecha:</strong> {{ formatFecha(estudioSeleccionado.created_at) }}
                        </b-col>
                        <b-col cols="6" md="3">
                            <strong>Usuario:</strong> {{ estudioSeleccionado.user ? estudioSeleccionado.user.name : 'N/D' }}
                        </b-col>
                        <b-col cols="6" md="3">
                            <strong>Periodo:</strong> {{ estudioSeleccionado.mes }}/{{ estudioSeleccionado.anio }}
                        </b-col>
                    </b-row>
                    <b-row class="mt-3">
                        <b-col cols="12">
                            <strong>Archivo:</strong> {{ estudioSeleccionado.nombre_archivo }}
                        </b-col>
                    </b-row>
                    <b-row class="mt-2" v-if="estudioSeleccionado.descripcion">
                        <b-col cols="12">
                            <strong>Descripción:</strong> {{ estudioSeleccionado.descripcion }}
                        </b-col>
                    </b-row>
                </b-card>

                <!-- Estadísticas -->
                <b-card class="mb-3">
                    <h5 style="color: #2c8c73;"><strong>Estadísticas</strong></h5>
                    <b-row class="text-center">
                        <b-col cols="4">
                            <h3 style="color: #2c8c73;">{{ estudioSeleccionado.total_registros }}</h3>
                            <p class="text-muted">Total Registros</p>
                        </b-col>
                        <b-col cols="4">
                            <h3 style="color: #28a745;">{{ estudioSeleccionado.registros_exitosos }}</h3>
                            <p class="text-muted">Exitosos</p>
                        </b-col>
                        <b-col cols="4">
                            <h3 style="color: #dc3545;">{{ estudioSeleccionado.registros_con_errores }}</h3>
                            <p class="text-muted">Con Errores</p>
                        </b-col>
                    </b-row>
                </b-card>

                <!-- Políticas Utilizadas -->
                <b-card class="mb-3">
                    <h5 style="color: #2c8c73;"><strong>Políticas Utilizadas</strong></h5>
                    <b-row>
                        <b-col cols="12" md="6">
                            <h6>Política de Portafolio:</h6>
                            <p><strong>{{ getNombrePoliticaPortafolio(estudioSeleccionado) }}</strong></p>
                        </b-col>
                        <b-col cols="12" md="6">
                            <h6>Política de Fondo:</h6>
                            <p><strong>{{ getNombrePoliticaFondo(estudioSeleccionado) }}</strong></p>
                        </b-col>
                    </b-row>
                </b-card>

                <!-- Acciones -->
                <div class="text-right">
                    <b-button variant="secondary" @click="$bvModal.hide('modal-ver-estudio')" class="mr-2">
                        <i class="fa fa-times mr-1"></i> Cerrar
                    </b-button>
                    <b-button variant="success" @click="exportarExcel(estudioSeleccionado)" class="mr-2">
                        <i class="fa fa-file-excel-o mr-1"></i> Exportar Excel
                    </b-button>
                    <b-button variant="danger" @click="exportarPDF(estudioSeleccionado)">
                        <i class="fa fa-file-pdf-o mr-1"></i> Exportar PDF
                    </b-button>
                </div>
            </div>
        </b-modal>

        <!-- Modal Confirmar Eliminación -->
        <b-modal
            id="modal-confirmar-eliminar"
            title="Confirmar Eliminación"
            hide-footer
            size="md"
        >
            <template #modal-title>
                <span class="heading-title">
                    <i class="fa fa-exclamation-triangle mr-2" style="color: #dc3545;"></i>
                    Confirmar Eliminación
                </span>
            </template>

            <div class="alert alert-danger">
                <i class="fa fa-warning mr-2"></i>
                <strong>¿Está seguro de eliminar este estudio?</strong>
            </div>

            <p>Se eliminará el siguiente análisis:</p>
            <ul>
                <li><strong>ID:</strong> {{ estudioAEliminar.id }}</li>
                <li><strong>Fecha:</strong> {{ formatFecha(estudioAEliminar.created_at) }}</li>
                <li><strong>Archivo:</strong> {{ estudioAEliminar.nombre_archivo }}</li>
                <li><strong>Registros:</strong> {{ estudioAEliminar.total_registros }}</li>
            </ul>

            <p class="text-danger">
                <strong>Nota:</strong> Esta acción no se puede deshacer.
            </p>

            <div class="text-right">
                <b-button
                    variant="secondary"
                    @click="$bvModal.hide('modal-confirmar-eliminar')"
                    class="mr-2"
                    :disabled="isDeleting"
                >
                    <i class="fa fa-times mr-1"></i> Cancelar
                </b-button>
                <b-button
                    variant="danger"
                    @click="eliminarEstudio"
                    :disabled="isDeleting"
                >
                    <span v-if="!isDeleting">
                        <i class="fa fa-trash mr-1"></i> Sí, Eliminar
                    </span>
                    <span v-else>
                        <i class="fa fa-spinner fa-spin mr-1"></i> Eliminando...
                    </span>
                </b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
    name: 'HistorialCartera',
    data() {
        return {
            estudios: [],
            estudioSeleccionado: {},
            estudioAEliminar: {},
            isLoading: false,
            isDeleting: false,
            page: 1,
            perPage: 10,
            total: 0,
            filtros: {
                fechaDesde: '',
                fechaHasta: '',
                mes: '',
                anio: ''
            },
            filtrosAplicados: false
        }
    },
    mounted() {
        this.cargarEstudios();
    },
    methods: {
        async cargarEstudios(page = 1) {
            this.isLoading = true;
            this.page = page;

            try {
                const params = {
                    page: this.page,
                    perPage: this.perPage,
                    fecha_desde: this.filtros.fechaDesde || null,
                    fecha_hasta: this.filtros.fechaHasta || null,
                    mes: this.filtros.mes || null,
                    anio: this.filtros.anio || null
                };

                const response = await axios.get('/historial-cartera/listar', { params });

                this.estudios = response.data.data;
                this.total = response.data.total;

            } catch (error) {
                console.error('Error al cargar estudios:', error);
                this.$bvToast.toast('Error al cargar el historial de estudios', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            } finally {
                this.isLoading = false;
            }
        },

        aplicarFiltros() {
            this.filtrosAplicados = true;
            this.page = 1;
            this.cargarEstudios(1);
        },

        limpiarFiltros() {
            this.filtros = {
                fechaDesde: '',
                fechaHasta: '',
                mes: '',
                anio: ''
            };
            this.filtrosAplicados = false;
            this.cargarEstudios(1);
        },

        verEstudio(estudio) {
            this.estudioSeleccionado = estudio;
            this.$bvModal.show('modal-ver-estudio');
        },

        confirmarEliminar(estudio) {
            this.estudioAEliminar = estudio;
            this.$bvModal.show('modal-confirmar-eliminar');
        },

        async eliminarEstudio() {
            this.isDeleting = true;

            try {
                await axios.delete(`/historial-cartera/eliminar/${this.estudioAEliminar.id}`);

                this.$bvModal.hide('modal-confirmar-eliminar');

                this.$bvToast.toast('Estudio eliminado exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                this.cargarEstudios(this.page);

            } catch (error) {
                console.error('Error al eliminar estudio:', error);
                this.$bvToast.toast('Error al eliminar el estudio', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            } finally {
                this.isDeleting = false;
            }
        },

        async exportarExcel(estudio) {
            try {
                this.$bvToast.toast('Preparando exportación a Excel...', {
                    title: 'Exportando',
                    variant: 'info',
                    solid: true,
                    autoHideDelay: 2000
                });

                // Usar un método que no bloquea la UI
                const link = document.createElement('a');
                link.href = `/historial-cartera/exportar/${estudio.id}/excel`;
                link.download = `estudio_cartera_${estudio.mes}_${estudio.anio}.xlsx`;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Mostrar mensaje de éxito después de un pequeño delay
                setTimeout(() => {
                    this.$bvToast.toast('Excel generado exitosamente', {
                        title: 'Éxito',
                        variant: 'success',
                        solid: true,
                        autoHideDelay: 3000
                    });
                }, 1000);

            } catch (error) {
                console.error('Error al exportar Excel:', error);
                this.$bvToast.toast('Error al exportar a Excel', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            }
        },

        async exportarPDF(estudio) {
            try {
                this.$bvToast.toast('Preparando exportación a PDF...', {
                    title: 'Exportando',
                    variant: 'info',
                    solid: true,
                    autoHideDelay: 2000
                });

                // Crear instancia de jsPDF
                const doc = new jsPDF('landscape');

                // Parsear datos procesados
                const datosProcessados = typeof estudio.datos_procesados === 'string'
                    ? JSON.parse(estudio.datos_procesados)
                    : estudio.datos_procesados;

                if (!datosProcessados || datosProcessados.length === 0) {
                    throw new Error('No hay datos para exportar');
                }

                const headerColor = [44, 140, 115];

                // Página 1: Información General
                doc.text(`Estudio: ${estudio.nombre_archivo}`, 14, 10);
                doc.text(`Periodo: ${estudio.mes}/${estudio.anio}`, 14, 15);
                doc.text(`Fecha: ${this.formatFecha(estudio.created_at)}`, 14, 20);

                doc.autoTable({
                    head: [['Cédula', 'Nombre', 'F. Nac.', 'Edad', 'Contrato', 'Cargo', 'Situación', 'Pagaduría', 'Cupo Libre', 'Colp.', 'Fidu.', 'Fopep']],
                    body: datosProcessados.map(item => [
                        item.doc || '',
                        (item.nombre || '').substring(0, 30),
                        item.fecha_nacimiento || '',
                        item.edad || '',
                        (item.tipo_contrato || '').substring(0, 15),
                        (item.cargo || '').substring(0, 20),
                        (item.situacion_laboral || '').substring(0, 15),
                        (item.pagaduria || '').substring(0, 20),
                        this.formatCurrency(item.cupo_libre || 0),
                        item.colpensiones || 'NO',
                        item.fiduprevisora || 'NO',
                        item.fopep || 'NO'
                    ]),
                    startY: 25,
                    headStyles: { fillColor: headerColor, fontSize: 6 },
                    bodyStyles: { fontSize: 5.5 },
                    margin: { top: 25 }
                });

                // Página 2: Detalle de Crédito
                doc.addPage();
                doc.text('Detalle de Crédito', 14, 10);
                doc.autoTable({
                    head: [['Cédula', 'Nombre', 'Val. Desemb.', 'Saldo Cap.', 'Int. Corr.', 'Int. Mora', 'Seguros', 'Otros', 'Total Oblig.']],
                    body: datosProcessados.map(item => [
                        item.doc || '',
                        (item.nombre || '').substring(0, 30),
                        this.formatCurrency(item.valor_desembolso || 0),
                        this.formatCurrency(item.saldo_capital_original || 0),
                        this.formatCurrency(item.intereses_corrientes || 0),
                        this.formatCurrency(item.intereses_mora || 0),
                        this.formatCurrency(item.seguros || 0),
                        this.formatCurrency(item.otros_conceptos || 0),
                        this.formatCurrency(item.total_obligacion || 0)
                    ]),
                    startY: 15,
                    headStyles: { fillColor: headerColor, fontSize: 6 },
                    bodyStyles: { fontSize: 5.5 }
                });

                // Página 3: Detalle Portafolio y Cupo
                doc.addPage();
                doc.text('Detalle Portafolio y Cupo', 14, 10);
                doc.autoTable({
                    head: [['Cédula', 'Nombre', 'Costo Portaf.', 'Comisión', 'Re-Inc. GAF', 'Coadmin.', 'SubTotal', 'Cupo Disp.']],
                    body: datosProcessados.map(item => [
                        item.doc || '',
                        (item.nombre || '').substring(0, 30),
                        this.formatCurrency(item.costo_compra_portafolio || 0),
                        this.formatCurrency(item.costo_comision_comercial || 0),
                        this.formatCurrency(item.costo_reincorporacion_gaf || 0),
                        this.formatCurrency(item.costo_coadministracion || 0),
                        this.formatCurrency(item.subtotal_costo_compra_adm || 0),
                        this.formatCurrency(item.total_cupo_disponible || 0)
                    ]),
                    startY: 15,
                    headStyles: { fillColor: headerColor, fontSize: 6 },
                    bodyStyles: { fontSize: 5.5 }
                });

                // Página 4: Cuota a Incorporar (Parte 1)
                doc.addPage();
                doc.text('Cuota a Incorporar - Parte 1', 14, 10);
                doc.autoTable({
                    head: [['Cédula', 'Nombre', 'Tasa Pact.', 'Resp. Tasa', 'Plazo Pact.', 'Resp. Plazo', 'Cuota Pact.', 'Resp. Cuota', 'Cuota a Inc.']],
                    body: datosProcessados.map(item => [
                        item.doc || '',
                        (item.nombre || '').substring(0, 30),
                        (item.tasa_pactada || 0).toFixed(2) + '%',
                        item.respetar_tasa_pactada || '',
                        item.plazo_pactado || 0,
                        item.respetar_plazo_pactado || '',
                        this.formatCurrency(item.cuota_pactada || 0),
                        item.respetar_cuota_pactada || '',
                        this.formatCurrency(item.cuota_a_incorporar || 0)
                    ]),
                    startY: 15,
                    headStyles: { fillColor: headerColor, fontSize: 6 },
                    bodyStyles: { fontSize: 5.5 }
                });

                // Guardar PDF
                const filename = `estudio_cartera_${estudio.mes}_${estudio.anio}_${Date.now()}.pdf`;
                doc.save(filename);

                this.$bvToast.toast('PDF generado exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

            } catch (error) {
                console.error('Error al exportar PDF:', error);
                this.$bvToast.toast('Error al exportar a PDF: ' + error.message, {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            }
        },

        irANuevoAnalisis() {
            window.location.href = '/analisis-de-cartera-avanzado';
        },

        formatFecha(fecha) {
            if (!fecha) return 'N/D';
            const date = new Date(fecha);
            return date.toLocaleDateString('es-CO', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getNombrePoliticaPortafolio(estudio) {
            if (!estudio.metadatos) return 'N/D';
            const metadatos = typeof estudio.metadatos === 'string'
                ? JSON.parse(estudio.metadatos)
                : estudio.metadatos;
            return metadatos.nombre_politica_portafolio || 'N/D';
        },

        getNombrePoliticaFondo(estudio) {
            if (!estudio.metadatos) return 'N/D';
            const metadatos = typeof estudio.metadatos === 'string'
                ? JSON.parse(estudio.metadatos)
                : estudio.metadatos;
            return metadatos.nombre_politica_fondo || 'N/D';
        },

        formatCurrency(value) {
            if (value === null || value === undefined || value === '') return '$0';
            const numValue = parseFloat(value);
            if (isNaN(numValue)) return '$0';
            return '$' + numValue.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    }
}
</script>

<style scoped>
.heading-title {
    color: #2c8c73;
    font-weight: 600;
}

.panel {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.panel-body {
    padding: 20px;
}

.table th {
    font-size: 13px;
}

.table td {
    vertical-align: middle;
    font-size: 13px;
}
</style>
