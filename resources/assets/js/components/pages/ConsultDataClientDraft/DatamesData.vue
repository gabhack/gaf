<template>
    <div class="col-6" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading">
                <b>INFORMACIÓN PERSONAL</b>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div :class="label.colClass || 'col-6'" v-for="label in labels" :key="label.field" v-if="label.field !== 'null'" >
                        <b class="panel-label">{{ label.label }}:</b>
                        <div>
                            <p class="panel-value">
                                <template v-if="Object.keys(datamesSed).length > 0 && datamesSed[label.field]">
                                    <template v-if="label.currency">
                                        {{ datamesSed[label.field] | currency }}
                                    </template>
                                    <template v-if="label.field == 'documentType'"> CÉDULA DE CIUDADANÍA </template>
                                    <template v-else>
                                        {{ datamesSed[label.field] }}
                                    </template>
                                </template>
                                <template v-else> - </template>
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <p class="panel-label mb-0">
                            EDAD
                        </p>
                        <p class="panel-value">
                            {{ edad }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'DatamesData',
    data() {
        return {
            fecha: '30/10/1997',
            labels: [
                { label: 'TIPO DOCUMENTO', field: 'documentType' },
                { label: 'NÚMERO DOCUMENTO', field: 'doc' },
                { label: 'NOMBRE Y APELLIDO', field: 'nombre_usuario' },
                { label: 'FECHA DE NACIMIENTO', field: 'fecha_nacimiento' },
                { label: 'TELÉFONO / CELULAR', field: 'telefono' },
                { label: 'DIRECCIÓN', field: 'direccion_residencial' },
                { label: 'CIUDAD / MUNICIPIO', field: 'ciudad' },
                { label: 'EMAIL', field: 'correo_electronico' },
                { label: 'PAGADURIA', field: 'pagaduria' }
            ]
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        fechaNacimiento() {
            return this.datamesSed.fecha_nacimiento;
        },
        mostrar(){
            return this.fechaNacimiento;
        },
        edad() {
            return this.calcularEdad(this.fechaNacimiento);
            // return this.calcularEdad(this.fecha);
        }
    },
    methods: {
        calcularEdad(fechaNacimiento) {
            var partes = fechaNacimiento.split('/');
            // Crear una nueva cadena en el formato esperado (MM/DD/YYYY)
            var fechaNacimientoFormatoCorrecto = partes[1] + '/' + partes[0] + '/' + partes[2];
            var hoy = new Date();
            var cumpleanos = new Date(fechaNacimientoFormatoCorrecto);
            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
            var m = hoy.getMonth() - cumpleanos.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                edad = edad - 1;
            }
            console.log(fechaNacimiento, cumpleanos, hoy, hoy.getFullYear(), cumpleanos.getFullYear(), hoy.getMonth(), cumpleanos.getMonth());
            return edad;
            
        },
    
  }
};
</script>
