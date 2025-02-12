<template>
    <div class="w-100 px-0" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3
                class="heading-title w-100 d-flex align-items-center justify-content-start"
                :class="visible ? null : 'collapsed'"
                :aria-expanded="visible ? 'true' : 'false'"
                aria-controls="info-laboral"
                @click="visible = !visible"
                style="cursor: pointer; gap: 10px"
            >
                <!-- SVG -->
                <svg
                    version="1.1"
                    :class="{ rotate180: visible }"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
                    x="0px"
                    y="0px"
                    width="15px"
                    height="9px"
                    viewBox="0 0 15 9"
                    style="enable-background: new 0 0 15 9"
                    xml:space="preserve"
                >
                    <defs></defs>
                    <path
                        fill="#3a5659"
                        d="M6.4,8.6C7,9.1,8,9.1,8.6,8.6l6-6c0.4-0.4,0.6-1.1,0.3-1.6C14.6,0.4,14.1,0,13.5,0l-12,0C0.9,0,0.3,0.4,0.1,0.9
	S0,2.1,0.4,2.6L6.4,8.6L6.4,8.6z"
                    />
                </svg>

                Información LaboralN2
            </h3>
            <b-collapse id="info-laboral" v-model="visible" class="mt-2">
                <div class="mt-3 table-responsive">
                    <table role="table" aria-colcount="4" class="table b-table table-striped table-hover">
                        <!----><!---->
                        <thead role="rowgroup" class="table-header-nowrap">
                            <!---->
                            <tr role="row">
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="1">
                                    Fecha de Ingreso
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="2">
                                    Fecha Ingreso Nómina
                                </th>

                                <th class="text-center" role="columnheader" scope="col" aria-colindex="4">
                                    Antiguedad Laboral
                                </th>
                            </tr>
                        </thead>
                        <tbody role="rowgroup">
                            <!---->
                            <tr role="row">
                                <td aria-colindex="1" role="cell">{{ datamesSed.fecha_ingreso || '--' }}</td>
                                <td class="text-center" aria-colindex="2" role="cell">
                                    {{ datamesSed.fecha_ingreso_nomina || '--' }}
                                </td>

                                <td class="text-center" aria-colindex="4" role="cell">
                                    {{ datamesSed.antiguedad ? datamesSed.antiguedad + ' años' : '--' }}
                                </td>
                            </tr>

                            <!----><!---->
                        </tbody>
                        <!---->
                    </table>
                </div>

                <div class="mt-3 table-responsive">
                    <table role="table" aria-colcount="4" class="table b-table table-striped table-hover">
                        <!----><!---->
                        <thead role="rowgroup" class="table-header-nowrap">
                            <!---->
                            <tr role="row">
                                <th role="columnheader" scope="col" aria-colindex="1">Cargo</th>
                                <th role="columnheader" scope="col" aria-colindex="2">Tipo de Contrato</th>
                                <th role="columnheader" scope="col" aria-colindex="3">Situación Laboral</th>
                                <th role="columnheader" scope="col" aria-colindex="4">Área de Desempeño</th>
                            </tr>
                        </thead>
                        <tbody role="rowgroup">
                            <!---->
                            <tr role="row">
                                <td aria-colindex="1" role="cell">{{ datamesSed.cargo || '--' }}</td>
                                <td aria-colindex="2" role="cell">{{ datamesSed.tipo_contrato || '--' }}</td>
                                <td aria-colindex="3" role="cell">{{ datamesSed.situacion_laboral || '--' }}</td>
                                <td aria-colindex="4" role="cell">{{ datamesSed.area_desempeño || '--' }}</td>
                            </tr>

                            <!----><!---->
                        </tbody>
                        <!---->
                    </table>
                </div>
            </b-collapse>
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
            visible: true
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos']),
        ...mapState('pagaduriasModule', ['coupons']),
        ...mapGetters('pagaduriasModule', ['ingresosExtras', 'salarioBasico', 'valorIngreso']),
        fechaIngreso() {
            return this.datamesSed.fecha_ingreso;
        },
        antiguedad() {
            return this.calcularAntiguedad(this.fechaIngreso);
        },
        datamesSedArray() {
            return [this.datamesSed];
        }
    },

    methods: {
        calcularAntiguedad(fechaIngreso) {
            var partes = fechaIngreso.split('/');
            var fechaNacimientoFormatoCorrecto = partes[1] + '/' + partes[0] + '/' + partes[2];
            var hoy = new Date();
            var ingreso = new Date(fechaNacimientoFormatoCorrecto);
            var antiguedad = hoy.getFullYear() - ingreso.getFullYear();
            var m = hoy.getMonth() - ingreso.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < ingreso.getDate())) {
                antiguedad = antiguedad - 1;
            }
            return antiguedad;
        }
    }
};
</script>
<style scoped>
th {
    font-size: 14px;
}
.table thead th {
    font-weight: 600;
    vertical-align: middle;
}
</style>
