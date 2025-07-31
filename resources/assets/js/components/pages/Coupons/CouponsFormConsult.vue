<template>
    <div class="panel container-fluid">
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"
                 color="#0CEDB0" />
        <div class="p-0 mb-3 col-md-12">
            <div class="mb-4 mt-5">
                <h3 class="heading-title">Prospección de Cartera</h3>
            </div>

            <div>
                <!-- filtros principales -->
                <div class="row d-flex justify-content-start align-items-end">
                    <div class="col-md-4">
                        <b-form-group label="Pagaduría">
                            <b-form-input v-model="pagaduria"
                                          list="lista-pagadurias"
                                          placeholder="Pagaduría"
                                          class="input_style_b form-control2" />
                            <div class="estilo-datalist">
                                <b-form-datalist id="lista-pagadurias"
                                                 :options="pagaduriasList"
                                                 class="listado form-control2" />
                            </div>
                        </b-form-group>
                    </div>

                    <div class="col-md-4">
                        <b-form-group label="Estado">
                            <b-form-select v-model="selectedEstado"
                                           :options="estadosOptions"
                                           class="input_style_b form-control2" />
                        </b-form-group>
                    </div>

                    <div class="col-md-4"
                         v-if="selectedEstado === 'Al día' || selectedEstado === 'Todas'">
                        <b-form-group label="Entidad (Banco o Financiera)">
                            <b-form-input v-model="concept"
                                          placeholder="Concepto"
                                          class="input_style_b form-control2" />
                        </b-form-group>
                    </div>

                    <div class="col-md-4"
                         v-if="selectedEstado === 'En mora' || selectedEstado === 'Todas'">
                        <b-form-group label="Código">
                            <b-form-input v-model="mliquid"
                                          placeholder="Mensaje de liquidación"
                                          type="text"
                                          class="input_style_b form-control2" />
                        </b-form-group>
                    </div>

                    <div class="col-md-4"
                         v-if="selectedEstado === 'Embargado' || selectedEstado === 'Todas'">
                        <b-form-group label="Entidad Demandante">
                            <b-form-input v-model="entidadDemandante"
                                          placeholder="Entidad demandante"
                                          class="input_style_b form-control2" />
                        </b-form-group>
                    </div>
                </div>

                <!-- mes, año, per-page -->
                <div class="row d-flex justify-content-start align-items-end">
                    <div class="col-md-4">
                        <b-form-group label="Mes">
                            <b-form-select v-model="month"
                                           :options="monthOptions"
                                           class="input_style_b form-control2" />
                        </b-form-group>
                    </div>

                    <div class="col-md-4">
                        <b-form-group label="Año">
                            <b-form-input v-model="year"
                                          placeholder="Año"
                                          type="number"
                                          class="input_style_b form-control2" />
                        </b-form-group>
                    </div>

                    <div class="col-md-4">
                        <b-form-group label="Registros por página">
                            <b-form-select v-model.number="perPageSelection"
                                           :options="perPageOptions"
                                           class="input_style_b form-control2" />
                        </b-form-group>
                    </div>
                </div>

                <!-- botones -->
                <div class="row">
                    <div class="col-md-12 text-left">
                        <CustomButton text="Prospectar" @click="search(1)" />
                        <CustomButton text="Exportar a Excel" @click="exportToExcel" />
                    </div>
                </div>

                <!-- validaciones -->
                <div v-if="!isPagaduriaValid || !isMonthValid || !isYearValid || !isEntidadDemandanteValid"
                     class="text-danger">
                    <div v-if="!isPagaduriaValid">La pagaduría es obligatoria.</div>
                    <div v-if="!isMonthValid">El mes es obligatorio.</div>
                    <div v-if="!isYearValid">El año es obligatorio.</div>
                    <div v-if="!isEntidadDemandanteValid">La entidad demandante es obligatoria.</div>
                </div>
            </div>
        </div>

        <!-- tablas -->
        <CouponsTable
            v-if="selectedEstado === 'Al día'"
            :items="coupons"
            :fields="cupones"
            :total-rows="rowsAldia"
            :current-page.sync="currentPageAldia"
            :per-page="perPageAldia"
            :input-filtro="inputFiltroCupon"
            @page-change="page => search(page, 'Al día')"
            table-id="aldia"
            resumen-titulo="Cartera al Día"
            :total-cuotas="totalCuotasAldia" />

        <DescuentosTable
            v-if="selectedEstado === 'En mora'"
            :items="descuentos"
            :fields="descuentosFields"
            :total-rows="rowsMora"
            :current-page.sync="currentPageMora"
            :per-page="perPageMora"
            :filtro="filtroDescuento"
            :mliquid="mliquid"
            @page-change="page => search(page, 'En mora')"
            :total-cuotas="totalCuotasMora"
            @open-modal="handleButtonClick"
            :is-loading-modal="isLoadingModal"
            :causales-final="causalesFinal"
            :situacion-laboral="situacionLaboral"
            :incapacidades="incapacidades" />

        <EmbargosTable
            v-if="selectedEstado === 'Embargado'"
            :items="embargos"
            :fields="embargosFields"
            :total-rows="rowsEmbargo"
            :current-page.sync="currentPageEmbargo"
            :per-page="perPageEmbargo"
            :filtro="filtroEmbargo"
            @page-change="page => search(page, 'Embargado')"
            :total-cuotas="totalCuotasEmbargo" />

        <ResumenTodas
            v-if="selectedEstado === 'Todas' && rowsAldia + rowsMora + rowsEmbargo > 0"
            :rows-aldia="rowsAldia"
            :rows-mora="rowsMora"
            :rows-embargo="rowsEmbargo"
            :total-cuotas-aldia="totalCuotasAldia"
            :total-cuotas-mora="totalCuotasMora"
            :total-cuotas-embargo="totalCuotasEmbargo" />

        <div v-if="searchPerformed && rowsAldia + rowsMora + rowsEmbargo === 0">
            <p>No se encontraron datos para los criterios de búsqueda proporcionados.</p>
        </div>
    </div>
