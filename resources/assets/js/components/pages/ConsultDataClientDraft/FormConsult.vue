<template>
    <div class="panel mb-3 col-md-12">
        <div class="panel-heading">
            <b>REALIZAR CONSULTA</b>
        </div>
        <div class="panel-body">
            <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />
            <div class="row">
                <div class="col-6" style="display: grid; align-items: end">
                    <b class="panel-label">CÉDULA:</b>
                    <input required class="form-control text-center" type="number" v-model="dataclient.doc" />
                </div>
                <div class="col-6">
                    <b class="panel-label">NOMBRES Y APELLIDOS:</b>
                    <input required class="form-control text-center" type="text" v-model="dataclient.name" />
                </div>
                <div class="col-6">
                    <b class="panel-label">CUOTA DESEADA:</b>
                    <input
                        required
                        class="form-control text-center"
                        type="number"
                        v-model.number="dataclient.cuotadeseada"
                    />
                </div>
                <div class="col-6" style="display: grid; align-items: end">
                    <b class="panel-label">MONTO:</b>
                    <input required class="form-control text-center" type="text" v-model.number="dataclient.monto" />
                </div>
                <div class="col-6">
                    <b class="panel-label">PLAZO:</b>
                    <input required class="form-control text-center" type="text" />
                </div>

                <div class="col-6">
    <b class="panel-label">PAGADURIAS:</b>
    <b-form-select
        v-if="dataclient.pagadurias && dataclient.pagadurias.length > 0"
        v-model="dataclient.pagaduria"
        class="text-center"
        required
        @change="modalConfirmConsultPag"
    >
        <option :value="null" disabled hidden>Elija una pagaduría</option>
        <option 
            v-for="(pagaduria, index) in dataclient.pagadurias" 
            :key="index"
            :value="pagaduria"
        >
            {{ pagaduria.label }}
        </option>
    </b-form-select>

    <b-form-select v-else v-model="dataclient.pagaduria" class="text-center">
        <option :value="null" disabled>Ingresa una cédula y presiona consultar</option>
    </b-form-select>
</div>


                <div class="col-6 mt-4">
                    <b-button
                        type="button"
                        variant="black-pearl"
                        v-if="dataclient.doc && dataclient.name"
                        class="px-4"
                        @click="getAllPagadurias"
                    >
                        CONSULTAR PAGADURIAS
                    </b-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
    name: 'FormConsult',
    data() {
        return {
            dataclient: {
                doc: '',
                name: '',
                cuotadeseada: 0,
                monto: 0,
                pagaduria: null,
                pagadurias: null,
                pagaduriaKey: null,
                visado: null
            },
            isLoading: false
        };
    },
    mounted() {
        
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed', 'cuotadeseada']),
        ...mapState('pagaduriasModule', ['pagaduriasTypes'])
    },
    methods: {
        ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
        ...mapMutations('pagaduriasModule', [
            'setPagaduriaType',
            'setPagaduriaLabel',
            'setCouponsType',
            'setSelectedPeriod'
        ]),
        ...mapMutations('embargosModule', ['setEmbargosType']),
        ...mapMutations('descuentosModule', ['setDescuentosType']),
        selectedPagaduria() {

            if (this.dataclient.pagaduria) {
                const type = this.dataclient.pagadurias.find(p => p.value === this.dataclient.pagaduria.value);
                
                if (!type) {
                    console.error("No se encontró el tipo de pagaduría para:", this.dataclient.pagaduria);
                    return;
                }
        

        const pagaduria = type.value;

        this.dataclient.pagaduriaKey = type.label.toLowerCase();

        this.setPagaduriaType(type.label);
        this.setPagaduriaLabel(type.label);

        if (type.label.includes('datames')) {
            this.setCouponsType(`Coupons${type.label.slice(7)}`);
            this.setEmbargosType(`Embargos${type.label.slice(7)}`);
            this.setDescuentosType(`Descuentos${type.label.slice(7)}`);
        } else {
            this.setCouponsType(type.label);
            this.setEmbargosType(type.label);
            this.setDescuentosType(type.label);
        }

        this.setDatamesSed(pagaduria);
        console.log("Estado final de dataclient después de asignar pagaduría:", this.dataclient);
    }
}
,
        emitInfo() {
            this.getAllPagadurias();
        },
        async getAllPagadurias() {
            this.isLoading = true;
            this.dataclient.pagadurias = [];

            this.setDatamesSed(null);
            this.setPagaduriaType('');
            this.setSelectedPeriod('');

            try {
                const response = await axios.get(`/pagadurias/per-doc-express/${this.dataclient.doc}`);
                console.log("Response data:", response.data);

                if (response.data && typeof response.data === 'object' && Object.keys(response.data).length > 0) {
                    // Transformamos el objeto en un array de objetos con label y value
                    this.dataclient.pagadurias = Object.keys(response.data).map(key => {
                        return {
                            label: key,           // La clave es la etiqueta
                            value: response.data[key] // El valor asociado es el objeto completo
                        };
                    });

                    console.log("Final dataclient.pagadurias:", this.dataclient.pagadurias);
                    this.setCuotaDeseada(this.dataclient.cuotadeseada);
                } else {
                    toastr.info('No tenemos información de este documento en el momento');
                }

            } catch (error) {
                console.error('Error en la consulta de pagadurías:', error);
                toastr.error('Error al realizar la consulta de pagadurías.');
            } finally {
                this.isLoading = false;
            }
        }
,

        modalConfirmConsultPag(val) {
            this.$bvModal
                .msgBoxConfirm('Esta acción tiene un costo', {
                    title: '¿Está seguro que desea realizar la consulta?',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'success',
                    okTitle: 'Consultar',
                    cancelTitle: 'Cancelar',
                    cancelVariant: 'danger',
                    headerClass: 'p-2 border-bottom-0',
                    footerClass: 'p-2 border-top-0',
                    centered: true
                })
                .then(value => {
                    if (!value) return;
                    this.saveVisados(val).then(status => {
                        if (status != 200) return;
                        this.$emit('emitInfo', this.dataclient);
                        console.log(this.dataclient);
                    });
                });
        },
        async saveVisados() {
            const selectedPeriod = this.$store.state.pagaduriasModule.selectedPeriod;
            console.log("Selected Period:", selectedPeriod);
    try {
        this.isLoading = true;

        const demograficoResponse = await axios.get(`/demografico/${this.dataclient.doc}`);
        const demograficoData = demograficoResponse.data;

        if (!demograficoData.nombre_usuario) {
            toastr.error('No se encontró el nombre del usuario');
            this.isLoading = false;
            return;
        }
  
            this.selectedPagaduria();

        const data = {
            doc: this.dataclient.doc,
            nombre: demograficoData.nombre_usuario,
            pagaduria: this.dataclient.pagaduria.label,  
            entidad: this.dataclient.pagaduria.value.id,  
            tipo_consulta: 'Diamond',
            consultant_email: 'cch@gmail.com',  
            consultant_name: 'JUAN', 
        };

        console.log("Datos corregidos que se envían al controlador:", data);

        const response = await axios.post('/visados', data);

        this.dataclient.visado = response.data;
        return Promise.resolve(response.status);
    } catch (e) {
        console.error("Error al guardar el visado:", e);
        toastr.error('Error al guardar el visado');
        return Promise.reject(e);
    } finally {
        this.isLoading = false;
    }
}


    }
};
</script>