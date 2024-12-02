<template>
    <div class="container-fluid" style="background-color: #f9fafc;">
        <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="true" color="#0CEDB0" />

        <b-toast id="toast-incapacidad-month" title="Alerta del Sistema" solid auto-hide-delay="10000" variant="info">
            Cliente con incapacidad mayor a 2 meses.
        </b-toast>

        <div v-if="type_consult === 'individual'">
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-end" v-if="datamesFopep">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3" />
                        <div>
                            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">
                                {{ datamesFopep.nomp }}
                            </h2>
                        </div>
                    </div>
                    <!-- BOTON DOWNLOAD ORIGINAL -->
                    <!-- <button type="button" v-on:click="print" class="btn btn-black-pearl ml-auto px-3">
                        <span class="mr-1">Descargar PDF</span>
                        <DownloadIcon fill="#fff" />
                    </button> -->
                </div>
            </div>

            <div id="consulta-container" class="row">
                <FormConsult @emitInfo="emitInfo" @downloadPdf="print" />

                <!--============================
                DATAMES FOPEP -
                ==============================-->
                <div class="info-container col-4">
                <DatamesComponent
                    v-if="pagaduriaType == 'FOPEP' && datamesFopep"
                    :user="user"
                    :datamesFopep="datamesFopep"
                />

                <!--============================
                    FIDUPREVISORA datamesFidu
                ==============================-->
                <DatamesFidu
                    v-if="pagaduriaType == 'FIDUPREVISORA' && datamesFidu"
                    :user="user"
                    :datamesFidu="datamesFidu"
                />

                <!--============================
                    DATAMESSEDUC SED VALLE
                ==============================-->
                <!-- <DatamesSedValle
                    v-if="pagaduriaType == 'SEDVALLE' && datamessedvalle"
                    :user="user"
                    :datamessedvalle="datamessedvalle"

                /> -->

                <!--================================
                SEMCALI datamessemcali
                ===================================-->
                <!-- <DatamesSemCali
                    v-if="pagaduriaType == 'SEMCALI' && datamessemcali"
                    :user="user"
                    :datamessemcali="datamessemcali"
                /> -->

                <!--================================
                DATAMES SECRETARIAS
                ===================================-->
                <!--<DatamesData2
                    v-if="
                        pagaduriaType == 'SEMSAHAGUN'" />-->

                <DatamesData
                    class="col-12"
                    v-if="
                        pagaduriaType == 'SEDCHOCO' ||
                        pagaduriaType == 'SEDVALLE' ||
                        pagaduriaType == 'SEDCAUCA' ||
                        pagaduriaType == 'SEMSAHAGUN' ||
                        pagaduriaType == 'SEMSANTAMARTA' ||
                        pagaduriaType == 'SEMCALI' ||
                        pagaduriaType == 'SEMQUIBDO' ||
                        pagaduriaType == 'SEDMAGDALENA' ||
                        pagaduriaType == 'SEMPOPAYAN' ||
                        pagaduriaType == 'SEMMONTERIA' ||
                        pagaduriaType == 'SEDCORDOBA' ||
                        pagaduriaType == 'SEDCALDAS' ||
                        pagaduriaType == 'SEDBOYACA' ||
                        pagaduriaType == 'SEDBOLIVAR' ||
                        pagaduriaType == 'SEMBARRANQUILLA' ||
                        pagaduriaType == 'SEDATLANTICO' ||
                        pagaduriaType == 'SEDHUILA' ||
                        pagaduriaType == 'SEDRISARALDA' ||
                        pagaduriaType == 'SEDMETA' ||
                        pagaduriaType == 'SEDCUNDINAMARCA' ||
                        pagaduriaType == 'SEMMOSQUERA' ||
                        pagaduriaType == 'SEMMAGANGUE' ||
                        pagaduriaType == 'SEMBUGA' ||
                        pagaduriaType == 'SEMNEIVA' ||
                        pagaduriaType == 'SEMFUNZA' ||
                        pagaduriaType == 'SEMZIPAQUIRA' ||
                        pagaduriaType == 'SEMYUMBO' ||
                        pagaduriaType == 'SEMYOPAL' ||
                        pagaduriaType == 'SEMIPIALES' ||
                        pagaduriaType == 'SEMPIEDECUESTA' ||
                        pagaduriaType == 'SEMVALLEDUPAR' ||
                        pagaduriaType == 'SEMURIBIA' ||
                        pagaduriaType == 'SEMTURBO' ||
                        pagaduriaType == 'SEMTUNJA' ||
                        pagaduriaType == 'SEMBUCARAMANGA' ||
                        pagaduriaType == 'SEMMANIZALES' ||
                        pagaduriaType == 'SEMMAICAO' ||
                        pagaduriaType == 'SEMMALAMBO' ||
                        pagaduriaType == 'SEMPASTO' ||
                        pagaduriaType == 'SEMBUENAVENTURA' ||
                        pagaduriaType == 'SEMPALMIRA' ||
                        pagaduriaType == 'SEMJAMUNDI' ||
                        pagaduriaType == 'SEMCARTAGO' ||
                        pagaduriaType == 'SEMDUITAMA' ||
                        pagaduriaType == 'SEMGIRON' ||
                        pagaduriaType == 'SEMGIRARDOT' ||
                        pagaduriaType == 'SEMCHIA' ||
                        pagaduriaType == 'SEMBELLO' ||
                        pagaduriaType == 'SEMCIENAGA' ||
                        pagaduriaType == 'SEMCUCUTA' ||
                        pagaduriaType == 'SEMMEDELLIN' ||
                        pagaduriaType == 'SEMDOSQUEBRADAS' ||
                        pagaduriaType == 'SEMCARTAGENA' ||
                        pagaduriaType == 'SEMFUSAGAZUGA' ||
                        pagaduriaType == 'SEMENVIGADO' ||
                        pagaduriaType == 'SEMFACATATIVA' ||
                        pagaduriaType == 'SEMARMENIA' ||
                        pagaduriaType == 'SEMVILLAVICENCIO' ||
                        pagaduriaType == 'SEMFLORENCIA' ||
                        pagaduriaType == 'SEMFLORIDABLANCA' ||
                        pagaduriaType == 'SEDNORTEDESANTANDER' ||
                        pagaduriaType == 'SEDSANTANDER' ||
                        pagaduriaType == 'SEMGUAINIA' ||
                        pagaduriaType == 'SEMIBAGUE' ||
                        pagaduriaType == 'SEMLORICA' ||
                        pagaduriaType == 'SEDCASANARE' ||
                        pagaduriaType == 'SEMPEREIRA' ||
                        pagaduriaType == 'SEMITAGUI' ||
                        pagaduriaType == 'SEMAPARTADO' ||
                        pagaduriaType == 'SEMBARRANCABERMEJA' ||
                        pagaduriaType == 'SEMPITALITO' ||
                        pagaduriaType == 'SEMRIOHACHA' ||
                        pagaduriaType == 'SEMRIONEGRO' ||
                        pagaduriaType == 'SEMTULUA' ||
                        pagaduriaType == 'SEMTUMACO' ||
                        pagaduriaType == 'SEMSABANETA' ||
                        pagaduriaType == 'SEMSAN' ||
                        pagaduriaType == 'SEMSOACHA' ||
                        pagaduriaType == 'SEMSOGAMOSO' ||
                        pagaduriaType == 'SEMSOLEDAD' ||
                        pagaduriaType == 'SEMESTRELLA' ||
                        pagaduriaType == 'SEDCAQUETA' ||
                        pagaduriaType == 'SEDANTIOQUIA' ||
                        pagaduriaType == 'SEDARAUCA' ||
                        pagaduriaType == 'SEDPUTUMAYO' ||
                        pagaduriaType == 'SEDQUINDIO' ||
                        pagaduriaType == 'SEDSINCELEJO' ||
                        pagaduriaType == 'SEDSUCRE' ||
                        pagaduriaType == 'SEDCESAR' ||
                        pagaduriaType == 'SEDAMAZONAS' ||
                        pagaduriaType == 'SEDTOLIMA' ||
                        pagaduriaType == 'SEDVAUPES' ||
                        pagaduriaType == 'SEDVICHADA' ||
                        pagaduriaType == 'SEDGUAJIRA' ||
                        pagaduriaType == 'SEDGUAVIARE' ||
                        pagaduriaType == 'SEDNARINO'
                    "
                /></div>

                <!-- <DatamesSedChoco v-if="pagaduriaType == 'SEDCHOCO'" /> -->
                <!-- <DatamesSedMagdalena v-if="pagaduriaType == 'SEDMAGDALENA'" /> -->
                <!-- <DatamesSemSahagun v-if="pagaduriaType == 'SEMSAHAGUN'" /> -->

                <!--============================
                COMPONENTE HISTORIAL LABORAL
                ==============================-->
                <template v-if="fechavinc">
                    <div class="info-container col-4">
                    <EmploymentHistory2
                        class="col-12"
                        :fechavinc="fechavinc"
                        :datamesFidu="datamesFidu"
                        :user="user"
                    /></div>
                    <div class="info-container col-4">
                    <EmploymentHistory
                        class="col-12"
                        :fechavinc="fechavinc"
                        :datamesFidu="datamesFidu"
                        :datamessemcali="datamessemcali"
                        :user="user"
                    /></div>
                    <Detallecliente class="detallecliente-top-margin" :totales="totalesData" />
                </template>
                
                <template v-if="showOthers">
                    <DescapliEmpty
                        v-if="
                            pagaduriaType == 'FIDUPREVISORA' ||
                            pagaduriaType == 'SEDVALLE' ||
                            pagaduriaType == 'SEDCAUCA' ||
                            pagaduriaType == 'SEDCHOCO' ||
                            pagaduriaType == 'SEDCALDAS' ||
                            pagaduriaType == 'SEDBOYACA' ||
                            pagaduriaType == 'SEDCORDOBA' ||
                            pagaduriaType == 'SEMQUIBDO' ||
                            pagaduriaType == 'SEMSANTAMARTA' ||
                            pagaduriaType == 'SEMCALI' ||
                            pagaduriaType == 'SEDMAGDALENA' ||
                            pagaduriaType == 'SEMSAHAGUN' ||
                            pagaduriaType == 'SEMMONTERIA' ||
                            pagaduriaType == 'SEMBARRANQUILLA' ||
                            pagaduriaType == 'SEDATLANTICO' ||
                            pagaduriaType == 'SEDBOLIVAR' ||
                            pagaduriaType == 'SEMPOPAYAN' ||
                            pagaduriaType == 'FOPEP' ||
                            pagaduriaType == 'SEMMEDELLIN' ||
                            pagaduriaType == 'SEDHUILA' ||
                            pagaduriaType == 'SEDRISARALDA' ||
                            pagaduriaType == 'SEDMETA' ||
                            pagaduriaType == 'SEDCUNDINAMARCA' ||
                            pagaduriaType == 'SEMMOSQUERA' ||
                            pagaduriaType == 'SEMMAGANGUE' ||
                            pagaduriaType == 'SEMBUGA' ||
                            pagaduriaType == 'SEMNEIVA' ||
                            pagaduriaType == 'SEMZIPAQUIRA' ||
                            pagaduriaType == 'SEMYUMBO' ||
                            pagaduriaType == 'SEMYOPAL' ||
                            pagaduriaType == 'SEMFLORENCIA' ||
                            pagaduriaType == 'SEMFLORIDABLANCA' ||
                            pagaduriaType == 'SEMIPIALES' ||
                            pagaduriaType == 'SEMVALLEDUPAR' ||
                            pagaduriaType == 'SEMURIBIA' ||
                            pagaduriaType == 'SEMTURBO' ||
                            pagaduriaType == 'SEMTUNJA' ||
                            pagaduriaType == 'SEMBUCARAMANGA' ||
                            pagaduriaType == 'SEMMANIZALES' ||
                            pagaduriaType == 'SEMMAICAO' ||
                            pagaduriaType == 'SEMMALAMBO' ||
                            pagaduriaType == 'SEMPASTO' ||
                            pagaduriaType == 'SEMBUENAVENTURA' ||
                            pagaduriaType == 'SEMPALMIRA' ||
                            pagaduriaType == 'SEMJAMUNDI' ||
                            pagaduriaType == 'SEMFUSAGAZUGA' ||
                            pagaduriaType == 'SEMCARTAGO' ||
                            pagaduriaType == 'SEMDUITAMA' ||
                            pagaduriaType == 'SEMFUNZA' ||
                            pagaduriaType == 'SEMESTRELLA' ||
                            pagaduriaType == 'SEMGIRON' ||
                            pagaduriaType == 'SEMPIEDECUESTA' ||
                            pagaduriaType == 'SEMPEREIRA' ||
                            pagaduriaType == 'SEMGIRARDOT' ||
                            pagaduriaType == 'SEMGUAINIA' ||
                            pagaduriaType == 'SEMLORICA' ||
                            pagaduriaType == 'SEMIBAGUE' ||
                            pagaduriaType == 'SEMCHIA' ||
                            pagaduriaType == 'SEMITAGUI' ||
                            pagaduriaType == 'SEMAPARTADO' ||
                            pagaduriaType == 'SEMBARRANCABERMEJA' ||
                            pagaduriaType == 'SEMPITALITO' ||
                            pagaduriaType == 'SEMRIOHACHA' ||
                            pagaduriaType == 'SEMRIONEGRO' ||
                            pagaduriaType == 'SEMTULUA' ||
                            pagaduriaType == 'SEMTUMACO' ||
                            pagaduriaType == 'SEMSABANETA' ||
                            pagaduriaType == 'SEMSAN' ||
                            pagaduriaType == 'SEMSOACHA' ||
                            pagaduriaType == 'SEMSOGAMOSO' ||
                            pagaduriaType == 'SEMSOLEDAD' ||
                            pagaduriaType == 'SEMBELLO' ||
                            pagaduriaType == 'SEMCIENAGA' ||
                            pagaduriaType == 'SEMCARTAGENA' ||
                            pagaduriaType == 'SEMARMENIA' ||
                            pagaduriaType == 'SEMVILLAVICENCIO' ||
                            pagaduriaType == 'SEMCUCUTA' ||
                            pagaduriaType == 'SEMDOSQUEBRADAS' ||
                            pagaduriaType == 'SEMENVIGADO' ||
                            pagaduriaType == 'SEMFACATATIVA' ||
                            pagaduriaType == 'SEDNORTEDESANTANDER' ||
                            pagaduriaType == 'SEDSANTANDER' ||
                            pagaduriaType == 'SEDCASANARE' ||
                            pagaduriaType == 'SEDCAQUETA' ||
                            pagaduriaType == 'SEDANTIOQUIA' ||
                            pagaduriaType == 'SEDARAUCA' ||
                            pagaduriaType == 'SEDPUTUMAYO' ||
                            pagaduriaType == 'SEDQUINDIO' ||
                            pagaduriaType == 'SEDSINCELEJO' ||
                            pagaduriaType == 'SEDSUCRE' ||
                            pagaduriaType == 'SEDCESAR' ||
                            pagaduriaType == 'SEDAMAZONAS' ||
                            pagaduriaType == 'SEDTOLIMA' ||
                            pagaduriaType == 'SEDVAUPES' ||
                            pagaduriaType == 'SEDVICHADA' ||
                            pagaduriaType == 'SEDGUAJIRA' ||
                            pagaduriaType == 'SEDGUAVIARE' ||
                            pagaduriaType == 'SEDNARINO'
                        "
                        :disabledProspect="disabledProspect"
                    />
                    <hr class="divider" >
                    <!--===================================
                            OBLIGACIONES VIGENTES EN MORA
                    ========================================-->
                    <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'" />
                    <Descnoap v-if="pagaduriaType == 'FOPEP'" :descnoap="descnoap" />

                    <EmbargosEmpty v-if="pagaduriaType == 'SED'" :embargosempty="embargosempty" />
                    <Embargos v-else />
                    <hr class="divider" >
                    <!--===================================
                            LIQUIDACIONES
                    ========================================-->
                    <!-- <Descuentossemsahagun
                        v-if="pagaduriaType == 'SEMSAHAGUN'"
                        :descuentossemsahagun="descuentossemsahagun"
                    /> -->
                    <DescuentosEmpty v-if="pagaduriaType == 'SED'" :descuentosempty="descuentosempty" />
                    <Descuentos v-else />
                    <hr class="divider" >
                    <div class="col-12">
                        <CustomButton text="Visar" style="width: 164px;" @click="visadoFunction"/>
                        <!-- <b-button class="mb-3" variant="black-pearl" @click="visadoFunction">Visar</b-button> -->
                    </div>
                    
                </template>

                <!-- <Others
                    v-if="showOthers && pagadurias"
                    :pagaduriaType="pagaduriaType"
                    :pagadurias="pagadurias"
                    :fechavinc="fechavinc"
                    :descapli="descapli"
                    :descnoap="descnoap"
                    :embargossedvalle="embargossedvalle"
                    :user="user"
                /> -->
            </div>
        </div>
    </div>
