<template>
    <div class="w-100 px-0" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3
                class="heading-title w-100 d-flex align-items-center justify-content-start"
                :class="visible ? null : 'collapsed'"
                :aria-expanded="visible ? 'true' : 'false'"
                aria-controls="info-personal"
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

                Información Personal y Demográfica
            </h3>
            <b-collapse id="info-personal" v-model="visible" class="mt-2">
                <div class="mt-3 table-responsive">
                    <table role="table" aria-colcount="5" class="table b-table table-striped table-hover">
                        <!----><!---->
                        <thead role="rowgroup" class="table-header-nowrap">
                            <!---->
                            <tr role="row">
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="1">
                                    Nombre y Apellido
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="2">
                                    Tipo de Documento
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="3">
                                    N<sup>a</sup> Documento
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="4">
                                    Fecha de Nacimiento
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="5">Edad</th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="1">
                                    Teléfono / Celular
                                </th>
                            </tr>
                        </thead>
                        <tbody role="rowgroup">
                            <!---->
                            <tr role="row">
                                <td aria-colindex="1" role="cell">{{ datamesSed.nombre_usuario || '--' }}</td>
                                <td class="text-center" aria-colindex="2" role="cell">
                                    <!--  {{ datamesSed.documentType || '--' }}-->
                                    Cédula de Ciudadanía
                                </td>
                                <td class="text-center" aria-colindex="3" role="cell">
                                    {{ datamesSed.doc || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="4" role="cell">
                                    {{ datamesSed.fecha_nacimiento || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="5" role="cell">{{ edad || '--' }}</td>
                                <td class="text-center" aria-colindex="1" role="cell">
                                    {{ datamesSed.telefono || '--' }}
                                </td>
                            </tr>

                            <!----><!---->
                        </tbody>
                        <!---->
                    </table>
                </div>

                <div class="mt-3 table-responsive">
                    <table role="table" aria-colcount="7" class="table b-table table-striped table-hover">
                        <!----><!---->
                        <thead role="rowgroup" class="table-header-nowrap">
                            <!---->
                            <tr role="row">
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="2">Dirección</th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="3">
                                    Ciudad / Municipio
                                </th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="4">Email</th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="5">Pagaduría</th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="6">Profesión</th>
                                <th class="text-center" role="columnheader" scope="col" aria-colindex="7">
                                    Nivel Educativo
                                </th>
                            </tr>
                        </thead>
                        <tbody role="rowgroup">
                            <!---->
                            <tr role="row">
                                <td class="text-center" aria-colindex="2" role="cell">
                                    {{ datamesSed.direccion_residencial || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="3" role="cell">
                                    {{ datamesSed.ciudad || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="4" role="cell">
                                    {{ datamesSed.correo_electronico || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="5" role="cell">
                                    {{ datamesSed.pagaduria || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="6" role="cell">
                                    {{ datamesSed.profesion || '--' }}
                                </td>
                                <td class="text-center" aria-colindex="7" role="cell">
                                    {{ datamesSed.niveleducac || '--' }}
                                </td>
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
import { mapState } from 'vuex';

export default {
    name: 'DatamesData',
    data() {
        return {
            visible: true
        };
    },
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
    font-weight: 600;
    vertical-align: middle;
}
</style>
