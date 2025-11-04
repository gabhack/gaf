<template>
    <div style="padding: 30px">
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <!-- Progress Log -->
        <div v-if="uploadProgress.show" class="progress-log-overlay">
            <div class="progress-log-container">
                <h4 class="progress-title">Procesando Análisis de Cartera</h4>
                <div class="progress-steps">
                    <div
                        v-for="step in uploadProgress.steps"
                        :key="step.id"
                        class="progress-step"
                        :class="{
                            'step-active': step.status === 'active',
                            'step-completed': step.status === 'completed',
                            'step-pending': step.status === 'pending'
                        }"
                    >
                        <span class="step-icon">{{ step.icon }}</span>
                        <span class="step-text">{{ step.text }}</span>
                        <span v-if="step.status === 'active'" class="step-loader">...</span>
                        <span v-if="step.status === 'completed'" class="step-check">✓</span>
                    </div>
                </div>
            </div>
        </div>

        <b-row>
            <b-col cols="12" md="9" style="margin-left: 28px">
                <h3 class="heading-title">Análisis de Cartera Avanzado</h3>
                <p>
                    Acceda a información estratégica que facilita la toma de decisiones en la compra de cartera,
                    permitiendo identificar y priorizar a los pensionados y empleados activos del sector público con
                    potencial de recuperación.
                </p>
            </b-col>
        </b-row>
        <div
            style="min-height: 500px"
            class="panel mb-3 col-md-12 d-flex justify-content-center align-items-center"
            v-if="!results.length"
        >
            <div class="d-flex flex-column align-items-center justify-content-center">
                <Lupa class="mb-3" />
                <p style="font-size: 16px; color: #6c757d; text-align: center;">
                    Aún no tienes análisis cargados <br />
                    Comienza creando un nuevo análisis de cartera
                </p>
                <b-button
                    @click="$bvModal.show('bv-modal-example')"
                    variant="primary"
                    size="lg"
                    style="background-color: #2c8c73; border-color: #2c8c73; padding: 12px 32px; font-weight: 600;"
                >
                    <i class="fa fa-plus-circle mr-2"></i> Nuevo Análisis de Cartera
                </b-button>
            </div>
        </div>

        <!-- MODALES - Movidos fuera del v-if para que siempre estén disponibles -->
        <b-modal id="bv-modal-example" hide-footer size="xl">
                <template #modal-title>
                    <span class="heading-title">
                        <i class="fa fa-chart-line mr-2" style="color: #2c8c73;"></i>
                        Nuevo Análisis de Cartera Avanzado
                    </span>
                </template>
                <div class="" style="background-color: #f9fafc; border-left: 4px solid #249fe3; border-radius: 4px">
                    <b-row style="padding: 16px">
                        <b-col cols="1" class="d-flex justify-content-center align-items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM9 4C9 4.55228 8.55228 5 8 5C7.44772 5 7 4.55228 7 4C7 3.44772 7.44772 3 8 3C8.55228 3 9 3.44772 9 4ZM7 7C6.44772 7 6 7.44772 6 8C6 8.55229 6.44772 9 7 9V12C7 12.5523 7.44772 13 8 13H9C9.55228 13 10 12.5523 10 12C10 11.4477 9.55228 11 9 11V8C9 7.44772 8.55228 7 8 7H7Z"
                                    fill="#20A0E9"
                                />
                            </svg>
                        </b-col>
                        <b-col cols="11" class="d-flex justify-content-center align-items-center">
                            <p class="modal-text">
                                Por favor, asegúrese de que el archivo Excel contiene la columna <strong>'cédulas'</strong> (obligatoria)
                                y opcionalmente: <strong>operación, valor desembolso, saldo capital original, intereses corrientes,
                                intereses de mora, seguros, otros conceptos, tasa pactada, respetar tasa pactada, plazo pactado,
                                cuota pactada, respetar cuota pactada, cupo colpensiones, cupo fopep, cupo fiduprevisora</strong>.
                                El sistema calculará automáticamente el Cupo Sem (libre inversión) y la cuota incorporada previamente.
                            </p>
                        </b-col>
                    </b-row>
                </div>
                <b-row class="py-3">
                    <div class="col-md-6">
                        <b-form-group label="Política de Portafolio: *" label-for="politica_portafolio">
                            <b-form-select
                                id="politica_portafolio"
                                v-model="selectedPoliticaPortafolio"
                                :options="politicasPortafolioOptions"
                                class="input_style_b form-control2"
                            >
                                <template #first>
                                    <b-form-select-option :value="null" disabled>Seleccione una política de portafolio</b-form-select-option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-md-6">
                        <b-form-group label="Política de Fondo: *" label-for="politica_fondo">
                            <b-form-select
                                id="politica_fondo"
                                v-model="selectedPoliticaFondo"
                                :options="politicasFondoOptions"
                                class="input_style_b form-control2"
                            >
                                <template #first>
                                    <b-form-select-option :value="null" disabled>Seleccione una política de fondo</b-form-select-option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-md-6">
                        <b-form-group label="Mes (MM): *">
                            <b-form-input
                                v-model="mes"
                                placeholder="01"
                                maxlength="2"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-6">
                        <b-form-group label="Año (YYYY): *">
                            <b-form-input
                                v-model="año"
                                placeholder="2025"
                                maxlength="4"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>
                    <b-col
                        cols="12"
                        style="
                            min-height: 150px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            cursor: pointer;
                        "
                        @click="triggerFileInput"
                        @dragover.prevent="handleDragOver"
                        @dragleave.prevent="handleDragLeave"
                        @drop.prevent="handleDrop"
                    >
                        <div
                            style="display: flex; flex-direction: column; align-items: center; justify-content: center"
                        >
                            <UploadFile class="mb-2" />
                            <p class="text-center" style="margin-bottom: 0.5rem">
                                Arrastre o suelte el archivo <br />
                                o
                            </p>
                            <CustomButton text="Seleccionar archivo" :color="'white'" />
                        </div>
                        <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none" />
                    </b-col>
                </b-row>
                <div class="d-flex justify-content-center align-item-center mb-5" style="width: 100%" v-if="file">
                    <div
                        style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            width: 300px;
                            border-bottom: 1px solid #babcbe;
                            padding: 8px;
                        "
                    >
                        <span style="font-size: 12px; font-weight: 400; line-height: 15.62px; color: black">
                            {{ file.name }}
                        </span>
                        <button style="padding: 0; margin: 0; border: none; background: none" @click="deleteFile">
                            <Trash />
                        </button>
                    </div>
                </div>
                <CustomButton @click="uploadFile($bvModal)" text="Subir archivo" v-if="file" />
                <CustomButton @click="$bvModal.hide('bv-modal-example')" :color="'white'" text="Cerrar" />
            </b-modal>

            <!-- Modal para Guardar Análisis -->
            <b-modal id="modal-guardar-analisis" title="Guardar Análisis de Cartera" hide-footer size="md">
                <template #modal-title>
                    <span class="heading-title">
                        <i class="fa fa-save mr-2" style="color: #2c8c73;"></i>
                        Guardar Análisis de Cartera
                    </span>
                </template>

                <div class="mb-3">
                    <h6 style="color: #2c8c73;"><strong>Resumen del Análisis</strong></h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td><strong>Archivo:</strong></td>
                            <td>{{ nombreArchivo || 'Sin archivo' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Periodo:</strong></td>
                            <td>{{ mes || '--' }} / {{ año || '----' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Registros:</strong></td>
                            <td>{{ results.length }}</td>
                        </tr>
                        <tr>
                            <td><strong>Política Portafolio:</strong></td>
                            <td>{{ getPoliticaPortafolioNombre() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Política Fondo:</strong></td>
                            <td>{{ getPoliticaFondoNombre() }}</td>
                        </tr>
                    </table>
                </div>

                <b-form-group
                    label="Descripción (Opcional):"
                    label-for="descripcion-analisis"
                    description="Agregue una descripción o nota para identificar este análisis"
                >
                    <b-form-textarea
                        id="descripcion-analisis"
                        v-model="descripcionAnalisis"
                        placeholder="Ej: Análisis de cartera para cierre del mes..."
                        rows="3"
                        max-rows="6"
                        maxlength="500"
                    ></b-form-textarea>
                    <small class="text-muted">{{ descripcionAnalisis.length }}/500 caracteres</small>
                </b-form-group>

                <div class="d-flex justify-content-end mt-3">
                    <b-button
                        variant="secondary"
                        @click="$bvModal.hide('modal-guardar-analisis')"
                        class="mr-2"
                        :disabled="isSavingAnalisis"
                    >
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </b-button>
                    <b-button
                        variant="success"
                        @click="confirmarGuardarAnalisis"
                        :disabled="isSavingAnalisis || !results.length"
                        style="background-color: #2c8c73; border-color: #2c8c73;"
                    >
                        <span v-if="!isSavingAnalisis">
                            <i class="fa fa-save mr-1"></i> Guardar
                        </span>
                        <span v-else>
                            <i class="fa fa-spinner fa-spin mr-1"></i> Guardando...
                        </span>
                    </b-button>
                </div>
            </b-modal>

            <!-- Modal para Confirmar Nuevo Análisis -->
            <b-modal
                id="modal-nuevo-analisis"
                title="Iniciar Nuevo Análisis"
                hide-footer
                size="md"
            >
                <template #modal-title>
                    <span class="heading-title">
                        <i class="fa fa-exclamation-triangle mr-2" style="color: #f39c12;"></i>
                        Confirmar Nuevo Análisis
                    </span>
                </template>

                <div class="alert alert-warning">
                    <i class="fa fa-info-circle mr-2"></i>
                    <strong>¿Está seguro de iniciar un nuevo análisis?</strong>
                </div>

                <p>Esta acción limpiará todos los datos actuales:</p>
                <ul>
                    <li>Resultados del análisis actual ({{ results.length }} registros)</li>
                    <li>Archivo cargado: <strong>{{ nombreArchivo || 'Ninguno' }}</strong></li>
                    <li>Periodo: <strong>{{ mes || '--' }} / {{ año || '----' }}</strong></li>
                    <li>Políticas seleccionadas</li>
                </ul>

                <p class="text-danger">
                    <i class="fa fa-warning mr-1"></i>
                    <strong>Nota:</strong> Asegúrese de haber guardado el análisis actual antes de continuar.
                </p>

                <div class="d-flex justify-content-end mt-3">
                    <b-button
                        variant="secondary"
                        @click="$bvModal.hide('modal-nuevo-analisis')"
                        class="mr-2"
                    >
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </b-button>
                    <b-button
                        variant="primary"
                        @click="confirmarNuevoAnalisis"
                    >
                        <i class="fa fa-check mr-1"></i> Sí, Iniciar Nuevo
                    </b-button>
                </div>
            </b-modal>

        <!-- Card para mostrar las consultas recientes -->
        <div v-if="showRecentConsultations && recentConsultations.length" class="card recent-consultations">
            <div class="card-header">
                <b>Consultas Recientes</b>
            </div>
            <ul class="list-group list-group-flush">
                <li v-for="consultation in recentConsultations" :key="consultation.id" class="list-group-item">
                    <a href="#" @click.prevent="loadConsultationData(consultation.consulta_data)">
                        {{ consultation.created_at }}
                    </a>
                </li>
            </ul>
        </div>

        <!-- Panel de Resultados -->
        <div v-if="results.length" class="panel mb-3 col-md-12">
            <b-row class="mb-3" style="margin-left: 15px; margin-right: 15px;">
                <b-col cols="12" class="d-flex justify-content-between align-items-center">
                    <div>
                        <b-badge variant="info" class="mr-2" style="font-size: 14px; padding: 8px 12px;">
                            Política Portafolio: <strong>{{ getPoliticaPortafolioNombre() }}</strong>
                        </b-badge>
                        <b-badge variant="info" style="font-size: 14px; padding: 8px 12px;">
                            Política Fondo: <strong>{{ getPoliticaFondoNombre() }}</strong>
                        </b-badge>
                    </div>
                    <div class="d-flex align-items-center">
                        <b-button
                            @click="guardarAnalisis"
                            variant="success"
                            class="mr-2"
                            style="background-color: #2c8c73; border-color: #2c8c73; white-space: nowrap;"
                        >
                            <i class="fa fa-save mr-1"></i> Guardar Análisis
                        </b-button>
                        <b-button
                            @click="iniciarNuevoAnalisis"
                            variant="primary"
                            class="mr-2"
                            style="white-space: nowrap;"
                        >
                            <i class="fa fa-plus-circle mr-1"></i> Nuevo Análisis
                        </b-button>
                        <CustomButton
                            @click="exportToPDF"
                            class="btn btn-danger mr-2"
                            text="Exportar PDF"
                            style="white-space: nowrap"
                        />
                        <CustomButton
                            @click="exportToExcel"
                            class="btn btn-success"
                            text="Exportar Excel"
                            style="white-space: nowrap"
                        />
                    </div>
                </b-col>
            </b-row>
            <h4 style="margin-left: 16px" class="heading-title">Resultado:</h4>
            <div class="panel-body">
                <b-form-group>
                    <b-form-input
                        v-model="searchQuery"
                        placeholder="Buscar por documento"
                        class="input_style_b form-control2"
                    ></b-form-input>
                </b-form-group>
                <div class="table-container">
                    <table class="table table-hover table-striped custom-analysis-table">
                        <thead class="thead-custom">
                            <tr>
                                <th class="th-operacion">Operación</th>
                                <th class="th-cedula">Cédula</th>
                                <th class="th-nombre">Nombre del Cliente</th>
                                <th class="th-secretaria">Secretaria</th>
                                <th class="th-colp text-center">Colp.</th>
                                <th class="th-fidu text-center">Fidu.</th>
                                <th class="th-fopep text-center">Fopep</th>
                                <th class="th-edad text-center">Edad</th>
                                <th class="th-actions text-center">Crédito</th>
                                <th class="th-actions text-center">Portafolio</th>
                                <th class="th-actions text-center">AMI</th>
                                <th class="th-actions text-center">Cuota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fila Principal -->
                            <tr v-for="(result, index) in filteredResults" :key="result.doc + '-' + index" class="table-row-custom">
                                <td class="td-operacion">{{ result.operacion || 'N/D' }}</td>
                                <td class="td-cedula"><strong>{{ result.doc }}</strong></td>
                                <td class="td-nombre">{{ capitalize(result.nombre_usuario) || 'No disponible' }}</td>
                                <td class="td-secretaria">{{ capitalize(result.pagaduria) || 'N/D' }}</td>
                                <td class="td-badge text-center">
                                    <b-badge :variant="result.colpensiones ? 'success' : 'secondary'" class="badge-small">
                                        {{ result.colpensiones ? 'Sí' : 'No' }}
                                    </b-badge>
                                </td>
                                <td class="td-badge text-center">
                                    <b-badge :variant="result.fiducidiaria ? 'success' : 'secondary'" class="badge-small">
                                        {{ result.fiducidiaria ? 'Sí' : 'No' }}
                                    </b-badge>
                                </td>
                                <td class="td-badge text-center">
                                    <b-badge :variant="result.fopep ? 'success' : 'secondary'" class="badge-small">
                                        {{ result.fopep ? 'Sí' : 'No' }}
                                    </b-badge>
                                </td>
                                <td class="td-edad text-center">{{ result.edad || 'N/D' }}</td>
                                <td class="td-actions text-center">
                                    <b-button
                                        v-if="hasDetalleCredito(result)"
                                        size="sm"
                                        variant="outline-primary"
                                        @click="toggleDetails(result, 'credito')"
                                        data-toggle="modal"
                                        data-target="#modalCredito"
                                        v-b-tooltip.hover
                                        title="Ver detalle de crédito"
                                    >
                                        <i class="fa fa-eye"></i>
                                    </b-button>
                                    <span v-else class="text-muted small">N/D</span>
                                </td>
                                <td class="td-actions text-center">
                                    <b-button
                                        v-if="hasDetallePortafolio(result)"
                                        size="sm"
                                        variant="outline-warning"
                                        @click="toggleDetails(result, 'portafolio')"
                                        data-toggle="modal"
                                        data-target="#modalPortafolio"
                                        v-b-tooltip.hover
                                        title="Ver detalle de portafolio"
                                    >
                                        <i class="fa fa-briefcase"></i>
                                    </b-button>
                                    <span v-else class="text-muted small">N/D</span>
                                </td>
                                <td class="td-actions text-center">
                                    <b-button
                                        v-if="hasDetalleAMI(result)"
                                        size="sm"
                                        variant="outline-info"
                                        @click="toggleDetails(result, 'ami')"
                                        data-toggle="modal"
                                        data-target="#modalAMI"
                                        v-b-tooltip.hover
                                        title="Ver detalle AMI"
                                    >
                                        <i class="fa fa-chart-line"></i>
                                    </b-button>
                                    <span v-else class="text-muted small">N/D</span>
                                </td>
                                <td class="td-actions text-center">
                                    <b-button
                                        size="sm"
                                        variant="outline-success"
                                        @click="toggleDetails(result, 'cuotaIncorporar')"
                                        data-toggle="modal"
                                        data-target="#modalCuotaIncorporar"
                                        v-b-tooltip.hover
                                        title="Ver cuota a incorporar"
                                        style="background-color: #2c8c73; border-color: #2c8c73; color: white;"
                                    >
                                        <i class="fa fa-calculator"></i>
                                    </b-button>
                                </td>
                            </tr>
                            <!-- Fila para mostrar mensaje si no hay resultados -->
                            <tr v-if="filteredResults.length === 0">
                                <td colspan="12">No hay resultados</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Modal de Detalle de Crédito -->
                    <div
                        class="modal fade"
                        id="modalCredito"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="modalCreditoLabel"
                        aria-hidden="true"
                        data-backdrop="static"
                        data-keyboard="false"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCreditoLabel">Detalle de Crédito</h5>
                                    <button
                                        @click="closeExpandedRows"
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div
                                    class="modal-body"
                                    v-for="(result, index) in filteredResults"
                                    :key="'credito-' + result.doc + '-' + index"
                                    v-if="isRowExpanded(result, 'credito')"
                                >
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Valor Desembolso</strong></td>
                                                <td>{{ formatCurrency(result.valor_desembolso) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Saldo Capital Original</strong></td>
                                                <td>{{ formatCurrency(result.saldo_capital_original) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Intereses Corrientes</strong></td>
                                                <td>{{ formatCurrency(result.intereses_corrientes) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Intereses de Mora</strong></td>
                                                <td>{{ formatCurrency(result.intereses_de_mora) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Seguros</strong></td>
                                                <td>{{ formatCurrency(result.seguros) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Otros Conceptos</strong></td>
                                                <td>{{ formatCurrency(result.otros_conceptos) }}</td>
                                            </tr>
                                            <tr style="background-color: #2c8c73; color: white; font-weight: bold;">
                                                <td><strong>Total Obligación</strong></td>
                                                <td>{{ formatCurrency(result.total_obligacion) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de Detalle Portafolio -->
                    <div
                        class="modal fade"
                        id="modalPortafolio"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="modalPortafolioLabel"
                        aria-hidden="true"
                        data-backdrop="static"
                        data-keyboard="false"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPortafolioLabel">Detalle Portafolio</h5>
                                    <button
                                        @click="closeExpandedRows"
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div
                                    class="modal-body"
                                    v-for="(result, index) in filteredResults"
                                    :key="'portafolio-' + result.doc + '-' + index"
                                    v-if="isRowExpanded(result, 'portafolio')"
                                >
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Costo Compra Portafolio</strong></td>
                                                <td>{{ formatCurrency(result.costo_compra_portafolio) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Costo Comisión Comercial</strong></td>
                                                <td>{{ formatCurrency(result.costo_comision_comercial) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Costo Re-Incorporación GAF</strong></td>
                                                <td>{{ formatCurrency(result.costo_reincorporacion_gaf) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Costo Coadministración</strong></td>
                                                <td>{{ formatCurrency(result.costo_coadministracion) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Costo Seguro V.D (3 Meses)</strong></td>
                                                <td>{{ formatCurrency(result.costo_seguro_vd) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Costos Fiduciarios (Fiducoomeva)</strong></td>
                                                <td>{{ formatCurrency(result.costos_fiduciarios) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Reporte Centrales ($10.000)</strong></td>
                                                <td>{{ formatCurrency(result.reporte_centrales) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tecnología ($5.000)</strong></td>
                                                <td>{{ formatCurrency(result.tecnologia) }}</td>
                                            </tr>
                                            <tr style="background-color: #2c8c73; color: white; font-weight: bold;">
                                                <td><strong>SUB TOTAL Costo Compra + Adm (NPL´S)</strong></td>
                                                <td>{{ formatCurrency(result.sub_total_costo_compra_adm) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de Detalle AMI -->
                    <div
                        class="modal fade"
                        id="modalAMI"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="modalAMILabel"
                        aria-hidden="true"
                        data-backdrop="static"
                        data-keyboard="false"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAMILabel">Detalle AMI</h5>
                                    <button
                                        @click="closeExpandedRows"
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div
                                    class="modal-body"
                                    v-for="(result, index) in filteredResults"
                                    :key="'ami-' + result.doc + '-' + index"
                                    v-if="isRowExpanded(result, 'ami')"
                                >
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Cuota Incorporada Previamente</strong></td>
                                                <td>{{ formatCurrency(result.cuota_incorporada_previamente) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cupo Sem</strong></td>
                                                <td>{{ formatCurrency(result.cupo_sem) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cupo Colpensiones</strong></td>
                                                <td>{{ formatCurrency(result.cupo_colpensiones) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cupo Fopep</strong></td>
                                                <td>{{ formatCurrency(result.cupo_fopep) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cupo Fiduprevisora</strong></td>
                                                <td>{{ formatCurrency(result.cupo_fiduprevisora) }}</td>
                                            </tr>
                                            <tr style="background-color: #2c8c73; color: white; font-weight: bold;">
                                                <td><strong>TOTAL CUPO DISPONIBLE</strong></td>
                                                <td>{{ formatCurrency(result.total_cupo_disponible) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de Cuota a Incorporar -->
                    <div
                        class="modal fade"
                        id="modalCuotaIncorporar"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="modalCuotaIncorporarLabel"
                        aria-hidden="true"
                        data-backdrop="static"
                        data-keyboard="false"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCuotaIncorporarLabel">Cuota a Incorporar</h5>
                                    <button
                                        @click="closeExpandedRows"
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div
                                    class="modal-body"
                                    v-for="(result, index) in filteredResults"
                                    :key="'cuotaIncorporar-' + result.doc + '-' + index"
                                    v-if="isRowExpanded(result, 'cuotaIncorporar')"
                                >
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color: #e3f2fd; font-weight: bold;">
                                                <td><strong>TOTAL CUPO DISPONIBLE</strong></td>
                                                <td>{{ formatCurrency(result.total_cupo_disponible) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tasa Pactada</strong></td>
                                                <td>{{ result.tasa_pactada || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>RESPETAR TASA PACTADA</strong></td>
                                                <td>{{ result.respetar_tasa_pactada || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tasa Nueva Libranza Ck</strong></td>
                                                <td>{{ result.tasa_nueva_libranza_ck || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>PLAZO PACTADO</strong></td>
                                                <td>{{ result.plazo_pactado || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>RESPETAR PLAZO PACTADO</strong></td>
                                                <td>{{ result.respetar_plazo_pactado || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Plazo Nueva Libranza Ck</strong></td>
                                                <td>{{ result.plazo_nueva_libranza_ck || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>CUOTA PACTADA</strong></td>
                                                <td>{{ formatCurrency(result.cuota_pactada) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>RESPETAR CUOTA PACTADA</strong></td>
                                                <td>{{ result.respetar_cuota_pactada || 'No disponible' }}</td>
                                            </tr>
                                            <tr style="background-color: #2c8c73; color: white; font-weight: bold;">
                                                <td><strong>CUOTA A INCORPORAR</strong></td>
                                                <td>{{ formatCurrency(result.cuota_a_incorporar) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tasa Modificada Conservando plazo 180</strong></td>
                                                <td>{{ result.tasa_modificada_conservando_plazo_180 || 'No disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Plazo Modificado Conservando Tasa 1,88%</strong></td>
                                                <td>{{ result.plazo_modificado_conservando_tasa_188 || 'No disponible' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Paginación -->
                <!-- Botones de paginación -->
                <div class="pagination">
                    <button
                        v-if="page > 1"
                        @click="fetchPaginatedResults(page - 1)"
                        class="btn btn-primary"
                        style="background-color: #2c8c73"
                        ;
                    >
                        Anterior
                    </button>
                    <button
                        @click="fetchPaginatedResults(page + 1)"
                        :disabled="page * perPage >= total"
                        class="btn btn-primary"
                        style="background-color: #2c8c73"
                    >
                        Siguiente
                    </button>
                </div>
                <br />
                <b-modal id="bv-modal-example" hide-footer size="xl">
                    <template #modal-title>
                        <span class="heading-title">
                            <i class="fa fa-chart-line mr-2" style="color: #2c8c73;"></i>
                            Nuevo Análisis de Cartera Avanzado
                        </span>
                    </template>
                    <div class="" style="background-color: #f9fafc; border-left: 4px solid #249fe3; border-radius: 4px">
                        <b-row style="padding: 16px">
                            <b-col cols="1" class="d-flex justify-content-center align-items-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM9 4C9 4.55228 8.55228 5 8 5C7.44772 5 7 4.55228 7 4C7 3.44772 7.44772 3 8 3C8.55228 3 9 3.44772 9 4ZM7 7C6.44772 7 6 7.44772 6 8C6 8.55229 6.44772 9 7 9V12C7 12.5523 7.44772 13 8 13H9C9.55228 13 10 12.5523 10 12C10 11.4477 9.55228 11 9 11V8C9 7.44772 8.55228 7 8 7H7Z"
                                        fill="#20A0E9"
                                    />
                                </svg>
                            </b-col>
                            <b-col cols="11" class="d-flex justify-content-center align-items-center">
                                <p class="modal-text">
                                    Por favor, asegúrese de que el archivo Excel contiene la columna <strong>'cédulas'</strong> (obligatoria)
                                    y opcionalmente: <strong>operación, valor desembolso, saldo capital original, intereses corrientes,
                                    intereses de mora, seguros, otros conceptos, tasa pactada, respetar tasa pactada, plazo pactado,
                                    cuota pactada, respetar cuota pactada</strong>.
                                </p>
                            </b-col>
                        </b-row>
                    </div>
                    <b-row class="py-3">
                        <div class="col-md-6">
                            <b-form-group label="Política de Portafolio: *" label-for="politica_portafolio">
                                <b-form-select
                                    id="politica_portafolio"
                                    v-model="selectedPoliticaPortafolio"
                                    :options="politicasPortafolioOptions"
                                    class="input_style_b form-control2"
                                >
                                    <template #first>
                                        <b-form-select-option :value="null" disabled>Seleccione una política de portafolio</b-form-select-option>
                                    </template>
                                </b-form-select>
                            </b-form-group>
                        </div>

                        <div class="col-md-6">
                            <b-form-group label="Política de Fondo: *" label-for="politica_fondo">
                                <b-form-select
                                    id="politica_fondo"
                                    v-model="selectedPoliticaFondo"
                                    :options="politicasFondoOptions"
                                    class="input_style_b form-control2"
                                >
                                    <template #first>
                                        <b-form-select-option :value="null" disabled>Seleccione una política de fondo</b-form-select-option>
                                    </template>
                                </b-form-select>
                            </b-form-group>
                        </div>

                        <div class="col-md-6">
                            <b-form-group label="Mes (MM): *">
                                <b-form-input
                                    v-model="mes"
                                    placeholder="01"
                                    maxlength="2"
                                    class="input_style_b form-control2"
                                ></b-form-input>
                            </b-form-group>
                        </div>

                        <div class="col-md-6">
                            <b-form-group label="Año (YYYY): *">
                                <b-form-input
                                    v-model="año"
                                    placeholder="2025"
                                    maxlength="4"
                                    class="input_style_b form-control2"
                                ></b-form-input>
                            </b-form-group>
                        </div>
                        <b-col
                            cols="12"
                            style="
                                min-height: 150px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                cursor: pointer;
                            "
                            @click="triggerFileInput"
                            @dragover.prevent="handleDragOver"
                            @dragleave.prevent="handleDragLeave"
                            @drop.prevent="handleDrop"
                        >
                            <div
                                style="
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                "
                            >
                                <UploadFile class="mb-2" />
                                <p class="text-center" style="margin-bottom: 0.5rem">
                                    Arrastre o suelte el archivo <br />
                                    o
                                </p>
                                <CustomButton text="Seleccionar archivo" :color="'white'" />
                            </div>
                            <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none" />
                        </b-col>
                    </b-row>
                    <div class="d-flex justify-content-center align-item-center mb-5" style="width: 100%" v-if="file">
                        <div
                            style="
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                width: 300px;
                                border-bottom: 1px solid #babcbe;
                                padding: 8px;
                            "
                        >
                            <span style="font-size: 12px; font-weight: 400; line-height: 15.62px; color: black">{{
                                file.name
                            }}</span>
                            <button style="padding: 0; margin: 0; border: none; background: none" @click="deleteFile">
                                <Trash />
                            </button>
                        </div>
                    </div>
                    <CustomButton @click="uploadFile($bvModal)" text="Subir archivo" v-if="file" />
                    <CustomButton @click="$bvModal.hide('bv-modal-example')" :color="'white'" text="Cerrar" />
                </b-modal>
            </div>
        </div>

        <!-- Mensaje de error -->
        <div v-if="error" class="alert alert-danger mt-3">
            {{ error }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import * as XLSX from 'xlsx';
import CustomButton from '../../customComponents/CustomButton.vue';
import Lupa from '../../icons/Lupa.vue';
import UploadFile from '../../icons/UploadFile.vue';
import Trash from '../../icons/Trash.vue';
import jsPDF from 'jspdf'; // Si utilizas exportToPDF
import 'jspdf-autotable'; // Plugin para tablas en jsPDF

export default {
    name: 'DemographicDataAvanzado',
    components: {
        CustomButton,
        Lupa,
        UploadFile,
        Trash
    },
    data() {
        return {
            file: null,
            isLoading: false,
            results: [],
            searchQuery: '',
            error: null,
            recentConsultations: [],
            showRecentConsultations: false,
            page: 1,
            perPage: 50,
            total: 0,
            mes: '',
            año: '',
            expandedRows: [],

            // Políticas
            selectedPoliticaPortafolio: null,
            selectedPoliticaFondo: null,
            politicasPortafolio: [],
            politicasFondo: [],

            uploadProgress: {
                show: false,
                steps: [
                    { id: 1, text: 'Subiendo archivo', status: 'pending', icon: '📤' },
                    { id: 2, text: 'Validando datos', status: 'pending', icon: '✅' },
                    { id: 3, text: 'Cruzando AMI', status: 'pending', icon: '🔄' },
                    { id: 4, text: 'Calculando resultados', status: 'pending', icon: '🧮' },
                    { id: 5, text: 'Generando salida', status: 'pending', icon: '📊' },
                    { id: 6, text: 'Listo', status: 'pending', icon: '🎉' }
                ]
            },

            // Variables para guardar análisis
            nombreArchivo: '',
            descripcionAnalisis: '',
            isSavingAnalisis: false
        };
    },
    watch: {
        '$route.query': {
            immediate: true,
            handler() {
                this.initFromQuery();
            }
        }
    },
    computed: {
        filteredResults() {
            if (!this.results || !Array.isArray(this.results)) {
                return [];
            }
            return this.results.filter(result => {
                if (!result || !result.doc) {
                    return false;
                }
                if (this.searchQuery.length < 3) {
                    return true;
                }
                return result.doc.toString().includes(this.searchQuery);
            });
        },
        politicasPortafolioOptions() {
            return this.politicasPortafolio
                .filter(p => p.activo)
                .map(p => ({
                    value: p.id,
                    text: p.nombre
                }));
        },
        politicasFondoOptions() {
            return this.politicasFondo
                .filter(f => f.activo)
                .map(f => ({
                    value: f.id,
                    text: f.nombre_fondo
                }));
        }
    },
    methods: {
        initFromQuery () {
            console.log('[initFromQuery] ');
    const urlParams = new URLSearchParams(window.location.search)
    this.mes  = urlParams.get('mes')  || ''
    this.año  = urlParams.get('año')  || urlParams.get('anio') || ''

    console.log('[initFromQuery] ', { mes: this.mes, año: this.año })

    if (this.mes && this.año && !this.results.length) {
      this.fetchPaginatedResults(1)
    }
  },
        capitalize(text) {
            if (!text) return '';
            return text.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
        },
        deleteFile() {
            this.file = null;
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileUpload(event) {
            this.file = event.target.files[0];
            if (this.file) {
                this.nombreArchivo = this.file.name;
            }
        },
        handleDragOver(event) {
            this.isDragging = true;
        },
        handleDragLeave(event) {
            this.isDragging = false;
        },
        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file) {
                this.file = file;
                this.handleFileUpload({ target: { files: [file] } });
                console.log('Archivo cargado desde drag & drop:', file);
            }
            this.isDragging = false;
        },
        async uploadFile(modal) {
            if (!this.selectedPoliticaPortafolio) {
                this.$bvToast.toast('Debe seleccionar una política de portafolio', {
                    title: 'Validación',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 3000
                });
                return;
            }
            if (!this.selectedPoliticaFondo) {
                this.$bvToast.toast('Debe seleccionar una política de fondo', {
                    title: 'Validación',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 3000
                });
                return;
            }
            if (!this.file) {
                this.$bvToast.toast('Seleccione un archivo primero', {
                    title: 'Validación',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 3000
                });
                return;
            }
            if (!this.isValidMonthYear()) {
                this.$bvToast.toast('Por favor, ingrese un mes válido (MM) y un año válido (YYYY).', {
                    title: 'Validación',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 3000
                });
                return;
            }

            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('mes', this.mes);
            formData.append('año', this.año);
            formData.append('politica_portafolio_id', this.selectedPoliticaPortafolio);
            formData.append('politica_fondo_id', this.selectedPoliticaFondo);

            try {
                // Mostrar log de progreso
                this.uploadProgress.show = true;
                this.resetProgressSteps();

                // Paso 1: Subiendo archivo
                this.updateProgressStep(1, 'active');
                await this.delay(800);

                // Limpiar resultados previos
                this.results = [];

                let response = await axios.post('/demografico-avanzado/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.updateProgressStep(1, 'completed');

                // Paso 2: Validando datos
                this.updateProgressStep(2, 'active');
                await this.delay(1000);
                this.updateProgressStep(2, 'completed');

                // Paso 3: Cruzando AMI
                this.updateProgressStep(3, 'active');
                await this.delay(1200);
                this.updateProgressStep(3, 'completed');

                // Paso 4: Calculando resultados
                this.updateProgressStep(4, 'active');
                await this.delay(1000);
                this.updateProgressStep(4, 'completed');

                // Paso 5: Generando salida
                this.updateProgressStep(5, 'active');
                modal.hide('bv-modal-example');
                this.error = null;

                // Actualizar la URL con los parámetros mes y año
                const newUrl = `${window.location.pathname}?mes=${this.mes}&año=${this.año}`;
                window.history.pushState({}, '', newUrl);

                // Ahora obtener datos de la primera página sin subir archivo nuevamente
                await this.fetchPaginatedResults(1);
                this.updateProgressStep(5, 'completed');

                // Paso 6: Listo
                this.updateProgressStep(6, 'active');
                await this.delay(800);
                this.updateProgressStep(6, 'completed');

                // Ocultar el log después de un momento
                await this.delay(1500);
                this.uploadProgress.show = false;
            } catch (error) {
                this.uploadProgress.show = false;
                this.error = error.response ? error.response.data.error : 'Error subiendo el archivo';
            } finally {
                this.isLoading = false;
            }
        },
        resetProgressSteps() {
            this.uploadProgress.steps.forEach(step => {
                step.status = 'pending';
            });
        },
        updateProgressStep(stepId, status) {
            const step = this.uploadProgress.steps.find(s => s.id === stepId);
            if (step) {
                step.status = status;
            }
        },
        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        async fetchPaginatedResults (page) {
      this.isLoading = true
      try {
        console.log('[DemographicDataAvanzado] fetch', { page, mes: this.mes, año: this.año })
        const { data } = await axios.get('/demografico-avanzado/fetch-paginated-results', {
          params: {
            page,
            perPage: this.perPage,
            mes: this.mes,
            año: this.año          // ← siempre con tilde
          }
        })

        // LOG COMPLETO DE LA RESPUESTA
        console.log('========================================')
        console.log('RESPUESTA COMPLETA DEL SERVIDOR:')
        console.log(JSON.stringify(data, null, 2))
        console.log('========================================')
        console.log('PRIMER REGISTRO (si existe):')
        if (data.data && data.data.length > 0) {
          console.log(JSON.stringify(data.data[0], null, 2))
        }
        console.log('========================================')

        this.results = (data.data || []).map(r => ({
          ...r,
          showCupones: false,
          showEmbargos: false,
          showDescuentos: false
        }))
        this.total = data.total
        this.page  = data.page

        console.log('Total de resultados procesados:', this.results.length)
      } catch (e) {
        console.error('ERROR en fetchPaginatedResults:', e)
        this.error = e.response?.data?.error || 'Error al cargar datos'
      } finally {
        this.isLoading = false
      }

  }
,
        isValidMonthYear() {
            const mesRegex = /^(0[1-9]|1[0-2])$/;
            const añoRegex = /^[0-9]{4}$/;
            return mesRegex.test(this.mes) && añoRegex.test(this.año);
        },
        loadConsultationData(data) {
            // Asegúrate de que los datos cargados tengan las propiedades necesarias
            this.results = data
                .filter(item => item && typeof item === 'object')
                .map(item => ({
                    ...item,
                    showCupones: false,
                    showEmbargos: false,
                    showDescuentos: false
                }));
        },
        toggleRecentConsultations() {
            if (!this.showRecentConsultations) {
                this.fetchRecentConsultations();
            }
            this.showRecentConsultations = !this.showRecentConsultations;
        },
        toggleDetails(result, type) {
            const key = `${result.doc}-${type}`;
            const index = this.expandedRows.indexOf(key);
            if (index > -1) {
                // Si ya está expandido, lo eliminamos
                this.expandedRows.splice(index, 1);
            } else {
                // Si no está expandido, lo añadimos
                this.expandedRows.push(key);
            }
        },
        isRowExpanded(result, type) {
            const key = `${result.doc}-${type}`;
            return this.expandedRows.includes(key);
        },
        closeExpandedRows() {
            this.expandedRows = [];
        },
        exportToPDF() {
            const doc = new jsPDF('landscape');
            const greenColor = [44, 140, 115]; // Color verde GAF

            // Página 1: Información General Completa
            doc.setFontSize(14);
            doc.setTextColor(44, 140, 115);
            doc.text('Análisis de Cartera Avanzado - Información General', 14, 15);

            const columns1 = [
                'Operación',
                'Cédula',
                'Nombre',
                'F. Nac.',
                'Edad',
                'Contrato',
                'Cargo',
                'Situación',
                'Pagaduría',
                'Cupo Libre',
                'Colp.',
                'Fidu.',
                'Fopep'
            ];
            const rows1 = this.filteredResults.map(item => [
                item.operacion || 'N/D',
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 25),
                item.fecha_nacimiento || 'N/D',
                item.edad || 'N/D',
                (item.tipo_contrato || 'N/D').substring(0, 10),
                (item.cargo || 'N/D').substring(0, 18),
                (item.situacion_laboral || 'N/D').substring(0, 12),
                (item.pagaduria || 'N/D').substring(0, 20),
                this.formatCurrency(item.cupo_libre),
                item.colpensiones ? 'Sí' : 'No',
                item.fiducidiaria ? 'Sí' : 'No',
                item.fopep ? 'Sí' : 'No'
            ]);
            doc.autoTable({
                head: [columns1],
                body: rows1,
                startY: 20,
                styles: { fontSize: 5.5, cellPadding: 1.5 },
                headStyles: { fillColor: greenColor, fontSize: 6, fontStyle: 'bold' }
            });

            // Página 2: Detalle de Crédito
            doc.addPage();
            doc.setFontSize(14);
            doc.text('Análisis de Cartera Avanzado - Detalle de Crédito', 14, 15);

            const columns2 = [
                'Cédula',
                'Nombre',
                'Val. Desemb.',
                'Saldo Cap.',
                'Int. Corr.',
                'Int. Mora',
                'Seguros',
                'Otros',
                'Total Oblig.'
            ];
            const rows2 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 25),
                this.formatCurrency(item.valor_desembolso),
                this.formatCurrency(item.saldo_capital_original),
                this.formatCurrency(item.intereses_corrientes),
                this.formatCurrency(item.intereses_de_mora),
                this.formatCurrency(item.seguros),
                this.formatCurrency(item.otros_conceptos),
                this.formatCurrency(item.total_obligacion)
            ]);
            doc.autoTable({
                head: [columns2],
                body: rows2,
                startY: 20,
                styles: { fontSize: 5.5, cellPadding: 1.5 },
                headStyles: { fillColor: greenColor, fontSize: 6, fontStyle: 'bold' },
                columnStyles: {
                    2: { halign: 'right' },
                    3: { halign: 'right' },
                    4: { halign: 'right' },
                    5: { halign: 'right' },
                    6: { halign: 'right' },
                    7: { halign: 'right' },
                    8: { halign: 'right', fontStyle: 'bold' }
                }
            });

            // Página 3: Detalle de Portafolio
            doc.addPage();
            doc.setFontSize(14);
            doc.text('Análisis de Cartera Avanzado - Detalle Portafolio', 14, 15);

            const columns3 = [
                'Cédula',
                'Nombre',
                'Costo Portaf.',
                'Comisión',
                'Re-Inc. GAF',
                'Coadmin.',
                'Seguro V.D',
                'Fiduciarios',
                'Centrales',
                'Tecnología',
                'SUB TOTAL'
            ];
            const rows3 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 20),
                this.formatCurrency(item.costo_compra_portafolio),
                this.formatCurrency(item.costo_comision_comercial),
                this.formatCurrency(item.costo_reincorporacion_gaf),
                this.formatCurrency(item.costo_coadministracion),
                this.formatCurrency(item.costo_seguro_vd),
                this.formatCurrency(item.costos_fiduciarios),
                this.formatCurrency(item.reporte_centrales),
                this.formatCurrency(item.tecnologia),
                this.formatCurrency(item.sub_total_costo_compra_adm)
            ]);
            doc.autoTable({
                head: [columns3],
                body: rows3,
                startY: 20,
                styles: { fontSize: 5, cellPadding: 1.5 },
                headStyles: { fillColor: greenColor, fontSize: 5.5, fontStyle: 'bold' },
                columnStyles: {
                    2: { halign: 'right' },
                    3: { halign: 'right' },
                    4: { halign: 'right' },
                    5: { halign: 'right' },
                    6: { halign: 'right' },
                    7: { halign: 'right' },
                    8: { halign: 'right' },
                    9: { halign: 'right' },
                    10: { halign: 'right', fontStyle: 'bold' }
                }
            });

            // Página 4: Detalle de Cupo
            doc.addPage();
            doc.setFontSize(14);
            doc.text('Análisis de Cartera Avanzado - Detalle de Cupo', 14, 15);

            const columns4 = [
                'Cédula',
                'Nombre',
                'Cuota Inc. Prev.',
                'Cupo Sem',
                'Cupo Colp.',
                'Cupo Fopep',
                'Cupo Fidu.',
                'TOTAL CUPO'
            ];
            const rows4 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 25),
                this.formatCurrency(item.cuota_incorporada_previamente),
                this.formatCurrency(item.cupo_sem),
                this.formatCurrency(item.cupo_colpensiones),
                this.formatCurrency(item.cupo_fopep),
                this.formatCurrency(item.cupo_fiduprevisora),
                this.formatCurrency(item.total_cupo_disponible)
            ]);
            doc.autoTable({
                head: [columns4],
                body: rows4,
                startY: 20,
                styles: { fontSize: 6, cellPadding: 1.5 },
                headStyles: { fillColor: greenColor, fontSize: 6.5, fontStyle: 'bold' },
                columnStyles: {
                    2: { halign: 'right' },
                    3: { halign: 'right' },
                    4: { halign: 'right' },
                    5: { halign: 'right' },
                    6: { halign: 'right' },
                    7: { halign: 'right', fontStyle: 'bold' }
                }
            });

            // Página 5: Cuota a Incorporar (Parte 1)
            doc.addPage();
            doc.setFontSize(14);
            doc.text('Análisis de Cartera Avanzado - Cuota a Incorporar (Parte 1)', 14, 15);

            const columns5 = [
                'Cédula',
                'Nombre',
                'Tasa Pact.',
                'Resp. Tasa',
                'Tasa Nueva CK',
                'Plazo Pact.',
                'Resp. Plazo',
                'Plazo Nuevo CK',
                'Cuota Pact.',
                'Resp. Cuota'
            ];
            const rows5 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 20),
                item.tasa_pactada || 'N/D',
                item.respetar_tasa_pactada || 'N/D',
                item.tasa_nueva_libranza_ck || 'N/D',
                item.plazo_pactado || 'N/D',
                item.respetar_plazo_pactado || 'N/D',
                item.plazo_nueva_libranza_ck || 'N/D',
                this.formatCurrency(item.cuota_pactada),
                item.respetar_cuota_pactada || 'N/D'
            ]);
            doc.autoTable({
                head: [columns5],
                body: rows5,
                startY: 20,
                styles: { fontSize: 5.5, cellPadding: 1.5 },
                headStyles: { fillColor: greenColor, fontSize: 6, fontStyle: 'bold' }
            });

            // Página 6: Cuota a Incorporar (Parte 2)
            doc.addPage();
            doc.setFontSize(14);
            doc.text('Análisis de Cartera Avanzado - Cuota a Incorporar (Parte 2)', 14, 15);

            const columns6 = [
                'Cédula',
                'Nombre',
                'CUOTA A INCORP.',
                'Tasa Modif. (Plazo 180)',
                'Plazo Modif. (Tasa 1.88%)'
            ];
            const rows6 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 30),
                this.formatCurrency(item.cuota_a_incorporar),
                item.tasa_modificada_conservando_plazo_180 ?
                    (item.tasa_modificada_conservando_plazo_180 + '%') : 'N/D',
                item.plazo_modificado_conservando_tasa_188 ?
                    (item.plazo_modificado_conservando_tasa_188 + ' meses') : 'N/D'
            ]);
            doc.autoTable({
                head: [columns6],
                body: rows6,
                startY: 20,
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: greenColor, fontSize: 8, fontStyle: 'bold' },
                columnStyles: {
                    2: { halign: 'right', fontStyle: 'bold' },
                    3: { halign: 'center' },
                    4: { halign: 'center' }
                }
            });

            // Guardar PDF con fecha
            const fecha = new Date().toISOString().slice(0, 10);
            doc.save(`analisis_cartera_avanzado_${fecha}.pdf`);
        },
        async exportToExcel() {
            try {
                // Validar que haya datos
                if (!this.results || this.results.length === 0) {
                    this.$bvToast.toast('No hay datos para exportar', {
                        title: 'Advertencia',
                        variant: 'warning',
                        solid: true,
                        autoHideDelay: 3000
                    });
                    return;
                }

                // Mostrar mensaje de carga
                this.$bvToast.toast('Generando archivo Excel...', {
                    title: 'Exportando',
                    variant: 'info',
                    solid: true,
                    autoHideDelay: 2000
                });

                // Enviar datos al backend para generar Excel con formato
                const response = await axios.post('/demografico-avanzado/exportar-excel', {
                    datos: this.results
                }, {
                    responseType: 'blob' // Importante para recibir el archivo
                });

                // Crear URL del blob y descargar
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                const fecha = new Date().toISOString().slice(0, 10);
                link.setAttribute('download', `analisis_cartera_avanzado_${fecha}.xlsx`);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);

                this.$bvToast.toast('Archivo Excel generado exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

            } catch (error) {
                console.error('Error al exportar Excel:', error);
                this.$bvToast.toast('Error al generar el archivo Excel', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            }
        },

        // Helper para formatear embargos
        formatEmbargos(embargos) {
            if (!embargos || !embargos.length) return 'No hay embargos';
            return embargos
                .map(
                    e =>
                        `Documento: ${e.docdeman || 'N/A'}, Entidad: ${e.entidaddeman || 'N/A'}, Valor: ${
                            e.valor || e.netoemb
                        }`
                )
                .join('\r\n');
        },

        // Helper para formatear cupones
        formatCupones(cupones) {
            if (!cupones || !cupones.length) return 'No hay cupones';
            return cupones.map(c => `Concepto: ${c.concept || 'N/A'}, Egresos: ${c.egresos}`).join('\r\n');
        },

        // Helper para formatear descuentos
        formatDescuentos(descuentos) {
            if (!descuentos || !descuentos.length) return 'No hay descuentos';
            return descuentos.map(d => `Mliquid: ${d.mliquid || 'N/A'}, Valor: ${d.valor}`).join('\r\n');
        },
        async fetchRecentConsultations() {
            try {
                let response = await axios.get('/demografico/recent-consultations');
                this.recentConsultations = response.data;
            } catch (error) {
                console.error('Error al obtener consultas recientes:', error);
            }
        },
        formatCurrency(value) {
            if (value == null || isNaN(value)) {
                return 'No disponible';
            }
            return new Intl.NumberFormat('es-CO').format(value);
        },
        hasDetalleCredito(result) {
            // Verifica si hay al menos un valor de crédito disponible
            return result.valor_desembolso != null ||
                   result.saldo_capital_original != null ||
                   result.intereses_corrientes != null ||
                   result.intereses_de_mora != null ||
                   result.seguros != null ||
                   result.otros_conceptos != null;
        },
        hasDetallePortafolio(result) {
            // Verifica si hay saldo capital original para calcular portafolio
            return result.saldo_capital_original != null && result.saldo_capital_original > 0;
        },
        hasDetalleAMI(result) {
            // Verifica si hay datos de cupo disponibles
            return result.cuota_incorporada_previamente != null ||
                   result.cupo_sem != null ||
                   result.cupo_colpensiones != null ||
                   result.cupo_fopep != null ||
                   result.cupo_fiduprevisora != null;
        },

        async loadPoliticasPortafolio() {
            try {
                const response = await axios.get('/politicas-portafolio/get');
                this.politicasPortafolio = response.data.politicas || [];
            } catch (error) {
                console.error('Error al cargar políticas de portafolio:', error);
                this.politicasPortafolio = [];
            }
        },

        async loadPoliticasFondo() {
            try {
                const response = await axios.get('/politicas-portafolio/fondos/get');
                this.politicasFondo = response.data.fondos || [];
            } catch (error) {
                console.error('Error al cargar políticas de fondo:', error);
                this.politicasFondo = [];
            }
        },

        getPoliticaPortafolioNombre() {
            if (!this.selectedPoliticaPortafolio) return 'No seleccionada';
            const politica = this.politicasPortafolio.find(p => p.id === this.selectedPoliticaPortafolio);
            return politica ? politica.nombre : 'No seleccionada';
        },

        getPoliticaFondoNombre() {
            if (!this.selectedPoliticaFondo) return 'No seleccionado';
            const fondo = this.politicasFondo.find(f => f.id === this.selectedPoliticaFondo);
            return fondo ? fondo.nombre_fondo : 'No seleccionado';
        },

        guardarAnalisis() {
            console.log('=== INICIANDO GUARDADO DE ANÁLISIS ===');
            console.log('Results:', this.results);
            console.log('Results length:', this.results ? this.results.length : 0);
            console.log('Mes:', this.mes);
            console.log('Año:', this.año);
            console.log('Política Portafolio:', this.selectedPoliticaPortafolio);
            console.log('Política Fondo:', this.selectedPoliticaFondo);

            // Validaciones previas
            if (!this.results || this.results.length === 0) {
                console.log('FALLO: No hay datos procesados');
                this.$bvToast.toast('No hay datos para guardar. Por favor, cargue y procese un archivo primero.', {
                    title: 'Advertencia',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 4000
                });
                return;
            }

            if (!this.mes || !this.año) {
                console.log('FALLO: Falta mes o año');
                this.$bvToast.toast('Por favor, ingrese el mes y año del análisis antes de guardar.', {
                    title: 'Advertencia',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 4000
                });
                return;
            }

            if (!this.selectedPoliticaPortafolio || !this.selectedPoliticaFondo) {
                console.log('FALLO: Falta seleccionar políticas');
                this.$bvToast.toast('Por favor, seleccione las políticas de portafolio y fondo antes de guardar.', {
                    title: 'Advertencia',
                    variant: 'warning',
                    solid: true,
                    autoHideDelay: 4000
                });
                return;
            }

            console.log('✓ Todas las validaciones pasaron, abriendo modal...');
            console.log('$bvModal disponible?', this.$bvModal);
            console.log('Intentando abrir modal con ID: modal-guardar-analisis');

            // Limpiar descripción y abrir modal
            this.descripcionAnalisis = '';

            // Intentar abrir el modal
            try {
                this.$bvModal.show('modal-guardar-analisis');
                console.log('Comando show() ejecutado');
            } catch (error) {
                console.error('Error al abrir modal:', error);
            }

            // También intentar con $nextTick por si es un problema de timing
            this.$nextTick(() => {
                console.log('Intentando abrir modal en nextTick...');
                this.$bvModal.show('modal-guardar-analisis');

                // Verificar si el modal está en el DOM
                const modalElement = document.getElementById('modal-guardar-analisis');
                console.log('Modal element en DOM:', modalElement);

                // Verificar si existe el modal wrapper de Bootstrap
                const modalBackdrop = document.querySelector('.modal-backdrop');
                const modalDialog = document.querySelector('.modal-dialog');
                console.log('Modal backdrop:', modalBackdrop);
                console.log('Modal dialog:', modalDialog);

                // Verificar clases del modal si existe
                if (modalElement) {
                    console.log('Clases del modal:', modalElement.className);
                    console.log('Estilo display:', window.getComputedStyle(modalElement).display);
                }
            });
        },

        async confirmarGuardarAnalisis() {
            this.isSavingAnalisis = true;

            try {
                const response = await axios.post('/demografico-avanzado/guardar-analisis', {
                    mes: this.mes,
                    anio: this.año,
                    politica_portafolio_id: this.selectedPoliticaPortafolio,
                    politica_fondo_id: this.selectedPoliticaFondo,
                    nombre_archivo: this.nombreArchivo || `analisis_${Date.now()}.xlsx`,
                    descripcion: this.descripcionAnalisis || null,
                    datos_procesados: this.results
                });

                if (response.data.success) {
                    this.$bvModal.hide('modal-guardar-analisis');

                    this.$bvToast.toast(
                        `Análisis guardado exitosamente con ID: ${response.data.estudio_id}. Los datos permanecen cargados. Use "Nuevo Análisis" si desea limpiar.`,
                        {
                            title: '✅ Guardado Exitoso',
                            variant: 'success',
                            solid: true,
                            autoHideDelay: 7000
                        }
                    );

                    // IMPORTANTE: Solo limpiar el campo de descripción del modal
                    // NO limpiar los datos del análisis (results, mes, año, etc.)
                    this.descripcionAnalisis = '';

                    // Los datos permanecen cargados para que el usuario pueda:
                    // - Exportar a Excel/PDF
                    // - Revisar los resultados
                    // - Guardar nuevamente con otra descripción si lo desea
                    // Para limpiar debe usar el botón "Nuevo Análisis"
                } else {
                    throw new Error(response.data.message || 'Error desconocido');
                }
            } catch (error) {
                console.error('Error al guardar análisis:', error);

                const errorMessage = error.response?.data?.error || error.message || 'Error al guardar el análisis';

                this.$bvToast.toast(errorMessage, {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 5000
                });
            } finally {
                this.isSavingAnalisis = false;
            }
        },

        iniciarNuevoAnalisis() {
            // Si hay datos actuales, mostrar modal de confirmación
            if (this.results && this.results.length > 0) {
                this.$bvModal.show('modal-nuevo-analisis');
            } else {
                // Si no hay datos, directamente abrir el modal de carga
                this.$bvModal.show('bv-modal-example');
            }
        },

        async confirmarNuevoAnalisis() {
            try {
                // Llamar al backend para limpiar caché
                await axios.post('/demografico-avanzado/limpiar-cache');

                // Limpiar datos del frontend
                this.results = [];
                this.file = null;
                this.nombreArchivo = '';
                this.mes = '';
                this.año = '';
                this.page = 1;
                this.total = 0;
                this.searchQuery = '';
                this.expandedRows = [];
                this.descripcionAnalisis = '';
                this.selectedPoliticaPortafolio = null;
                this.selectedPoliticaFondo = null;

                // Resetear el input de archivo
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }

                // Cerrar modal de confirmación
                this.$bvModal.hide('modal-nuevo-analisis');

                // Abrir modal para nuevo análisis
                this.$bvModal.show('bv-modal-example');

                // Mostrar mensaje de éxito
                this.$bvToast.toast('Datos limpiados correctamente. Puede cargar un nuevo archivo.', {
                    title: 'Nuevo Análisis',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

            } catch (error) {
                console.error('Error al limpiar datos:', error);

                this.$bvToast.toast('Error al limpiar los datos. Por favor, recargue la página.', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 4000
                });
            }
        }
    },
    mounted() {
        this.initFromQuery();
        this.loadPoliticasPortafolio();
        this.loadPoliticasFondo();
    }
};
</script>

<style scoped>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.panel-body {
    padding: 15px;
}

.table-responsive {
    overflow-x: auto;
}

.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.form-group {
    margin-bottom: 15px;
}

.float-right {
    float: right;
}

.recent-consultations {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
}
::v-deep {
    & .modal-header {
        border-bottom: none;
    }
    & .modal-dialog {
        min-width: 600px;
    }
}
.modal-text {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
    margin: 0;
}
.drag-over {
    border: 2px dashed #007bff;
    background-color: #f0f8ff;
}
th {
    white-space: nowrap; /* Evita el salto de línea */
}
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

/* Progress Log Styles */
.progress-log-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 1100;
    display: flex;
    justify-content: center;
    align-items: center;
}

.progress-log-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px 40px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.progress-title {
    color: #2c8c73;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    text-align: center;
}

.progress-steps {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.progress-step {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.progress-step.step-pending {
    opacity: 0.5;
}

.progress-step.step-active {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    opacity: 1;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
}

.progress-step.step-completed {
    background: #e8f5e9;
    border-left: 4px solid #2c8c73;
    opacity: 1;
}

.step-icon {
    font-size: 24px;
    margin-right: 12px;
    min-width: 30px;
}

.step-text {
    flex: 1;
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
}

.step-loader {
    font-size: 18px;
    color: #2196f3;
    font-weight: bold;
    animation: blink 1s infinite;
}

.step-check {
    font-size: 20px;
    color: #2c8c73;
    font-weight: bold;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Custom Table Styles */
.table-container {
    overflow-x: auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background: white;
}

.custom-analysis-table {
    margin-bottom: 0;
    font-size: 14px;
}

.thead-custom {
    background: linear-gradient(135deg, #2c8c73 0%, #239167 100%);
    color: white;
}

.thead-custom th {
    border: none !important;
    font-weight: 600;
    font-size: 13px;
    padding: 12px 8px;
    vertical-align: middle;
    white-space: nowrap;
}

.thead-custom .th-operacion { min-width: 100px; }
.thead-custom .th-cedula { min-width: 100px; }
.thead-custom .th-nombre { min-width: 200px; }
.thead-custom .th-secretaria { min-width: 150px; }
.thead-custom .th-colp { min-width: 70px; }
.thead-custom .th-fidu { min-width: 70px; }
.thead-custom .th-fopep { min-width: 70px; }
.thead-custom .th-edad { min-width: 60px; }
.thead-custom .th-actions { min-width: 90px; }

.table-row-custom {
    transition: all 0.2s ease;
}

.table-row-custom:hover {
    background-color: #f8f9fa !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.table-row-custom td {
    padding: 10px 8px;
    vertical-align: middle;
    border-color: #e9ecef;
}

.td-cedula {
    color: #2c8c73;
    font-weight: 600;
}

.td-nombre {
    font-weight: 500;
    color: #2c3e50;
}

.td-secretaria {
    color: #6c757d;
    font-size: 13px;
}

.td-badge {
    padding: 4px !important;
}

.badge-small {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 600;
}

.td-actions button {
    min-width: 32px;
    padding: 4px 8px;
}

.td-actions .fa {
    font-size: 14px;
}

.input_style_b {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 8px 12px;
    background-color: #ffffff;
    color: #2c3e50;
    font-weight: 500;
    transition: border-color 0.2s;
}

.input_style_b:focus {
    border-color: #2c8c73;
    box-shadow: 0 0 0 0.2rem rgba(44, 140, 115, 0.25);
}

.heading-title {
    color: #2c3e50;
    font-weight: 600;
}
</style>