</template>

<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />

<script>
import printJS from 'print-js';
import FormConsult from './FormConsult';
import EmploymentHistory from './EmploymentHistory';
import EmploymentHistory2 from './EmploymentHistory2';
import DatamesComponent from './Datames.vue';
import CustomButton from '../../customComponents/CustomButton.vue';
import DatamesData from './DatamesData';
import DatamesData2 from './DatamesData2';
import DatamesFidu from './DatamesFidu';
import DatamesSedChoco from './DatamesSedChoco.vue';
import DatamesSedMagdalena from './DatamesSedMagdalena';
import DatamesSedValle from './DatamesSedValle.vue';
import DatamesSemCali from './DatamesSemCali.vue';
import DatamesSemSahagun from './DatamesSemSahagun';

import Descapli from './Descapli';
import DescapliEmpty from './DescapliEmpty.vue';
import Descnoap from './Descnoap';
import DescnoapEmpty from './DescnoapEmpty';
import Others from './Others.vue';
import Embargos from './Embargos.vue';
import EmbargosEmpty from './EmbargosEmpty';
import Descuentos from './Descuentos.vue';
import DescuentosEmpty from './DescuentosEmpty';
import Detallecliente from './Detallecliente';
import Descuentossemsahagun from './Descuentossemsahagun';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import { mapActions, mapState, mapGetters } from 'vuex';

