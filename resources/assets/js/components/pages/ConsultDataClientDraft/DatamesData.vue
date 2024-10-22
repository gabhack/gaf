<template>
    <div class="col-6" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title">Información personal</h3>
            <b-row class="mt-3">
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre y apellido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.nombre_usuario || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Tipo de documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.documentType || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Número de documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.doc || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha de nacimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.fecha_nacimiento || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ edad || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Teléfono / celular</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.telefono || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Dirección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.direccion_residencial || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6" class="pb-4">
                    <table>
                        <thead>
                            <tr>
                                <th>Ciudad / municipio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.ciudad || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6">
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.correo_electronico || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
                <b-col cols="12" sm="6">
                    <table>
                        <thead>
                            <tr>
                                <th>Pagaduria</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.pagaduria || '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </b-col>
            </b-row>
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
