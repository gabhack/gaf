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
                <p>
                    Aún no tienes archivos <br />
                    cargados, puedes...
                </p>
                <CustomButton text="Cargar archivo" @click="$bvModal.show('bv-modal-example')" />
            </div>
            <b-modal id="bv-modal-example" hide-footer style="min-width: 1000px">
                <template #modal-title><span class="heading-title">Agregar datos demográficos</span></template>
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
                    <div class="col-md-12">
                        <b-form-group label="Mes (MM):">
                            <b-form-input
                                v-model="mes"
                                placeholder="01"
                                maxlength="2"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-12">
                        <b-form-group label="Año (YYYY):">
                            <b-form-input
                                v-model="año"
                                placeholder="2024"
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
        </div>

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
            <b-row style="margin-left: 900px">
                <b-col cols="12" md="3" class="d-flex justify-content-start justify-content-md-end align-items-center">
                    <CustomButton
                        text="Cargar archivo"
                        @click="$bvModal.show('bv-modal-example')"
                        style="white-space: nowrap; margin-right: 8px"
                    />
                    <CustomButton
                        @click="exportToPDF"
                        class="btn btn-danger mr-2"
                        text="Exportar a PDF"
                        style="white-space: nowrap"
                    />
                    <CustomButton
                        @click="exportToExcel"
                        class="btn btn-success"
                        text="Exportar a Excel"
                        style="white-space: nowrap"
                    />
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
                <div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Operación</th>
                                <th>Cédula</th>
                                <th>Nombre del Cliente</th>
                                <th>Secretaria (SED - SEM)</th>
                                <th>Colpensiones</th>
                                <th>Fiduprevisora</th>
                                <th>Fopep</th>
                                <th>Edad</th>
                                <th>Detalle de Crédito</th>
                                <th>Detalle Portafolio</th>
                                <th>Cuota a Incorporar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fila Principal -->
                            <tr v-for="(result, index) in filteredResults" :key="result.doc + '-' + index">
                                <td>{{ result.operacion || 'No disponible' }}</td>
                                <td>{{ result.doc }}</td>
                                <td>{{ capitalize(result.nombre_usuario) || 'No disponible' }}</td>
                                <td>{{ capitalize(result.pagaduria) || 'No disponible' }}</td>
                                <td>{{ result.colpensiones ? 'Sí' : 'No' }}</td>
                                <td>{{ result.fiducidiaria ? 'Sí' : 'No' }}</td>
                                <td>{{ result.fopep ? 'Sí' : 'No' }}</td>
                                <td>{{ result.edad || 'No disponible' }}</td>
                                <td>
                                    <CustomButton
                                        v-if="hasDetalleCredito(result)"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'credito')"
                                        data-toggle="modal"
                                        data-target="#modalCredito"
                                        :text="
                                            isRowExpanded(result, 'credito')
                                                ? 'Ocultar Detalle'
                                                : 'Ver Detalle'
                                        "
                                    />
                                    <span v-else>No hay información</span>
                                </td>
                                <td>
                                    <CustomButton
                                        v-if="hasDetallePortafolio(result)"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'portafolio')"
                                        data-toggle="modal"
                                        data-target="#modalPortafolio"
                                        :text="
                                            isRowExpanded(result, 'portafolio')
                                                ? 'Ocultar Detalle'
                                                : 'Ver Detalle'
                                        "
                                    />
                                    <span v-else>No hay información</span>
                                </td>
                                <td>
                                    <CustomButton
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'cuotaIncorporar')"
                                        data-toggle="modal"
                                        data-target="#modalCuotaIncorporar"
                                        :text="
                                            isRowExpanded(result, 'cuotaIncorporar')
                                                ? 'Ocultar Detalle'
                                                : 'Ver Detalle'
                                        "
                                    />
                                </td>
                            </tr>
                            <!-- Fila para mostrar mensaje si no hay resultados -->
                            <tr v-if="filteredResults.length === 0">
                                <td colspan="11">No hay resultados</td>
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
                                                <td><strong>Costo Coadministración (3 Cuotas)</strong></td>
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
                <b-modal id="bv-modal-example" hide-footer style="min-width: 1000px">
                    <template #modal-title><span class="heading-title">Agregar datos demográficos</span></template>
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
                        <div class="col-md-12">
                            <b-form-group label="Mes (MM):">
                                <b-form-input
                                    v-model="mes"
                                    placeholder="01"
                                    maxlength="2"
                                    class="input_style_b form-control2"
                                ></b-form-input>
                            </b-form-group>
                        </div>

                        <div class="col-md-12">
                            <b-form-group label="Año (YYYY):">
                                <b-form-input
                                    v-model="año"
                                    placeholder="2024"
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
            expandedRows: [], // Lista para rastrear filas expandidas
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
            }
        };
    },
    created ()   { this.initFromQuery() }   ,
