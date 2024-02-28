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
            labels: [
                { label: 'TIPO DOCUMENTO', field: 'documentType' },
                { label: 'NÚMERO DOCUMENTO', field: 'doc' },
                { label: 'NOMBRE Y APELLIDO', field: 'nombre_usuario' },
                { label: 'FECHA DE NACIMIENTO', field: 'fecha_nacimiento' },
                { label: 'EDAD', field: 'edad' },
                { label: 'TELÉFONO / CELULAR', field: 'telefono' },
                { label: 'DIRECCIÓN', field: 'direccion_residencial' },
                // { label: 'DEPARTAMENTO', field: 'departamento' },
                { label: 'CIUDAD / MUNICIPIO', field: 'ciudad' },
                { label: 'EMAIL', field: 'correo_electronico' },
                // { label: 'EPS', field: 'eps' },
                // { label: 'BANCO', field: 'banco' },
                // { label: 'SUCURSAL', field: 'sucursal' },
                { label: 'PAGADURIA', field: 'pagaduria' },
                // { label: 'RESOLUCION', field: 'numero_resolucion' },
                // { label: 'FECHA RESOLUCION', field: 'fecha_resolucion' },
            ]
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed'])
    }
};
</script>
