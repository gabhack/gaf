<template>
    <div style="padding: 30px">
        <b-row>
            <b-col cols="12" md="9" style="margin-left: 28px">
                <h3 class="heading-title">Políticas de Portafolio y Fondos</h3>
                <p>
                    Configure y gestione múltiples políticas de portafolio y fondos con diferentes configuraciones.
                </p>
            </b-col>
        </b-row>

        <div class="panel mb-3 col-md-12">
            <div class="panel-body">
                <!-- Pestañas -->
                <b-tabs content-class="mt-3" active-nav-item-class="font-weight-bold">

                    <!-- PESTAÑA 1: POLÍTICAS DEL PORTAFOLIO -->
                    <b-tab title="Políticas del Portafolio" active>
                        <b-row class="mb-3">
                            <b-col cols="12" class="text-right">
                                <b-button
                                    @click="showCreatePoliticaModal"
                                    variant="primary"
                                    class="btn-create"
                                    style="background-color: #2c8c73; border-color: #2c8c73"
                                >
                                    + Nueva Política
                                </b-button>
                            </b-col>
                        </b-row>

                        <!-- Tabla de políticas -->
                        <b-table
                            v-if="politicas.length > 0"
                            :items="politicas"
                            :fields="fieldsPoliticas"
                            striped
                            hover
                            responsive
                            class="custom-table"
                        >
                            <template #cell(nombre)="data">
                                <strong>{{ data.item.nombre }}</strong>
                            </template>

                            <template #cell(porcentaje_portafolio)="data">
                                {{ formatPercent(data.item.porcentaje_portafolio) }}%
                            </template>

                            <template #cell(porcentaje_comision_comercial)="data">
                                {{ formatPercent(data.item.porcentaje_comision_comercial) }}%
                            </template>

                            <template #cell(porcentaje_reincorporacion_gaf)="data">
                                {{ formatPercent(data.item.porcentaje_reincorporacion_gaf) }}%
                            </template>

                            <template #cell(porcentaje_coadministracion)="data">
                                {{ formatPercent(data.item.porcentaje_coadministracion) }}%
                            </template>

                            <template #cell(porcentaje_costo_seguro_vd)="data">
                                {{ formatPercent(data.item.porcentaje_costo_seguro_vd) }}%
                            </template>

                            <template #cell(activo)="data">
                                <b-badge :variant="data.item.activo ? 'success' : 'secondary'">
                                    {{ data.item.activo ? 'Activa' : 'Inactiva' }}
                                </b-badge>
                            </template>

                            <template #cell(acciones)="data">
                                <b-button
                                    size="sm"
                                    variant="outline-primary"
                                    @click="showViewPoliticaModal(data.item)"
                                    class="mr-1"
                                >
                                    <i class="fa fa-eye"></i> Ver
                                </b-button>
                                <b-button
                                    size="sm"
                                    variant="outline-warning"
                                    @click="showEditPoliticaModal(data.item)"
                                    class="mr-1"
                                >
                                    <i class="fa fa-edit"></i> Editar
                                </b-button>
                                <b-button
                                    size="sm"
                                    :variant="data.item.activo ? 'outline-secondary' : 'outline-success'"
                                    @click="toggleActivoPolitica(data.item)"
                                    class="mr-1"
                                >
                                    {{ data.item.activo ? 'Desactivar' : 'Activar' }}
                                </b-button>
                                <b-button
                                    size="sm"
                                    variant="outline-danger"
                                    @click="confirmDeletePolitica(data.item)"
                                >
                                    <i class="fa fa-trash"></i> Eliminar
                                </b-button>
                            </template>
                        </b-table>

                        <div v-else class="text-center py-5">
                            <p class="text-muted">No hay políticas creadas. Haz clic en "Nueva Política" para crear una.</p>
                        </div>
                    </b-tab>

                    <!-- PESTAÑA 2: POLÍTICAS DEL FONDO -->
                    <b-tab title="Políticas del Fondo">
                        <b-row class="mb-3">
                            <b-col cols="12" class="text-right">
                                <b-button
                                    @click="showCreateFondoModal"
                                    variant="primary"
                                    class="btn-create"
                                    style="background-color: #2c8c73; border-color: #2c8c73"
                                >
                                    + Nuevo Fondo
                                </b-button>
                            </b-col>
                        </b-row>

                        <!-- Tabla de fondos -->
                        <b-table
                            v-if="fondos.length > 0"
                            :items="fondos"
                            :fields="fieldsFondos"
                            striped
                            hover
                            responsive
                            class="custom-table"
                        >
                            <template #cell(nombre_fondo)="data">
                                <strong>{{ data.item.nombre_fondo }}</strong>
                            </template>

                            <template #cell(saldo_max)="data">
                                {{ formatCurrency(data.item.saldo_max) }}
                            </template>

                            <template #cell(tasa_usura)="data">
                                {{ formatPercent(data.item.tasa_usura) }}%
                            </template>

                            <template #cell(activo)="data">
                                <b-badge :variant="data.item.activo ? 'success' : 'secondary'">
                                    {{ data.item.activo ? 'Activo' : 'Inactivo' }}
                                </b-badge>
                            </template>

                            <template #cell(acciones)="data">
                                <b-button
                                    size="sm"
                                    variant="outline-primary"
                                    @click="showViewFondoModal(data.item)"
                                    class="mr-1"
                                >
                                    <i class="fa fa-eye"></i> Ver
                                </b-button>
                                <b-button
                                    size="sm"
                                    variant="outline-warning"
                                    @click="showEditFondoModal(data.item)"
                                    class="mr-1"
                                >
                                    <i class="fa fa-edit"></i> Editar
                                </b-button>
                                <b-button
                                    size="sm"
                                    :variant="data.item.activo ? 'outline-secondary' : 'outline-success'"
                                    @click="toggleActivoFondo(data.item)"
                                    class="mr-1"
                                >
                                    {{ data.item.activo ? 'Desactivar' : 'Activar' }}
                                </b-button>
                                <b-button
                                    size="sm"
                                    variant="outline-danger"
                                    @click="confirmDeleteFondo(data.item)"
                                >
                                    <i class="fa fa-trash"></i> Eliminar
                                </b-button>
                            </template>
                        </b-table>

                        <div v-else class="text-center py-5">
                            <p class="text-muted">No hay fondos creados. Haz clic en "Nuevo Fondo" para crear uno.</p>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>

        <!-- MODAL POLÍTICAS DE PORTAFOLIO -->
        <b-modal
            :id="modalPoliticaId"
            :title="modalPoliticaTitle"
            size="lg"
            @ok="handlePoliticaModalOk"
            @hidden="resetPoliticaModal"
            ok-title="Guardar"
            cancel-title="Cancelar"
            ok-variant="success"
        >
            <b-form @submit.prevent="handlePoliticaSubmit">
                <b-form-group
                    label="Nombre de la Política *"
                    label-for="nombre_politica"
                    description="Asigne un nombre descriptivo a esta política"
                >
                    <b-form-input
                        id="nombre_politica"
                        v-model="formDataPolitica.nombre"
                        type="text"
                        required
                        :disabled="isViewModePolitica"
                        placeholder="Ej: Política Estándar, Política Premium, etc."
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="Descripción"
                    label-for="descripcion"
                    description="Descripción opcional de la política"
                >
                    <b-form-textarea
                        id="descripcion"
                        v-model="formDataPolitica.descripcion"
                        rows="2"
                        :disabled="isViewModePolitica"
                        placeholder="Descripción de la política..."
                        class="input_style_b"
                    ></b-form-textarea>
                </b-form-group>

                <hr>

                <h5 class="mb-3">Porcentajes de la Política</h5>

                <b-form-group
                    label="% Portafolio *"
                    label-for="porcentaje_portafolio"
                    description="Porcentaje aplicable al portafolio (ej: 8, 0.0003, 1.39494)"
                >
                    <b-form-input
                        id="porcentaje_portafolio"
                        v-model.number="formDataPolitica.porcentaje_portafolio"
                        type="number"
                        step="any"
                        min="0"
                        required
                        :disabled="isViewModePolitica"
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="% Comisión Comercial *"
                    label-for="porcentaje_comision_comercial"
                    description="Porcentaje de comisión comercial (ej: 3, 0.0003, 1.39494)"
                >
                    <b-form-input
                        id="porcentaje_comision_comercial"
                        v-model.number="formDataPolitica.porcentaje_comision_comercial"
                        type="number"
                        step="any"
                        min="0"
                        required
                        :disabled="isViewModePolitica"
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="% Re-Incorporación GAF *"
                    label-for="porcentaje_reincorporacion_gaf"
                    description="Porcentaje de re-incorporación GAF (ej: 5, 0.0003, 1.39494)"
                >
                    <b-form-input
                        id="porcentaje_reincorporacion_gaf"
                        v-model.number="formDataPolitica.porcentaje_reincorporacion_gaf"
                        type="number"
                        step="any"
                        min="0"
                        required
                        :disabled="isViewModePolitica"
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="% Coadministración *"
                    label-for="porcentaje_coadministracion"
                    description="Porcentaje de coadministración (ej: 2, 0.0003, 1.39494)"
                >
                    <b-form-input
                        id="porcentaje_coadministracion"
                        v-model.number="formDataPolitica.porcentaje_coadministracion"
                        type="number"
                        step="any"
                        min="0"
                        required
                        :disabled="isViewModePolitica"
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="% Costo Seguro V.D *"
                    label-for="porcentaje_costo_seguro_vd"
                    description="Porcentaje de costo de seguro V.D (ej: 0.0236, 0.0003, 1.39494)"
                >
                    <b-form-input
                        id="porcentaje_costo_seguro_vd"
                        v-model.number="formDataPolitica.porcentaje_costo_seguro_vd"
                        type="number"
                        step="any"
                        min="0"
                        required
                        :disabled="isViewModePolitica"
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group v-if="!isViewModePolitica">
                    <b-form-checkbox
                        v-model="formDataPolitica.activo"
                        switch
                        size="lg"
                    >
                        <strong>Política Activa</strong>
                    </b-form-checkbox>
                </b-form-group>
            </b-form>
        </b-modal>

        <!-- MODAL POLÍTICAS DEL FONDO -->
        <b-modal
            :id="modalFondoId"
            :title="modalFondoTitle"
            size="xl"
            @ok="handleFondoModalOk"
            @hidden="resetFondoModal"
            ok-title="Guardar"
            cancel-title="Cancelar"
            ok-variant="success"
        >
            <b-form @submit.prevent="handleFondoSubmit">
                <b-form-group
                    label="Nombre del Fondo *"
                    label-for="nombre_fondo"
                    description="Asigne un nombre descriptivo a este fondo"
                >
                    <b-form-input
                        id="nombre_fondo"
                        v-model="formDataFondo.nombre_fondo"
                        type="text"
                        required
                        :disabled="isViewModeFondo"
                        placeholder="Ej: Fondo Principal, Fondo Secundario, etc."
                        class="input_style_b"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="Descripción"
                    label-for="descripcion_fondo"
                    description="Descripción opcional del fondo"
                >
                    <b-form-textarea
                        id="descripcion_fondo"
                        v-model="formDataFondo.descripcion"
                        rows="2"
                        :disabled="isViewModeFondo"
                        placeholder="Descripción del fondo..."
                        class="input_style_b"
                    ></b-form-textarea>
                </b-form-group>

                <hr>

                <h5 class="mb-3">Parámetros del Fondo</h5>

                <b-row>
                    <!-- Columna 1: Campos Fijos -->
                    <b-col md="6">
                        <h6 class="text-muted mb-3">Campos Fijos</h6>

                        <b-form-group label="SALDO MAX *" label-for="saldo_max">
                            <b-form-input
                                id="saldo_max"
                                v-model.number="formDataFondo.saldo_max"
                                type="number"
                                step="any"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                @input="calculateFondoFields"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="DIAS MORA MAX *" label-for="dias_mora_max">
                            <b-form-input
                                id="dias_mora_max"
                                v-model.number="formDataFondo.dias_mora_max"
                                type="number"
                                step="1"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="PLAZO MAX (meses) *" label-for="plazo_max">
                            <b-form-input
                                id="plazo_max"
                                v-model.number="formDataFondo.plazo_max"
                                type="number"
                                step="1"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T.A MIN (EA) *" label-for="ta_min_ea" description="Tasa Anual Mínima Efectiva Anual">
                            <b-form-input
                                id="ta_min_ea"
                                v-model.number="formDataFondo.ta_min_ea"
                                type="number"
                                step="any"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                @input="calculateFondoFields"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T. USURA (EA) *" label-for="t_usura_ea" description="Tasa de Usura Efectiva Anual">
                            <b-form-input
                                id="t_usura_ea"
                                v-model.number="formDataFondo.t_usura_ea"
                                type="number"
                                step="any"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                @input="calculateFondoFields"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="TASA USURA *" label-for="tasa_usura">
                            <b-form-input
                                id="tasa_usura"
                                v-model.number="formDataFondo.tasa_usura"
                                type="number"
                                step="any"
                                min="0"
                                required
                                :disabled="isViewModeFondo"
                                class="input_style_b"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>

                    <!-- Columna 2: Campos Calculados -->
                    <b-col md="6">
                        <h6 class="text-muted mb-3">Campos Calculados (Automáticos)</h6>

                        <b-form-group label="T.A MIN (EM)" label-for="ta_min_em" description="T.A MIN (EA) ÷ 12">
                            <b-form-input
                                id="ta_min_em"
                                v-model="formDataFondo.ta_min_em"
                                type="text"
                                disabled
                                class="input_style_b input-calculated"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T. USURA -2 (EA)" label-for="t_usura_menos2_ea" description="T. USURA (EA) - 2">
                            <b-form-input
                                id="t_usura_menos2_ea"
                                v-model="formDataFondo.t_usura_menos2_ea"
                                type="text"
                                disabled
                                class="input_style_b input-calculated"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T. USURA (EM)" label-for="t_usura_em" description="T. USURA (EA) ÷ 12">
                            <b-form-input
                                id="t_usura_em"
                                v-model="formDataFondo.t_usura_em"
                                type="text"
                                disabled
                                class="input_style_b input-calculated"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T. USURA -2 (EM)" label-for="t_usura_menos2_em" description="T. USURA -2 (EA) ÷ 12">
                            <b-form-input
                                id="t_usura_menos2_em"
                                v-model="formDataFondo.t_usura_menos2_em"
                                type="text"
                                disabled
                                class="input_style_b input-calculated"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group label="T. USURA (DIA)" label-for="t_usura_dia" description="T. USURA (EA) ÷ 365">
                            <b-form-input
                                id="t_usura_dia"
                                v-model="formDataFondo.t_usura_dia"
                                type="text"
                                disabled
                                class="input_style_b input-calculated"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-form-group v-if="!isViewModeFondo">
                    <b-form-checkbox
                        v-model="formDataFondo.activo"
                        switch
                        size="lg"
                    >
                        <strong>Fondo Activo</strong>
                    </b-form-checkbox>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PoliticasPortafolio',
    data() {
        return {
            // Políticas de Portafolio
            politicas: [],
            fieldsPoliticas: [
                { key: 'nombre', label: 'Nombre', sortable: true },
                { key: 'porcentaje_portafolio', label: '% Portafolio', sortable: true },
                { key: 'porcentaje_comision_comercial', label: '% Comisión', sortable: true },
                { key: 'porcentaje_reincorporacion_gaf', label: '% Re-Inc. GAF', sortable: true },
                { key: 'porcentaje_coadministracion', label: '% Coadmin.', sortable: true },
                { key: 'porcentaje_costo_seguro_vd', label: '% Seguro V.D', sortable: true },
                { key: 'activo', label: 'Estado', sortable: true },
                { key: 'acciones', label: 'Acciones' }
            ],
            modalPoliticaId: 'modal-politica',
            modalPoliticaTitle: '',
            modalPoliticaMode: 'create',
            formDataPolitica: this.getEmptyPoliticaForm(),

            // Políticas del Fondo
            fondos: [],
            fieldsFondos: [
                { key: 'nombre_fondo', label: 'Nombre', sortable: true },
                { key: 'saldo_max', label: 'Saldo Máx', sortable: true },
                { key: 'dias_mora_max', label: 'Días Mora Máx', sortable: true },
                { key: 'plazo_max', label: 'Plazo Máx', sortable: true },
                { key: 'tasa_usura', label: 'Tasa Usura', sortable: true },
                { key: 'activo', label: 'Estado', sortable: true },
                { key: 'acciones', label: 'Acciones' }
            ],
            modalFondoId: 'modal-fondo',
            modalFondoTitle: '',
            modalFondoMode: 'create',
            formDataFondo: this.getEmptyFondoForm()
        };
    },
    computed: {
        isViewModePolitica() {
            return this.modalPoliticaMode === 'view';
        },
        isViewModeFondo() {
            return this.modalFondoMode === 'view';
        }
    },
    mounted() {
        this.loadPoliticas();
        this.loadFondos();
    },
    methods: {
        // ========== POLÍTICAS DE PORTAFOLIO ==========
        getEmptyPoliticaForm() {
            return {
                id: null,
                nombre: '',
                descripcion: '',
                porcentaje_portafolio: 0,
                porcentaje_comision_comercial: 0,
                porcentaje_reincorporacion_gaf: 0,
                porcentaje_coadministracion: 0,
                porcentaje_costo_seguro_vd: 0,
                activo: true
            };
        },
        async loadPoliticas() {
            try {
                const response = await axios.get('/politicas-portafolio/get');
                this.politicas = response.data.politicas || [];
            } catch (error) {
                console.error('Error al cargar políticas:', error);
            }
        },
        showCreatePoliticaModal() {
            this.modalPoliticaMode = 'create';
            this.modalPoliticaTitle = 'Crear Nueva Política';
            this.formDataPolitica = this.getEmptyPoliticaForm();
            this.$bvModal.show(this.modalPoliticaId);
        },
        showEditPoliticaModal(politica) {
            this.modalPoliticaMode = 'edit';
            this.modalPoliticaTitle = 'Editar Política';
            this.formDataPolitica = { ...politica };
            this.$bvModal.show(this.modalPoliticaId);
        },
        showViewPoliticaModal(politica) {
            this.modalPoliticaMode = 'view';
            this.modalPoliticaTitle = 'Ver Política';
            this.formDataPolitica = { ...politica };
            this.$bvModal.show(this.modalPoliticaId);
        },
        resetPoliticaModal() {
            this.formDataPolitica = this.getEmptyPoliticaForm();
            this.modalPoliticaMode = 'create';
        },
        handlePoliticaModalOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handlePoliticaSubmit();
        },
        async handlePoliticaSubmit() {
            try {
                if (this.modalPoliticaMode === 'view') {
                    this.$bvModal.hide(this.modalPoliticaId);
                    return;
                }

                const endpoint = this.modalPoliticaMode === 'create'
                    ? '/politicas-portafolio/store'
                    : '/politicas-portafolio/update';

                const response = await axios.post(endpoint, {
                    politica: this.formDataPolitica
                });

                this.$bvToast.toast(response.data.message || 'Política guardada exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                this.$bvModal.hide(this.modalPoliticaId);
                await this.loadPoliticas();
            } catch (error) {
                console.error('Error al guardar política:', error);
                this.$bvToast.toast(error.response?.data?.message || 'Error al guardar la política', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 3000
                });
            }
        },
        async toggleActivoPolitica(politica) {
            try {
                const response = await axios.post('/politicas-portafolio/toggle-activo', {
                    id: politica.id
                });

                this.$bvToast.toast(response.data.message || 'Estado actualizado', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                await this.loadPoliticas();
            } catch (error) {
                console.error('Error al cambiar estado:', error);
            }
        },
        confirmDeletePolitica(politica) {
            this.$bvModal.msgBoxConfirm(
                `¿Está seguro que desea eliminar la política "${politica.nombre}"?`,
                {
                    title: 'Confirmar Eliminación',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'Eliminar',
                    cancelTitle: 'Cancelar',
                    centered: true
                }
            ).then(value => {
                if (value) {
                    this.deletePolitica(politica);
                }
            });
        },
        async deletePolitica(politica) {
            try {
                const response = await axios.post('/politicas-portafolio/delete', {
                    id: politica.id
                });

                this.$bvToast.toast(response.data.message || 'Política eliminada exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                await this.loadPoliticas();
            } catch (error) {
                console.error('Error al eliminar política:', error);
            }
        },

        // ========== POLÍTICAS DEL FONDO ==========
        getEmptyFondoForm() {
            return {
                id: null,
                nombre_fondo: '',
                descripcion: '',
                // Campos fijos
                saldo_max: 0,
                dias_mora_max: 0,
                plazo_max: 0,
                ta_min_ea: 0,
                t_usura_ea: 0,
                tasa_usura: 0,
                // Campos calculados
                ta_min_em: 0,
                t_usura_menos2_ea: 0,
                t_usura_em: 0,
                t_usura_menos2_em: 0,
                t_usura_dia: 0,
                activo: true
            };
        },
        async loadFondos() {
            try {
                const response = await axios.get('/politicas-portafolio/fondos/get');
                this.fondos = response.data.fondos || [];
            } catch (error) {
                console.error('Error al cargar fondos:', error);
            }
        },
        showCreateFondoModal() {
            this.modalFondoMode = 'create';
            this.modalFondoTitle = 'Crear Nuevo Fondo';
            this.formDataFondo = this.getEmptyFondoForm();
            this.$bvModal.show(this.modalFondoId);
        },
        showEditFondoModal(fondo) {
            this.modalFondoMode = 'edit';
            this.modalFondoTitle = 'Editar Fondo';
            this.formDataFondo = { ...fondo };
            this.$bvModal.show(this.modalFondoId);
        },
        showViewFondoModal(fondo) {
            this.modalFondoMode = 'view';
            this.modalFondoTitle = 'Ver Fondo';
            this.formDataFondo = { ...fondo };
            this.$bvModal.show(this.modalFondoId);
        },
        resetFondoModal() {
            this.formDataFondo = this.getEmptyFondoForm();
            this.modalFondoMode = 'create';
        },
        handleFondoModalOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handleFondoSubmit();
        },
        calculateFondoFields() {
            const f = this.formDataFondo;

            // T.A MIN (EM) = T.A MIN (EA) / 12
            f.ta_min_em = (parseFloat(f.ta_min_ea) / 12).toFixed(6);

            // T. USURA -2 (EA) = T. USURA (EA) - 2
            f.t_usura_menos2_ea = (parseFloat(f.t_usura_ea) - 2).toFixed(6);

            // T. USURA (EM) = T. USURA (EA) / 12
            f.t_usura_em = (parseFloat(f.t_usura_ea) / 12).toFixed(6);

            // T. USURA -2 (EM) = T. USURA -2 (EA) / 12
            f.t_usura_menos2_em = (parseFloat(f.t_usura_menos2_ea) / 12).toFixed(6);

            // T. USURA (DIA) = T. USURA (EA) / 365
            f.t_usura_dia = (parseFloat(f.t_usura_ea) / 365).toFixed(6);
        },
        async handleFondoSubmit() {
            try {
                if (this.modalFondoMode === 'view') {
                    this.$bvModal.hide(this.modalFondoId);
                    return;
                }

                // Calcular campos antes de enviar
                this.calculateFondoFields();

                const endpoint = this.modalFondoMode === 'create'
                    ? '/politicas-portafolio/fondos/store'
                    : '/politicas-portafolio/fondos/update';

                const response = await axios.post(endpoint, {
                    fondo: this.formDataFondo
                });

                this.$bvToast.toast(response.data.message || 'Fondo guardado exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                this.$bvModal.hide(this.modalFondoId);
                await this.loadFondos();
            } catch (error) {
                console.error('Error al guardar fondo:', error);
                this.$bvToast.toast(error.response?.data?.message || 'Error al guardar el fondo', {
                    title: 'Error',
                    variant: 'danger',
                    solid: true,
                    autoHideDelay: 3000
                });
            }
        },
        async toggleActivoFondo(fondo) {
            try {
                const response = await axios.post('/politicas-portafolio/fondos/toggle-activo', {
                    id: fondo.id
                });

                this.$bvToast.toast(response.data.message || 'Estado actualizado', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                await this.loadFondos();
            } catch (error) {
                console.error('Error al cambiar estado:', error);
            }
        },
        confirmDeleteFondo(fondo) {
            this.$bvModal.msgBoxConfirm(
                `¿Está seguro que desea eliminar el fondo "${fondo.nombre_fondo}"?`,
                {
                    title: 'Confirmar Eliminación',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'Eliminar',
                    cancelTitle: 'Cancelar',
                    centered: true
                }
            ).then(value => {
                if (value) {
                    this.deleteFondo(fondo);
                }
            });
        },
        async deleteFondo(fondo) {
            try {
                const response = await axios.post('/politicas-portafolio/fondos/delete', {
                    id: fondo.id
                });

                this.$bvToast.toast(response.data.message || 'Fondo eliminado exitosamente', {
                    title: 'Éxito',
                    variant: 'success',
                    solid: true,
                    autoHideDelay: 3000
                });

                await this.loadFondos();
            } catch (error) {
                console.error('Error al eliminar fondo:', error);
            }
        },

        // ========== UTILIDADES ==========
        formatPercent(value) {
            if (value == null || isNaN(value)) return '0.00';
            return parseFloat(value).toFixed(4);
        },
        formatCurrency(value) {
            if (value == null || isNaN(value)) return '$0';
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            }).format(value);
        }
    }
};
</script>

<style scoped>
.heading-title {
    color: #2c3e50;
    font-weight: 600;
}

.panel {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
}

.panel-body {
    padding: 15px;
}

.input_style_b {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 8px 12px;
    background-color: #ffffff;
    color: #2c3e50;
    font-weight: 500;
}

.input_style_b:disabled {
    background-color: #f8f9fa;
    color: #495057;
    cursor: not-allowed;
    font-weight: 500;
}

.input-calculated {
    background-color: #e9ecef !important;
    font-weight: 600 !important;
    color: #2c8c73 !important;
}

.btn-create {
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.custom-table {
    margin-top: 20px;
}

.custom-table >>> th {
    background-color: #f8f9fa;
    color: #2c3e50;
    font-weight: 600;
    border-bottom: 2px solid #2c8c73;
}

.custom-table >>> tbody tr:hover {
    background-color: #f1f3f5;
}

::v-deep .nav-tabs .nav-link.active {
    color: #2c8c73 !important;
    border-bottom: 2px solid #2c8c73 !important;
}

::v-deep .nav-tabs .nav-link {
    color: #6c757d;
}

::v-deep .nav-tabs .nav-link:hover {
    color: #2c8c73;
}
</style>
