<template>
    <div class="col-12" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title">Información laboral</h3>

            <div class="mt-3 table-responsive">
                <table role="table" aria-colcount="4" class="table b-table table-striped table-hover">
                    <!----><!---->
                    <thead role="rowgroup" class="table-header-nowrap">
                        <!---->
                        <tr role="row">
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="1">Fecha ingreso</th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="2">
                                Fecha ingreso nómina
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="3">
                                Fecha de inicio
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="4">
                                Antiguedad laboral
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
                            <td class="text-center" aria-colindex="3" role="cell">
                                {{ datamesSed.fecha_inicio || '--' }}
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
                            <th role="columnheader" scope="col" aria-colindex="2">Tipo de contrato</th>
                            <th role="columnheader" scope="col" aria-colindex="3">Situación laboral</th>
                            <th role="columnheader" scope="col" aria-colindex="4">Área de desempeño</th>
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
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
    name: 'EmploymentHistory',
    props: ['fechavinc', 'datamessedvalle', 'datamesfidu', 'datamessemcali', 'user'],
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
</style>