mounted ()   { this.initFromQuery() }   ,
activated () { this.initFromQuery() }   ,
watch: {
  '$route.query': { immediate: true, handler () { this.initFromQuery() } }
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
            if (!this.file) {
                alert('Seleccione un archivo primero');
                return;
            }
            if (!this.isValidMonthYear()) {
                alert('Por favor, ingrese un mes válido (MM) y un año válido (YYYY).');
                return;
            }

            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('mes', this.mes);
            formData.append('año', this.año);

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

            // Página 1: Información General
            doc.text('Análisis de Cartera Avanzado - Información General', 14, 15);
            const columns1 = [
                'Operación',
                'Cédula',
                'Nombre',
                'Secretaria',
                'Colp.',
                'Fidu.',
                'Fopep',
                'Edad'
            ];
            const rows1 = this.filteredResults.map(item => [
                item.operacion || 'N/D',
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 25),
                (item.pagaduria || 'N/D').substring(0, 20),
                item.colpensiones ? 'Sí' : 'No',
                item.fiducidiaria ? 'Sí' : 'No',
                item.fopep ? 'Sí' : 'No',
                item.edad || 'N/D'
            ]);
            doc.autoTable({
                head: [columns1],
                body: rows1,
                startY: 20,
                styles: { fontSize: 6, cellPadding: 1.5 },
                headStyles: { fillColor: [44, 140, 115], fontSize: 7 }
            });

            // Página 2: Detalle de Crédito
            doc.addPage();
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
                styles: { fontSize: 5.5, cellPadding: 1 },
                headStyles: { fillColor: [44, 140, 115], fontSize: 6 }
            });

            // Página 3: Detalle de Portafolio
            doc.addPage();
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
                styles: { fontSize: 5, cellPadding: 1 },
                headStyles: { fillColor: [44, 140, 115], fontSize: 5.5 }
            });

            // Página 4: Cuota a Incorporar
            doc.addPage();
            doc.text('Análisis de Cartera Avanzado - Cuota a Incorporar', 14, 15);
            const columns4 = [
                'Cédula',
                'Nombre',
                'Total Cupo',
                'Tasa Pact.',
                'Resp. Tasa',
                'Plazo Pact.',
                'Resp. Plazo',
                'Cuota Pact.',
                'Resp. Cuota',
                'Cuota a Inc.'
            ];
            const rows4 = this.filteredResults.map(item => [
                item.doc,
                (item.nombre_usuario || 'N/D').substring(0, 20),
                this.formatCurrency(item.total_cupo_disponible),
                item.tasa_pactada || 'N/D',
                item.respetar_tasa_pactada || 'N/D',
                item.plazo_pactado || 'N/D',
                item.respetar_plazo_pactado || 'N/D',
                this.formatCurrency(item.cuota_pactada),
                item.respetar_cuota_pactada || 'N/D',
                this.formatCurrency(item.cuota_a_incorporar)
            ]);
            doc.autoTable({
                head: [columns4],
                body: rows4,
                startY: 20,
                styles: { fontSize: 5, cellPadding: 1 },
                headStyles: { fillColor: [44, 140, 115], fontSize: 5.5 }
            });

            doc.save('analisis_cartera_avanzado.pdf');
        },
        exportToExcel() {
            const columns = [
                'Operación',
                'Cédula',
                'Nombre del Cliente',
                'Secretaria (SED - SEM)',
                'Colpensiones',
                'Fiduprevisora',
                'Fopep',
                'Edad',
                // Detalle de Crédito
                'Valor Desembolso',
                'Saldo Capital Original',
                'Intereses Corrientes',
                'Intereses de Mora',
                'Seguros',
                'Otros Conceptos',
                'Total Obligación',
                // Detalle Portafolio
                'Costo Compra Portafolio',
                'Costo Comisión Comercial',
                'Costo Re-Incorporación GAF',
                'Costo Coadministración (3 Cuotas)',
                'Costo Seguro V.D (3 Meses)',
                'Costos Fiduciarios (Fiducoomeva)',
                'Reporte Centrales ($10.000)',
                'Tecnología ($5.000)',
                'SUB TOTAL Costo Compra + Adm (NPL´S)',
                // Cuota a Incorporar
                'TOTAL CUPO DISPONIBLE',
                'Tasa Pactada',
                'RESPETAR TASA PACTADA',
                'Tasa Nueva Libranza Ck',
                'PLAZO PACTADO',
                'RESPETAR PLAZO PACTADO',
                'Plazo Nueva Libranza Ck',
                'CUOTA PACTADA',
                'RESPETAR CUOTA PACTADA',
                'CUOTA A INCORPORAR',
                'Tasa Modificada Conservando plazo 180',
                'Plazo Modificado Conservando Tasa 1,88%'
            ];

            const rows = this.results.map(item => [
                item.operacion || 'No disponible',
                item.doc || 'No disponible',
                item.nombre_usuario || 'No disponible',
                item.pagaduria || 'No disponible',
                item.colpensiones ? 'Sí' : 'No',
                item.fiducidiaria ? 'Sí' : 'No',
                item.fopep ? 'Sí' : 'No',
                item.edad || 'No disponible',
                // Detalle de Crédito
                item.valor_desembolso || 'No disponible',
                item.saldo_capital_original || 'No disponible',
                item.intereses_corrientes || 'No disponible',
                item.intereses_de_mora || 'No disponible',
                item.seguros || 'No disponible',
                item.otros_conceptos || 'No disponible',
                item.total_obligacion || 'No disponible',
                // Detalle Portafolio
                item.costo_compra_portafolio || 'No disponible',
                item.costo_comision_comercial || 'No disponible',
                item.costo_reincorporacion_gaf || 'No disponible',
                item.costo_coadministracion || 'No disponible',
                item.costo_seguro_vd || 'No disponible',
                item.costos_fiduciarios || 'No disponible',
                item.reporte_centrales || 'No disponible',
                item.tecnologia || 'No disponible',
                item.sub_total_costo_compra_adm || 'No disponible',
                // Cuota a Incorporar
                item.total_cupo_disponible || 'No disponible',
                item.tasa_pactada || 'No disponible',
                item.respetar_tasa_pactada || 'No disponible',
                item.tasa_nueva_libranza_ck || 'No disponible',
                item.plazo_pactado || 'No disponible',
                item.respetar_plazo_pactado || 'No disponible',
                item.plazo_nueva_libranza_ck || 'No disponible',
                item.cuota_pactada || 'No disponible',
                item.respetar_cuota_pactada || 'No disponible',
                item.cuota_a_incorporar || 'No disponible',
                item.tasa_modificada_conservando_plazo_180 || 'No disponible',
                item.plazo_modificado_conservando_tasa_188 || 'No disponible'
            ]);

            const worksheet = XLSX.utils.aoa_to_sheet([columns, ...rows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Análisis Cartera Avanzado');
            XLSX.writeFile(workbook, 'analisis_cartera_avanzado.xlsx');
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
        }
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
</style>
