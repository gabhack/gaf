<template>
    <div class="col-4" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title" style="border: 5px #2c8c73 solid; background-color: #2c8c73; color: white; padding-left: 3px;">Información personal</h3>
                        <br><thead>
                            <tr>
                                <th style="color: #2c8c73;">Nombre y apellido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.nombre_usuario || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Tipo de documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.documentType || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Número de documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.doc || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Fecha de nacimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.fecha_nacimiento || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ edad || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Teléfono / celular</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.telefono || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Dirección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.direccion_residencial || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Ciudad / municipio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.ciudad || '--' }}</td>
                            </tr>
                        </tbody>
               
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.correo_electronico || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73;">Pagaduría</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.pagaduria || '--' }}</td>
                            </tr>
                        </tbody>
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
th{
    font-size: 14px;
}
</style>