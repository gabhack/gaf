<template>
    <div class="col-6" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading">
                <b>INFORMACIÓN PERSONAL</b>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            TIPO DE DOCUMENTO
                        </p>
                        <p class="panel-value" v-if="this.datamesSed.documentType == 'documentType'">
                            CÉDULA DE CIUDADANÍA
                        </p>
                        <p class="panel-value" v-else>
                            {{ this.datamesSed.documentType }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            NÚMERO DOCUMENTO:
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.doc }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            NOMBRE Y APELLIDO:
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.nombre_usuario }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            FECHA DE NACIMIENTO:
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.fecha_nacimiento }}
                        </p>
                    </div> 
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            EDAD
                        </p>
                        <p class="panel-value">
                            {{ edad }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            TELÉFONO / CELULAR
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.telefono }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            TELÉFONO / CELULAR
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.telefono }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            DIRECCIÓN
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.direccion_residencial }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            CIUDAD / MUNICIPIO
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.ciudad }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            EMAIL:
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.correo_electronico }}
                        </p>
                    </div>
                    <div class="col-6 center-align">
                        <p class="panel-label mb-0">
                            PAGADURIA:
                        </p>
                        <p class="panel-value">
                            {{ this.datamesSed.pagaduria }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scope>
    .center-align{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>
<script>
import { mapState } from 'vuex';

export default {
    name: 'DatamesData',
    data() {
        return {
            fecha: '30/10/1997'
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        fechaNacimiento() {
            return this.datamesSed.fecha_nacimiento;
        },
        edad() {
            return this.calcularEdad(this.fechaNacimiento);
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
            
        },
    
  }
};
</script>