export default {
    props: ['user'],
    created() {},
    components: {
        FormConsult,
        EmploymentHistory,
        EmploymentHistory2,
        DatamesComponent,
        DatamesData,
        DatamesData2,
        DatamesFidu,
        DatamesSedChoco,
        DatamesSedMagdalena,
        DatamesSedValle,
        DatamesSemCali,
        DatamesSemSahagun,
        Descapli,
        DescapliEmpty,
        Descnoap,
        DescnoapEmpty,
        Others,
        Embargos,
        Detallecliente,
        Descuentos,
        Descuentossemsahagun,
        DescuentosEmpty,
        EmbargosEmpty,
        CustomButton,
        Loading
    },

    data() {
        return {
            type_consult: 'individual',
            fechavinc: null,
            datamesFopep: null,
            datamessedvalle: null,
            datamesFidu: null,
            datamessemcali: null,
            descapli: [],
            descnoap: [],
            embargossedvalle: [],
            embargossedchoco: [],
            embargossedatlantico: [],
            embargossedcauca: [],
            embargossemquibdo: [],
            embargossempopayan: [],
            embargossemcali: [],
            embargosempty: [],
            descuentossedvalle: [],
            descuentosempty: [],
            descuentossedchoco: [],
            descuentossedcauca: [],
            descuentossemcali: [],
            descuentossemquibdo: [],
            descuentossemsahagun: [],
            descuentossempopayan: [],

            monto: 0,

            pagaduriaKey: '',
            cargo: null,
            showOthers: false,
            pagadurias: null,
            isLoading: false,
            disabledProspect: false,
            visado: null,
            visadoValido: 'NO FACTIBLE'
        };
    },
    watch: {
        couponsIngresos: {
       handler() {
           this.calcularTotales();
       },
       deep: true,
       immediate: true
   },

        ingresosExtras(val) {
            let totalIncapacidad = 0;

            this.ingresosExtras.some(item => {
                if (item.concept.includes('Definitiva') || item.concept.includes('definitiva')) {
                    let data = {
                        message: 'Cliente en proceso de retiro',
                        variant: 'danger'
                    };
                    this.alertDefinitiva(data);
                    return true;
                }
            });

            this.ingresosExtras.forEach(item => {
                if (item.concept.includes('Incapacidad') || item.concept.includes('incapacidad')) {
                    totalIncapacidad += Number(item.ingresos);
                }
            });

            // Valida si el valor de la incapacidad es mayor al valor del ingreso
            if (Number(totalIncapacidad) > Number(this.valorIngreso)) {
                let data = {
                    message: 'Cliente no apto por incapacidad',
                    variant: 'danger'
                };
                this.alertIncapacidad(data);
            }
        }
    }, 
    mounted() {
   if (this.couponsIngresos) {
       this.calcularTotales();
   }
},

    computed: {
        ...mapState('pagaduriasModule', ['coupons', 'couponsType', 'pagaduriaType', 'pagaduriaLabel']),
        ...mapGetters('pagaduriasModule', [
            'couponsPerPeriod',
            'valorIngreso',
            'ingresosIncapacidadPerPeriod',
            'incapacidadValida',
            'couponsIngresos',
            'ingresosExtras'
        ]),
        ...mapState('embargosModule', ['embargosType']),
        ...mapState('descuentosModule', ['descuentosType']),
        ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresosPlus']),
        ...mapGetters('descuentosModule', ['descuentosPerPeriod']),
        totales() {
            const valrSM = 1300000;

            //REGLAS BASICAS:

    //Si el sueldo (sueba)(despues de aportes) es menor a dos minimos entonces - 1 minimo
    //si el sueldo (sueba) (despues de aportes) es mayor a dos minimos entonces se pica por la mitad 
    //Si el sueldo (sueba) es mayor a 5.2millones se resta ademas de los aportes el 1% de solidaridad y se pica por la mitad
    //si el sueld basico (code sueba) es mayor a 6.2 millones entonces  sueldo -aportes-solidaridad- promedio 4 meses de renta y piquelo por la mitad 

    //TODO LO ANTERIOR se le llama COMPRA CARTERA (una variable)
    //CUPO LIBRE INVERSION es COmpra Cartera - egresos (no incluye, porque ya se incluyo aporte, solidaridad y promedio de renta)


            let totalWithoutHealthPension = 0;
            this.couponsIngresos.items.forEach(item => {
                if (item.code !== 'APFPM' && item.code !== 'APEPEN' && item.code !== 'APESDN') {
                    totalWithoutHealthPension += Number(item.vaplicado);
                }
            });

            let valorIngreso = 0;
            if (this.pagaduriaType === 'FOPEP') { // (aparte del 8% de pagadurias) 3 reglas: <=minimo entonces 4% --- > minimo < 3.900.000 10% ---  >3900000 12% 
                valorIngreso = Number(this.datamesFopep.vpension.replace(/[^0-9]/g, '').slice(0, -2));
            } else if (this.pagaduriaType == 'FIDUPREVISORA') {
                valorIngreso = Number(this.datamesFidu.vpension.replace(/[^0-9]/g, '').slice(0, -2));
            }
            else {
                valorIngreso = this.couponsPerPeriod.items.filter(item => item.code === 'INGCUP')[0]?.ingresos || 0; //buscar concepto
            }

            let increase = 0;
            if (this.cargo == 'Rector Institucion Educativa Completa') {  ///comprobar los cargos 
                increase = valorIngreso * 0.3;
                valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
            } else if (this.cargo == 'Coordinador') {
                increase = valorIngreso * 0.2;
                valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
            } else if (this.cargo == 'Director De Nucleo') {
                increase = valorIngreso * 0.35;
                valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
            }

            let disccount = 0.08;
            if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
                if (valorIngreso == valrSM) {
                    disccount = 0.04;
                } else if (valorIngreso > valrSM && valorIngreso < valrSM * 2) { //no aplica, son 3900000
                    disccount = 0.08;
                } else if (valorIngreso >= valrSM * 2) {
                    disccount = 0.12;
                }
            }

            //hay que adicionar un 1% menos de los ingresos para personas que ganen màs de 5.2 millones antes de deducciones
            //hay que restar la retencion (cuadrar con briyit)

            const valorIngresoTemp = valorIngreso - valorIngreso * disccount;
            console.log('valoringresotemp', valorIngresoTemp);

            let items = [];
            // let itemslength = [];

            let totalEgresos = 0;
            if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
                items = this.descapli;
                // itemslength = items.length;
                totalEgresos = items.reduce((a, b) => a + Number(b.vaplicado), 0);
            } else {
                items = this.couponsPerPeriod.items.filter(item => item.code !== 'INGCUP' && Number(item.egresos) > 0);
                // itemslength = items.length;
                totalEgresos = items.reduce((total, item) => total + Number(item.egresos), 0);
            }

            let previousDiscount = valorIngresoTemp / 2;

            let libreInversion = 0;
            if (valorIngresoTemp < valrSM * 2) {
                libreInversion = valorIngresoTemp - valrSM - totalWithoutHealthPension;
            } else {
                libreInversion = valorIngresoTemp / 2 - totalWithoutHealthPension;
            }

            let libreInversionSuma = libreInversion;
            console.log('libreinversion original', libreInversion);

            let compraCartera = 0;
            if (previousDiscount < valrSM) {
                compraCartera = valorIngresoTemp - valrSM;
            } else {
                compraCartera = valorIngresoTemp / 2;
            }

            let cuotaMaxima = 0;
            if (previousDiscount < valrSM) {
                cuotaMaxima = valorIngresoTemp - valrSM;
            } else {
                cuotaMaxima = valorIngresoTemp / 2;
            }

            return {
                libreInversion: libreInversion < 0 ? 0 : libreInversion,
                libreInversionSuma: libreInversionSuma,
                compraCartera: compraCartera < 0 ? 0 : compraCartera,
                cuotaMaxima: cuotaMaxima < 0 ? 0 : cuotaMaxima
            };
        }
    },
    methods: {
        ...mapActions('pagaduriasModule', ['fetchCoupons']),
        ...mapActions('embargosModule', ['fetchEmbargos']),
        ...mapActions('descuentosModule', ['fetchDescuentos']),
        emitInfo(payload) {
            this.isLoading = true;
            this.pagadurias = payload.pagadurias;
            this.pagaduriaKey = payload.pagaduriaKey;
            this.cargo = payload.cargo;

            this.datamesFopep = null;
            this.datamessedvalle = null;
            this.datamesFidu = null;
            this.datamessemcali = null;
            this.visado = payload.visado;

            this.monto = payload.monto;

            if (payload.pagaduria == 'FOPEP') {
                this.getDatames(payload);
            } else if (payload.pagaduria == 'SEDVALLE') {
                this.getDatamesSedValle(payload);
            } else if (payload.pagaduria == 'FIDUPREVISORA') {
                this.getDatamesFidu(payload);
            } else if (payload.pagaduria == 'SEMCALI') {
                this.getDatamesSemCali(payload);
            }

            this.getDescuentossemsahagun(payload);
            this.getDescapli(payload);
            this.getDescnoap(payload);
            this.getCoupons({
                doc: payload.doc,
                pagaduria: this.couponsType,
                pagaduriaLabel: this.pagaduriaLabel
            });
            this.getEmbargos({
                doc: payload.doc,
                pagaduria: this.embargosType,
                pagaduriaLabel: this.pagaduriaLabel
            });
            this.getDescuentos({
                doc: payload.doc,
                pagaduria: this.descuentosType,
                pagaduriaLabel: this.pagaduriaLabel
            });
            this.getFechaVinc(payload).then(response => {
                this.showOthers = true;
                this.isLoading = false;
            });
        },
        async getDatames(payload) {
            const response = await axios.get(`/datamesfopep/${payload.doc}`);
            this.datamesFopep = response.data;
        },
        async getDatamesSedValle(payload) {
            const response = await axios.post('/datamessedvalle/consultaUnitaria', { doc: payload.doc });
            this.datamessedvalle = response.data.data;
        },
        async getDatamesFidu(payload) {
            const response = await axios.post('/datamesfidu/consultaUnitaria', { doc: payload.doc });
            this.datamesFidu = response.data.data;
        },
        async getDatamesSemCali(payload) {
            const response = await axios.post('/consultaDatamessemcali', { doc: payload.doc });
            this.datamessemcali = response.data.data;
        },
        async getFechaVinc(payload) {
            const response = await axios.get(`/fechavinc/${payload.doc}`);
            this.fechavinc = response.data;
        },
        async getDescapli(payload) {
            const response = await axios.get(`/descapli/${payload.doc}`);
            this.descapli = response.data;
        },
        async getDescnoap(payload) {
            const response = await axios.get(`/descnoap/${payload.doc}`);
            this.descnoap = response.data;
        },
        async getDescuentossemsahagun(payload) {
            const response = await axios.post('/consultaDescuentossemsahagun', { doc: payload.doc });
            this.descuentossemsahagun = response.data.data;
        },
        async getCoupons(payload) {
            const data = {
                doc: payload.doc,
                pagaduria: payload.pagaduria,
                pagaduriaLabel: payload.pagaduriaLabel
            };

            const response = await axios.post('/get-coupons', data);
            this.fetchCoupons(response.data);

            setTimeout(() => {
                // Valida si el tiene incapacidades
                if (this.incapacidadValida === false) {
                    this.$bvToast.show('toast-incapacidad-month');
                }
            }, 1000);
        },
        async getEmbargos(payload) {
            const data = {
                doc: payload.doc,
                pagaduria: payload.pagaduria,
                pagaduriaLabel: payload.pagaduriaLabel
            };

            const response = await axios.post('/get-embargos', data);
            this.fetchEmbargos(response.data);
        },
        async getDescuentos(payload) {
            const data = {
                doc: payload.doc,
                pagaduria: payload.pagaduria,
                pagaduriaLabel: payload.pagaduriaLabel
            };

            const response = await axios.post('/get-descuentos', data);
            this.fetchDescuentos(response.data);
        },
        print() {
            window.print();
        },
        alertIncapacidad(data) {
            this.$bvToast.toast(`${data.message}`, {
                title: data.title ? data.title : 'Alerta del sistema',
                autoHideDelay: 10000,
                variant: data.variant,
                solid: true
            });
        },
        alertDefinitiva(data) {
            this.disabledProspect = true;
            this.$bvToast.toast(`${data.message}`, {
                title: data.title ? data.title : 'Alerta del sistema',
                autoHideDelay: 10000,
                variant: data.variant,
                solid: true
            });
        },

        //Visando consulta
        visadoFunction() {
            let causal = '';
            let obligacionMarcadas = false;
            let embargosSinMora = false;

            const cuotaMaximaDef = this.conteoEgresosPlus + this.totales.libreInversionSuma;
            console.log('cuotaMaximaDef', cuotaMaximaDef);

            const definitivaAlerta = this.ingresosExtras.some(
                item => item.concept.includes('Definitiva') || item.concept.includes('definitiva')
            );

            const cuotaMenor = Number(this.cuotadeseada) < cuotaMaximaDef;
            const cuotaMayor = Number(this.cuotadeseada) > cuotaMaximaDef;

            if (this.descuentosPerPeriod.total > 0) {
                obligacionMarcadas = this.descuentosPerPeriod.items.some(item => item.check == true);
            } else {
                embargosSinMora = true;
            }

            if (cuotaMenor === true && obligacionMarcadas === false) {
                console.log('hola');
                this.visadoValido = 'NO FACTIBLE';
                causal = 'Presenta obligaciones en mora';
            } else if (cuotaMenor === true && obligacionMarcadas === true) {
                console.log('hola2');
                this.visadoValido = 'FACTIBLE';
                causal = 'Sin causal';
            }

            if (cuotaMayor === true && embargosSinMora === true) {
                console.log('hola3');
                this.visadoValido = 'NO FACTIBLE';
                causal += 'Negado por cupo';
            } else if (cuotaMenor === true && embargosSinMora === true) {
                console.log('hola4');
                this.visadoValido = 'FACTIBLE';
                causal = 'Sin causal';
            } else {
                if (cuotaMayor === true && obligacionMarcadas === false && embargosSinMora === false) {
                    console.log('hola5');
                    this.visadoValido = 'NO FACTIBLE';
                    causal = '1. Presenta obligaciones en mora, 2. Negado por cupo';
                } else if (cuotaMayor === true && obligacionMarcadas === true) {
                    console.log('hola6');
                    this.visadoValido = 'NO FACTIBLE';
                    causal = 'Negado por cupo';
                }
            }

            if (definitivaAlerta) {
                console.log('hola7');
                this.visadoValido = 'NO FACTIBLE';
                causal = 'Cliente en proceso de retiro';
            }

            const data = {
                estado: this.visadoValido,
                cuotacredito: this.cuotadeseada,
                monto: this.monto,
                causal: causal
            };

            axios
                .post(`/visados/${this.visado.id}`, data)
                .then(response => {
                    console.log('response', response);
                    window.location.href = '/historyClient';
                    
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async calcularTotales() {
   console.log("Entrando a calcularTotales");
   const firstItem = this.couponsIngresos?.items?.[0];
   if (!firstItem) {
       console.error('No se encontró ningún elemento en couponsIngresos.items');
       return;
   }


   const { finperiodo, doc } = firstItem;
   if (!finperiodo || isNaN(new Date(finperiodo).getTime())) {
       console.error('El campo finperiodo no es válido:', finperiodo);
       return;
   }


   const finPeriodDate = new Date(finperiodo);
   const mes = finPeriodDate.getMonth() + 1;
   const año = finPeriodDate.getFullYear();


   try {
       this.isLoading = true;
       const response = await fetch(`/demografico/calcular-cupo/${doc}/${mes}/${año}`);
       if (!response.ok) {
           throw new Error(`Error al obtener datos: ${response.status}`);
       }


       const data = await response.json();


       console.log("Respuesta del servidor:", data);


       // Asegúrate de que data es un array y accede al primer elemento
       const result = data;


       if (!result) {
           console.error("El array de respuesta está vacío");
           return;
       }


       this.totalesData = {
           libreInversion: result.cupo_libre || 0,
           libreInversionSuma: result.libreInversionSuma || 0,
           compraCartera: result.compra_cartera || 0,
           cuotaMaxima: result.cuotaMaxima || 0,
       };


       console.log("Totales actualizados y reflejados en el DOM:", this.totalesData);


   } catch (error) {
       console.error('Error en calcularTotales:', error);
   } finally {
       this.isLoading = false;
   }
}

    }
};
</script>
<style>
.info-container {
    display: flex;
    justify-content: space-between;
    gap: 20px; /* Espaciado entre los bloques */
    margin-top: 20px;
}

.info-block {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    background-color: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 30%; /* Ajustar el ancho para un diseño responsivo */
}
.table-text {
    font-size: 12px;
}

.tables-space {
    margin-top: 15px !important;
}

.divider{
    width: 100%;             
    height: 2px;             
    background-color: #70777f;   
    border: none;           
    margin: 20px 12px;        
}

.detallecliente-top-margin {
    margin-top: 20px;
}
</style>
