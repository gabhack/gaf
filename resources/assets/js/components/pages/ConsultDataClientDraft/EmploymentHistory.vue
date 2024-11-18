<template>
    <div class="col-4">
        <div class="panel panel-primary mb-3">
     <h5 class="heading-title" style="border: 10px; padding-left: 8px; padding-bottom: 4px; background-color: #3a5659; color: white;">Historial laboral</h5>
            
            <div v-if="datamesSedArray.length > 0" class="general-info"><br>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    <li v-for="(item, index) in datamesSedArray" :key="index">
                        <p>
                            <b style="color:#2c8c73">Fecha ingreso:</b><br><span>{{ item.fecha_ingreso || '--' }}</span>
                        </p>
                        <p>
                            <b style="color:#2c8c73">Fecha vinculación:</b><br><span>{{ item.fnombramiento || '--' }}</span>
                        </p>
                        <p>
                            <b style="color:#2c8c73">Cargo:</b><br><span>{{ item.cargo || '--' }}</span>
                        </p>
                        <p>
                            <b style="color:#2c8c73">Grado:</b><br><span>{{ item.grado || '--' }}</span>
                        </p>
                        <p>
                            <b style="color:#2c8c73">Principal:</b><br><span>{{ item.depen || '--' }}</span>
                        </p>
                        <p>
                            <b style="color:#2c8c73">Ciudad laboral:</b><br><span>{{ item.ciudad || '--' }}</span>
                        </p>
                    </li>
                </ul>
            </div>

                    <!--============================
                            FOPEP
                    ==============================-->
                    <template v-if="pagaduriaType === 'FOPEP'">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                </div>
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value">{{ valorIngreso | currency }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">SUELDO BASICO:</b>
                                </div>
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                                        <p class="panel-value" v-else>{{ datamesSed.pension | currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-4" v-if="ingresosExtras.length > 0">
                            <b class="panel-label">INGRESOS EXTRAS:</b>
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">CONCEPTO:</b>
                                </div>
                                <div class="col-2">
                                    <b class="panel-label">VALOR:</b>
                                </div>
                            </div>
                            <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value">{{ extra.concept }}</p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value">{{ extra.ingresos | currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">TIPO PENSION:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.tp }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.vpension | currency }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <b class="panel-label">VALOR SALUD:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.vsalud | currency }}</p>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <b class="panel-label">VALOR DESCUENTOS:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.vdesc | currency }}</p>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <b class="panel-label">VALOR CUPO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.cupo | currency }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <b class="panel-label">VALOR EMBARGOS:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.vembargos | currency }}</p>
                            </div>
                        </div>
                    </template>

                    <!--============================
                            SED VALLE
                    ==============================-->

                    <div v-if="datamessedvalle">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                </div>
                                <div class="col-2">
                                    <p class="panel-value">{{ valorIngreso | currency }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">SUELDO BASICO:</b>
                                </div>
                                <div class="col-2">
                                    <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                                    <p class="panel-value" v-else>{{ datamessedvalle.vpension | currency }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-4" v-if="ingresosExtras.length > 0">
                            <b class="panel-label">INGRESOS EXTRAS:</b>
                            <div class="row">
                                <div class="col-2">
                                    <b class="panel-label">CONCEPTO:</b>
                                </div>
                                <div class="col-2">
                                    <b class="panel-label">VALOR:</b>
                                </div>
                            </div>
                            <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value">{{ extra.concept }}</p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div>
                                        <p class="panel-value">{{ extra.ingresos | currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">FECHA INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.fechingr }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">AREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.esquema }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.cargo }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">FECHA VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.fecnombr }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label"> TIPO DE CONTRATO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.ncontr }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">GRADO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.grado ? datamessedvalle.grado : '-' }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">PRINCIPAL :</b>
                            <div>
                                <p class="panel-value">
                                    {{ datamessedvalle.dependencia ? datamessedvalle.dependencia : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">SEDE:</b>
                            <div>
                                <p class="panel-value">
                                    {{ datamessedvalle.centrocosto ? datamessedvalle.centrocosto : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">TIPO VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.nivcontr }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">ESTADO LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.estlaboral }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.centrocosto }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.sedecoleg }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">CIUDAD LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.ciudad ? datamessedvalle.ciudad : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!--============================
                        FIDUPREVISORA
                    ==============================-->
                    <template v-if="datamesfidu">
                        <div class="col-2">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">VINCULACION:</b>
                            <div>
                                <p class="panel-value">{{ datamesfidu.vinc }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">FECHA DE PAGO PENSION:</b>
                            <div>
                                <p class="panel-value">{{ datamesfidu.fechpago }}</p>
                            </div>
                        </div>

                        <div class="col-2">
                            <b class="panel-label">VALOR DESCUENTO:</b>
                            <div>
                                <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
                            </div>
                        </div>
                    </template>

                    <!-- DATAMES SED -->
                    <template v-if="datamesfidu || datamessedvalle || pagaduriaType === 'FOPEP'"> </template>
                    <div v-else-if="datamesSed">
                              
                                <div v-if="arrayCoupons.length> 0">
                                <thead>
                                       <tr>
                                            <th style="color: #2c8c73; white-space: nowrap;">Valor ingreso</th>
                                       </tr> 
                                </thead>
                                    <tbody>
                                        <td style="font-size: 14px;">{{ valorIngreso | currency }}</td>
                                    </tbody> 
                                <thead>
                                        <tr>   
                                            <th style="color: #2c8c73;">Total</th>
                                        </tr>
                                </thead>
                                    <tbody>           
                                        <tr> 
                                            <td>{{ valorIngreso | currency }}</td>
                                        </tr> 
                                    </tbody>       
                                    
                                </div>                            
                                <div v-if="salarioBasico.length > 0">
                                    <div v-for="(item, index) in salarioBasico":key="index">
                                        <thead>
                                                <tr>
                                                  <th>{{ item.concept }}</th>
                                                </tr>        
                                        </thead>
                                        <tbody> 
                                                    <td>{{ item.ingresos | currency }}</td>
                                        </tbody>
                                    </div>
                                </div>
                                    <div v-if="ingresosExtras.length > 0" style="width: 100%;">
                                            <div v-for="(item, index) in ingresosExtras":key="index">
                                                <thead><tr><th>{{ item.concept }}</th></tr></thead>
                                                <tbody><td>{{ item.ingresos | currency }}</td></tbody>
                                            </div>
                                    </div>
                                <div v-if="arrayCoupons.length > 0" style="width: 100%;">
                                
                                        <thead> <tr><th>Días laborados</th></tr></thead>
                            <tbody><td>
                                Total días
                                <b style="font-weight: normal; color: inherit; padding-left: 10px; text-align: right;">{{ arrayCoupons.length > 0 ? arrayCoupons[0].dias_laborados : '--' }}</b>
                                    </td>
                            </tbody>
                                  
                                </div>
                        </div>
                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">FECHA CARGA DATA:</b>
                        <div>
                            <p class="panel-value">{{ fechavinc.fecdata }}</p>
                        </div>
                    </div>
                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">MES CARGA DATA:</b>
                        <div>
                            <p class="panel-value">{{ fechavinc.mesdata }}</p>
                        </div>
                    </div>
                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">AÑO CARGA DATA:</b>
                        <div>
                            <p class="panel-value">{{ fechavinc.anodata }}</p>
                        </div>
                    </div>
                    <div class="col-6" v-if="fechavinc.vinc">
                        <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                        <div>
                            <p class="panel-value">{{ fechavinc.vinc }}</p>
                        </div>
                    </div>
                </div>
                </div>
          </template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
    name: 'EmploymentHistory',
    props: ['fechavinc', 'datamessedvalle', 'datamesfidu', 'datamessemcali', 'user'],
    data() {
        return {
            Sueldo: [
                { key: 'concept', label: 'Sueldo básico' },
                { key: 'ingresos', label: 'Valor' }
            ],
            Extras: [
                { key: 'concept', label: 'Ingresos extras' },
                { key: 'ingresos', label: 'Valor' }
            ],
            Ingreso: [
                { key: 'label', label: 'Valor ingreso' },
                { key: 'value', label: 'Valor' }
            ],
            fields: [
                {
                    key: 'fecha_ingreso',
                    label: 'Fecha ingreso',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'fnombramiento',
                    label: 'Fecha vinculacíon',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'cargo',
                    label: 'Cargo',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'grado',
                    label: 'Grado',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'depen',
                    label: 'Principal',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'ciudad',
                    label: 'Ciudad laboral',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                }
            ],
            arrayCoupons: []
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType', 'setSelectedPeriod']),
        ...mapGetters('pagaduriasModule', [
            'ingresosExtras',
            'salarioBasico',
            'valorIngreso',
            'couponsIngresos',
            'pagaduriaPeriodos'
        ]),

        datamesSedArray() {
            return [this.datamesSed];
        },
        revisarAca() {
            return this.couponsIngresos.items;
        }
    },
    watch: {
        couponsIngresos() {
            this.arrayCoupons = [];
            this.arrayCoupons = [...this.couponsIngresos.items];
            function extractNumber(str) {
                const match = str.match(/\d+/);
                return match ? match[0] : null;
            }
            this.arrayCoupons.forEach(coupon => {
                coupon.dias_laborados = extractNumber(coupon.dias_laborados);
            });
        }
    }
};
</script>
<style scoped>
th {
    color:#2c8c73;
    font-size: 14px;
}
ul {
    margin: 0;
    padding: 0;
}
li {
    list-style-type: none;
    margin-bottom: 10px;
    padding: 0;
    border-bottom: none;
}
p {
    margin: 0;
    font-size: 14px;
}
b {
    font-size: 14px;
}
</style>
