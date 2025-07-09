<template>
    <div class="panel container-fluid">
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="p-0 mb-3 col-md-12">
            <div class="mb-4 mt-5">
                <h3 class="heading-title">Prospección de Cartera</h3>
            </div>
            <div>
                <div class="row d-flex justify-content-start align-items-end">
                    <div class="col-md-4">
                        <b-form-group label="Pagaduría">
                            <b-form-input
                                v-model="pagaduria"
                                list="lista-pagadurias"
                                placeholder="Pagaduría"
                                class="input_style_b form-control2"
                            />
                            <div class="estilo-datalist">
                                <b-form-datalist
                                    class="listado form-control2"
                                    id="lista-pagadurias"
                                    :options="pagaduriasList"
                                />
                            </div>
                        </b-form-group>
                    </div>

                    <div class="col-md-4">
                        <b-form-group label="Estado">
                            <b-form-select
                                class="input_style_b form-control2"
                                v-model="selectedEstado"
                                :options="estadosOptions"
                            />
                        </b-form-group>
                    </div>

                    <div class="col-md-4" v-if="selectedEstado === 'Al día' || selectedEstado === 'Todas'">
                        <b-form-group label="Entidad (Banco o Financiera)">
                            <b-form-input
                                class="input_style_b form-control2"
                                v-model="concept"
                                placeholder="Concepto"
                            />
                        </b-form-group>
                    </div>

                    <div class="col-md-4" v-if="selectedEstado === 'En mora' || selectedEstado === 'Todas'">
                        <b-form-group label="Codigo">
                            <b-form-input
                                class="input_style_b form-control2"
                                type="text"
                                v-model="mliquid"
                                placeholder="Mensaje de liquidación"
                            />
                        </b-form-group>
                    </div>

                    <div class="col-md-4" v-if="selectedEstado === 'Embargado' || selectedEstado === 'Todas'">
                        <b-form-group label="Entidad demandante">
                            <b-form-input
                                class="input_style_b form-control2"
                                v-model="entidadDemandante"
                                placeholder="Entidad demandante"
                                required
                            />
                        </b-form-group>
                    </div>
                </div>

                <div class="row d-flex justify-content-start align-items-end">
                    <div class="col-md-4">
                        <b-form-group label="Mes">
                            <b-form-input
                                class="input_style_b mr2 form-control2"
                                type="number"
                                v-model="month"
                                placeholder="Mes"
                            />
                        </b-form-group>
                    </div>

                    <div class="col-md-4">
                        <b-form-group label="Año">
                            <b-form-input
                                class="input_style_b form-control2"
                                type="number"
                                v-model="year"
                                placeholder="Año"
                            />
                        </b-form-group>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-left">
                        <CustomButton text="Prospectar" @click="search(1)" />
                        <CustomButton text="Exportar a Excel" @click="exportToExcel" />
                    </div>
                </div>

                <div
                    v-if="!isPagaduriaValid || !isMonthValid || !isYearValid || !isEntidadDemandanteValid"
                    class="text-danger"
                >
                    <div v-if="!isPagaduriaValid">La pagaduría es obligatoria.</div>
                    <div v-if="!isMonthValid">El mes es obligatorio.</div>
                    <div v-if="!isYearValid">El año es obligatorio.</div>
                    <div v-if="!isEntidadDemandanteValid">La entidad demandante es obligatoria.</div>
                </div>
            </div>
        </div>

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
            :total-cuotas="totalCuotasAldia"
        />

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
            :incapacidades="incapacidades"
        />

        <EmbargosTable
            v-if="selectedEstado === 'Embargado'"
            :items="embargos"
            :fields="embargosFields"
            :total-rows="rowsEmbargo"
            :current-page.sync="currentPageEmbargo"
            :per-page="perPageEmbargo"
            :filtro="filtroEmbargo"
            @page-change="page => search(page, 'Embargado')"
            :total-cuotas="totalCuotasEmbargo"
        />

        <ResumenTodas
            v-if="selectedEstado === 'Todas' && rowsAldia + rowsMora + rowsEmbargo > 0"
            :rows-aldia="rowsAldia"
            :rows-mora="rowsMora"
            :rows-embargo="rowsEmbargo"
            :total-cuotas-aldia="totalCuotasAldia"
            :total-cuotas-mora="totalCuotasMora"
            :total-cuotas-embargo="totalCuotasEmbargo"
        />

        <div v-if="searchPerformed && rowsAldia + rowsMora + rowsEmbargo === 0">
            <p>No se encontraron datos para los criterios de búsqueda proporcionados.</p>
        </div>
    </div>
</template>

