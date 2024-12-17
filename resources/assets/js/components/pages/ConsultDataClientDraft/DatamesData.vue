<template>
    <div class="col-12" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title">Información personal</h3>

            <div class="mt-3 table-responsive">
                <table role="table" aria-colcount="5" class="table b-table table-striped table-hover">
                    <!----><!---->
                    <thead role="rowgroup" class="table-header-nowrap">
                        <!---->
                        <tr role="row">
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="1">
                                Nombre y apellido
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="2">
                                Tipo de documento
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="3">
                                N<sup>a</sup> documento
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="4">
                                Fecha de nacimiento
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="5">Edad</th>
                        </tr>
                    </thead>
                    <tbody role="rowgroup">
                        <!---->
                        <tr role="row">
                            <td aria-colindex="1" role="cell">{{ datamesSed.nombre_usuario || '--' }}</td>
                            <td class="text-center" aria-colindex="2" role="cell">
                                {{ datamesSed.documentType || '--' }}
                            </td>
                            <td class="text-center" aria-colindex="3" role="cell">{{ datamesSed.doc || '--' }}</td>
                            <td class="text-center" aria-colindex="4" role="cell">
                                {{ datamesSed.fecha_nacimiento || '--' }}
                            </td>
                            <td class="text-center" aria-colindex="5" role="cell">{{ edad || '--' }}</td>
                        </tr>

                        <!----><!---->
                    </tbody>
                    <!---->
                </table>
            </div>

            <div class="mt-3 table-responsive">
                <table role="table" aria-colcount="5" class="table b-table table-striped table-hover">
                    <!----><!---->
                    <thead role="rowgroup" class="table-header-nowrap">
                        <!---->
                        <tr role="row">
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="1">
                                Teléfono / celular
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="2">Dirección</th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="3">
                                Ciudad / municipio
                            </th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="4">Email</th>
                            <th class="text-center" role="columnheader" scope="col" aria-colindex="5">Pagaduría</th>
                        </tr>
                    </thead>
                    <tbody role="rowgroup">
                        <!---->
                        <tr role="row">
                            <td class="text-center" aria-colindex="1" role="cell">{{ datamesSed.telefono || '--' }}</td>
                            <td class="text-center" aria-colindex="2" role="cell">
                                {{ datamesSed.direccion_residencial || '--' }}
                            </td>
                            <td class="text-center" aria-colindex="3" role="cell">{{ datamesSed.ciudad || '--' }}</td>
                            <td class="text-center" aria-colindex="4" role="cell">
                                {{ datamesSed.correo_electronico || '--' }}
                            </td>
                            <td aria-colindex="5" role="cell">{{ datamesSed.pagaduria || '--' }}</td>
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
import { mapState } from 'vuex';

export default {
    name: 'DatamesData',
    computed: {
        ...mapState('datamesModule', ['datamesSed']),

        datamesSedArray() {
            return [this.datamesSed];
        },
        edad() {
            return this.calcularEdad(this.datamesSed.fecha_nacimiento);
        }
    },
    methods: {
        calcularEdad(fechaNacimiento) {
            var partes = fechaNacimiento.split('/');
            var fechaNacimientoFormatoCorrecto = partes[1] + '/' + partes[0] + '/' + partes[2];
            var hoy = new Date();
            var cumpleanos = new Date(fechaNacimientoFormatoCorrecto);
            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
            var m = hoy.getMonth() - cumpleanos.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                edad = edad - 1;
            }
            return edad;
        }
    }
};
</script>
<style scoped>
th {
    font-size: 14px;
}
.table th {
    text-align: center;
}

.table th,
.table td {
    width: 200px;
}
.table thead th {
    text-align: center;
    font-weight: 500;
}
</style>