</template>

<style>
.form-group legend{font-size:14px;font-weight:400;line-height:18.23px;}
datalist{position:absolute;max-height:20em;overflow-y:auto;}
datalist option{font-size:0.8em;padding:0.3em 1em;background-color:#ccc;cursor:pointer;}
datalist option:hover,datalist option:focus{color:#fff;background-color:#036;}
td,th{text-align:left;}
.form-control2{border:1px solid #b9bdc3;background-color:#fff;border-radius:10px;}
.label-resumen{font-weight:600;text-align:center;background-color:#e1e1e1;color:#021b1e;border-radius:38px;min-height:30px;display:flex;align-items:center;padding:6px 12px;}
.heading-title{font-size:25px;font-weight:500;line-height:32.55px;}
</style>

<script>
import axios from 'axios'
import * as XLSX from 'xlsx'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import CustomButton from '../../customComponents/CustomButton.vue'
import CouponsTable from './partials/CouponsTable.vue'
import DescuentosTable from './partials/DescuentosTable.vue'
import EmbargosTable from './partials/EmbargosTable.vue'
import ResumenTodas from './partials/ResumenTodas.vue'

export default {
    name: 'CouponsFormConsult',
    components: {
        Loading,
        CustomButton,
        CouponsTable,
        DescuentosTable,
        EmbargosTable,
        ResumenTodas
    },
    data() {
        return {
            cupones: [
                { key: 'doc', label: 'Documento' },
                { key: 'names', label: 'Nombres Completos' },
                { key: 'code', label: 'Homónimo' },
                { key: 'concept', label: 'Concepto' },
                { key: 'egresos', label: 'Valor Cuota' }
            ],
            descuentosFields: [
                { key: 'doc', label: 'Documento' },
                { key: 'nomp', label: 'Nombre Completo' },
                { key: 'mliquid', label: 'Mensaje Liquidación' },
                { key: 'nomina', label: 'Nómina' },
                { key: 'valor', label: 'Valor' },
                { key: 'actions', label: 'Acciones' }
            ],
            embargosFields: [
                { key: 'doc', label: 'Documento' },
                { key: 'nomp', label: 'Cliente Demandado' },
                { key: 'docdeman', label: 'Número de Pagaré' },
                { key: 'entidaddeman', label: 'Entidad Demandante' },
                { key: 'temb', label: 'Cuota Embargada' }
            ],
            pagaduria: '',
            pagaduriasList: [],
            concept: '',
            code: '',
            month: '',
            year: '',
            mliquid: '',
            entidadDemandante: '',
            selectedEstado: 'Al día',
            estadosOptions: ['Al día', 'En mora', 'Embargado', 'Todas'],
            monthOptions: [
                { value: '', text: 'Mes' },
                { value: '1', text: '1' },
                { value: '2', text: '2' },
                { value: '3', text: '3' },
                { value: '4', text: '4' },
                { value: '5', text: '5' },
                { value: '6', text: '6' },
                { value: '7', text: '7' },
                { value: '8', text: '8' },
                { value: '09', text: '9' },
                { value: '10', text: '10' },
                { value: '11', text: '11' },
                { value: '12', text: '12' }
            ],
            coupons: [],
            descuentos: [],
            embargos: [],
            rowsAldia: 0,
            rowsMora: 0,
            rowsEmbargo: 0,
            totalCuotasAldia: 0,
            totalCuotasMora: 0,
            totalCuotasEmbargo: 0,
            perPageSelection: 10000,
            perPageOptions: [
                { value: 1000, text: '1000' },
                { value: 2000, text: '2000' },
                { value: 10000, text: '10000' },
                { value: 20000, text: '20000' }
            ],
            perPageAldia: 10000,
            perPageMora: 10000,
            perPageEmbargo: 10000,
            currentPageAldia: 1,
            currentPageMora: 1,
            currentPageEmbargo: 1,
            isLoading: false,
            isLoadingModal: false,
            searchPerformed: false,
            isPagaduriaValid: true,
            isMonthValid: true,
            isYearValid: true,
            isEntidadDemandanteValid: true,
            inputFiltroCupon: '',
            filtroDescuento: '',
            filtroEmbargo: '',
            causalesFinal: [],
            situacionLaboral: '',
            incapacidades: false
        }
    },
    watch: {
        perPageSelection(val) {
            this.perPageAldia = val
            this.perPageMora = val
            this.perPageEmbargo = val
            if (this.searchPerformed) this.search(this.getCurrentPage())
        }
    },
    mounted() {
        this.getPagaduriasNames()
    },
    methods: {
        getCurrentPage() {
            if (this.selectedEstado === 'Al día') return this.currentPageAldia
            if (this.selectedEstado === 'En mora') return this.currentPageMora
            if (this.selectedEstado === 'Embargado') return this.currentPageEmbargo
            return 1
        },
        async getPagaduriasNames() {
            const { data } = await axios.get('/pagadurias/namesAmi')
            this.pagaduriasList = data
        },
        validateInputs() {
    const m = String(this.month).trim(); // "1" … "12"
    const y = String(this.year).trim();  // "2025"

    // Validaciones
    this.isPagaduriaValid = !!this.pagaduria;
    this.isMonthValid    = /^(0?[1-9]|1[0-2])$/.test(m);
    this.isYearValid     = /^\d{4}$/.test(y);
    this.isEntidadDemandanteValid =
      this.selectedEstado !== 'Embargado' || !!this.entidadDemandante;

    // Si pasa, normaliza el mes a un dígito (1–9) o dos (10–12)
    if (this.isMonthValid) this.month = String(Number(m));
    if (this.isYearValid)  this.year  = y;

    return (
      this.isPagaduriaValid &&
      this.isMonthValid    &&
      this.isYearValid     &&
      this.isEntidadDemandanteValid
    );
  },
        async search(page = 1) {
            if (!this.validateInputs()) {
                return;
            }

            this.isLoading = true;
            this.searchPerformed = true;

            switch (this.selectedEstado) {
                case 'Al día':
                    this.currentPageAldia = page;
                    break;
                case 'En mora':
                    this.currentPageMora = page;
                    break;
                case 'Embargado':
                    this.currentPageEmbargo = page;
                    break;
                case 'Todas':
                    if (page === 1) {
                        this.currentPageAldia = 1;
                        this.currentPageMora = 1;
                        this.currentPageEmbargo = 1;
                    }
                    break;
            }

            const payload = {
                pagaduria: this.pagaduria,
                month: this.month,
                year: this.year,
                concept: this.concept,
                code: this.code,
                entidadDemandante: this.entidadDemandante,
                perPage: this.perPageSelection,
                page: page
            };

            try {
                if (this.selectedEstado === 'Al día' || this.selectedEstado === 'Todas') {
                    payload.page = this.currentPageAldia; 
                    const { data } = await axios.post('/coupons/by-pagaduria', payload);
                    this.rowsAldia = data.total || 0;
                    this.coupons = data.data || [];
                    this.totalCuotasAldia = this.formatCurrency(this.sumField(this.coupons, 'egresos'));
                }

                if (this.selectedEstado === 'En mora' || this.selectedEstado === 'Todas') {
                    payload.page = this.currentPageMora;
                    const { data } = await axios.post('/descuentos/by-pagaduria', payload);
                    this.rowsMora = data.total || 0;
                    this.descuentos = data.data || [];
                    this.totalCuotasMora = this.formatCurrency(this.sumField(this.descuentos, 'valor'));
                }

                if (this.selectedEstado === 'Embargado' || this.selectedEstado === 'Todas') {
                    payload.page = this.currentPageEmbargo;
                    const { data } = await axios.post('/embargos/by-pagaduria', payload);
                    this.rowsEmbargo = data.total || 0;
                    this.embargos = data.data || [];
                    this.totalCuotasEmbargo = this.formatCurrency(this.sumField(this.embargos, 'temb'));
                }
            } catch (error) {
                console.error("Error durante la búsqueda:", error);
            } finally {
                this.isLoading = false;
            }
        },
        handlePageChange(newPage, estado) {
            if (estado === 'Al día') {
                this.currentPageAldia = newPage;
                this.search(newPage);
            } else if (estado === 'En mora') {
                this.currentPageMora = newPage;
                this.search(newPage);
            } else if (estado === 'Embargado') {
                this.currentPageEmbargo = newPage;
                this.search(newPage);
            }
        },
        formatCurrency(val) {
            const num = parseFloat(String(val).replace(/,/g, ''))
            if (isNaN(num)) return val
            return num.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 })
        },
        sumField(arr, key) {
            return arr.reduce((t, i) => t + Number(String(i[key]).replace(/[^0-9.-]/g, '')), 0)
        },
        async handleButtonClick(doc) {
            this.isLoadingModal = true
            const { data } = await axios.get(`/incapacidad/${doc}/${this.month}/${this.year}`)
            this.incapacidades = data.incapacidad_dura_dos_meses_o_mas === 'Sí'
            this.isLoadingModal = false
        },
        exportToExcel() {
            const wb = XLSX.utils.book_new()
            if (this.coupons.length)
                XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(this.coupons), 'AlDia')
            if (this.descuentos.length)
                XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(this.descuentos), 'EnMora')
            if (this.embargos.length)
                XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(this.embargos), 'Embargos')
            XLSX.writeFile(wb, 'resultados.xlsx')
        }
    }
}
</script>