<style>
.form-group legend {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
}
datalist {
    position: absolute;
    max-height: 20em;
    overflow-y: auto;
}
datalist option {
    font-size: 0.8em;
    padding: 0.3em 1em;
    background-color: #ccc;
    cursor: pointer;
}
datalist option:hover,
datalist option:focus {
    color: #fff;
    background-color: #036;
}
td,
th {
    text-align: left;
}
.form-control2 {
    border: 1px solid #b9bdc3;
    background-color: white;
    border-radius: 10px;
}
.label-resumen {
    font-weight: 600;
    text-align: center;
    background-color: #e1e1e1;
    color: #021b1e;
    border-radius: 38px;
    min-height: 30px;
    display: flex;
    align-items: center;
    padding: 6px 12px;
}
.heading-title {
    font-size: 25px;
    font-weight: 500;
    line-height: 32.55px;
}
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
            coupons: [],
            embargos: [],
            descuentos: [],
            rowsAldia: 0,
            rowsMora: 0,
            rowsEmbargo: 0,
            totalCuotasAldia: 0,
            totalCuotasMora: 0,
            totalCuotasEmbargo: 0,
            perPageAldia: 50,
            perPageMora: 50,
            perPageEmbargo: 50,
            currentPageAldia: 1,
            currentPageMora: 1,
            currentPageEmbargo: 1,
            selectedEstado: 'Al día',
            estadosOptions: ['Al día', 'En mora', 'Embargado', 'Todas'],
            entidadDemandante: '',
            isLoading: false,
            searchPerformed: false,
            isPagaduriaValid: true,
            isMonthValid: true,
            isYearValid: true,
            isEntidadDemandanteValid: true,
            inputFiltroCupon: '',
            filtroDescuento: '',
            filtroEmbargo: '',
            causalesFinal: [],
            isLoadingModal: false,
            situacionLaboral: '',
            incapacidades: false
        }
    },
    mounted() {
        this.getPagaduriasNames()
    },
    methods: {
        async getPagaduriasNames() {
            try {
                const { data } = await axios.get('/pagadurias/namesAmi')
                this.pagaduriasList = data
            } catch (e) {
                console.error(e)
            }
        },
        validateInputs() {
    // convierto a string para poder medir longitud
    const m = String(this.month).padStart(2, '0')   // 1  -> 01
    const y = String(this.year)

    this.isPagaduriaValid        = !!this.pagaduria
    this.isMonthValid            = /^\d{2}$/.test(m)           // 01-12
    this.isYearValid             = /^\d{4}$/.test(y)           // 2024
    this.isEntidadDemandanteValid =
        this.selectedEstado !== 'Embargado' || !!this.entidadDemandante

    // si todo ok, normalizo los campos para el payload
    if (this.isMonthValid) this.month = m
    if (this.isYearValid)  this.year  = y

    return (
        this.isPagaduriaValid &&
        this.isMonthValid &&
        this.isYearValid &&
        this.isEntidadDemandanteValid
    )
},
        async search(page = 1, forceEstado = null) {
            console.log('🔥 search() invoked, payload month/year:', this.month, this.year)

            this.isLoading = true
            this.searchPerformed = true

            const payload = {
                pagaduria: this.pagaduria,
                month: this.month,
                year: this.year,
                concept: this.concept,
                code: this.code,
                entidadDemandante: this.entidadDemandante,
                perPage: this.getPerPage(forceEstado || this.selectedEstado),
                page
            }

            try {

                 if (this.selectedEstado === 'Al día' || this.selectedEstado === 'Todas' || forceEstado === 'Al día') {
                    const { data } = await axios.post('/coupons/by-pagaduria', payload)
                    this.coupons = data.data || []
                    this.rowsAldia = data.total || 0
                    this.totalCuotasAldia = this.formatCurrency(
                        this.sumField(this.coupons, 'egresos')
                    )

                }

                if (this.selectedEstado === 'En mora' || this.selectedEstado === 'Todas' || forceEstado === 'En mora') {
                    const { data } = await axios.post('/descuentos/by-pagaduria', payload)
                    this.descuentos = data.data || []
                    this.rowsMora = data.total || 0
                    this.totalCuotasMora = this.formatCurrency(
                        this.sumField(this.descuentos, 'valor')
                    )

                }

                if (this.selectedEstado === 'Embargado' || this.selectedEstado === 'Todas' || forceEstado === 'Embargado') {
                    const { data } = await axios.post('/embargos/by-pagaduria', payload)
                    this.embargos = data.data || []
                    this.rowsEmbargo = data.total || 0
                    this.totalCuotasEmbargo = this.formatCurrency(
                        this.sumField(this.embargos, 'temb')
                    )
                }
            } catch (e) {
                console.error(e)
            } finally {
                this.isLoading = false
            }
        },
        getPerPage(estado) {
            if (estado === 'Al día') return this.perPageAldia
            if (estado === 'En mora') return this.perPageMora
            if (estado === 'Embargado') return this.perPageEmbargo
            return 20
        },
        formatCurrency(val) {
            const num = parseFloat(String(val).replace(/,/g, ''))
            if (isNaN(num)) return val
            return num.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            })
        },
        sumField(arr, key) {
            return arr.reduce((t, i) => t + Number(String(i[key]).replace(/[^0-9.-]/g, '')), 0)
        },
        async handleButtonClick(doc, id) {
            this.isLoadingModal = true
            try {
                const { data } = await axios.get(`/incapacidad/${doc}/${this.month}/${this.year}`)
                this.incapacidades = data.incapacidad_dura_dos_meses_o_mas === 'Sí'
            } catch (e) {
                console.error(e)
            } finally {
                this.isLoadingModal = false
            }
        },
        exportToExcel() {
            const wb = XLSX.utils.book_new()
            if (this.coupons.length) {
                const sheet = XLSX.utils.json_to_sheet(this.coupons)
                XLSX.utils.book_append_sheet(wb, sheet, 'AlDia')
            }
            if (this.descuentos.length) {
                const sheet = XLSX.utils.json_to_sheet(this.descuentos)
                XLSX.utils.book_append_sheet(wb, sheet, 'EnMora')
            }
            if (this.embargos.length) {
                const sheet = XLSX.utils.json_to_sheet(this.embargos)
                XLSX.utils.book_append_sheet(wb, sheet, 'Embargos')
            }
            XLSX.writeFile(wb, 'resultados.xlsx')
        }
    }
}
</script>
