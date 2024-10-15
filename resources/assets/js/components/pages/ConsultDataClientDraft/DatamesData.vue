<template>
    <div class="col-6" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title">Información personal</h3>
            <b-table
                :items="datamesSedArray"
                :fields="fields"
                class="mt-3"
                responsive
                thead-class="table-header-nowrap"
                
            ></b-table>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';

export default {
    name: 'DatamesData',
    data() {
        return {
            fields: [
                { key: 'nombre_usuario', label: 'Nombre y apellido', sortable: false },
                { key: 'documentType', label: 'Tipo de documento', sortable: false },
                { key: 'doc', label: 'N° de documento', sortable: false },
                { key: 'fecha_nacimiento', label: 'Fecha de nacimiento', sortable: false },
                { key: 'edad', label: 'Edad', sortable: false },
                { key: 'telefono', label: 'Teléfono', sortable: false },
                { key: 'direccion_residencial', label: 'Dirección', sortable: false },
                { key: 'ciudad', label: 'Ciudad/Municipio', sortable: false },
                { key: 'correo_electronico', label: 'Email', sortable: false },
                { key: 'pagaduria', label: 'Pagaduria', sortable: false },
            ],
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),

  
        datamesSedArray() {
            return [this.datamesSed];
        },
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
<style scoped lang="scss">
.center-align{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
::v-deep .table {
    & thead{
        background-color: #3a5659;
        white-space: nowrap;
        color: white;
        font-size: 14px;
        font-weight: 700;
        line-height: 18.23px;
        & tr th{
            padding: 12px 40px;
            text-align: center;
            min-height: 50px !important;
            & div{
                min-height: 50px;
                display: flex;
                align-items: center;
            }
        }
    }
    & tbody{
        background-color: #fff;
        font-size: 14px;
        font-weight: 400;
        line-height: 18.23px;
        & td{
            text-align: center;
        }
    }
}
</style>