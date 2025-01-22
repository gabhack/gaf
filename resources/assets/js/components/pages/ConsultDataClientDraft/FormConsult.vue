<template>
    <div class="panel mb-3 col-md-12">
        <!-- <div class="panel-heading">
            <b>REALIZAR CONSULTA</b>
        </div> -->
        <b-row>
            <b-col cols="6">
                <h3 class="heading-title">Realizar consulta</h3>
            </b-col>
            <b-col cols="6" class="d-flex justify-content-end align-items-center">
                <CustomButton :class="'white'" @click="pdfEmit"> Descargar PDF <Download class="ml-2" /> </CustomButton>
            </b-col>
        </b-row>
        <div class="panel-body px-0">
            <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />
            <b-row>
                <b-col cols="12" md="4" class="mb-md-4 mb-2">
                    <b class="panel-label mb-2"><span class="text-danger"> *</span> Cédula</b>
                    <input
                        required
                        class="form-control2"
                        :class="{ errorValid: activeId == 'dataclientDoc' }"
                        @change="activeId = ''"
                        placeholder="N° de documento"
                        type="number"
                        v-model="dataclient.doc"
                        ref="dataclientDoc"
                    />
                </b-col>
                <b-col cols="12" md="4" class="mb-2">
                    <b class="panel-label mb-2"><span class="text-danger"> *</span> Nombres y apellidos</b>
                    <input
                        required
                        class="form-control2"
                        placeholder="Ingrese nombre completo"
                        type="text"
                        v-model="dataclient.name"
                        ref="dataclientName"
                        :class="{ errorValid: activeId == 'dataclientName' }"
                        @change="activeId = ''"
                    />
                </b-col>
                <b-col cols="12" md="4" class="mb-2">
                    <b class="panel-label mb-2"><span class="text-danger"> *</span> Monto</b>

                    <InputCurrency
                        v-model="dataclient.monto"
                        ref="dataclientMonto"
                        @change="activeId = ''"
                        label=""
                        id=""
                        name=""
                        placeholder="Ingrese un monto"
                        rules="required"
                        :validateClass="activeId == 'dataclientMonto' ? true : false"
                    />

                    <!-- <b-input-group size="md" prepend="$">
                        <input
                            type="text"
                            class="style-form-control col-md-8"
                            placeholder="Ingrese un monto"
                            ref="dataclientMonto"
                            v-model="dataclient.monto"
                            :class="{ errorValid: activeId == 'dataclientMonto' }"
                            @change="activeId = ''"
                        />
                    </b-input-group> -->
                </b-col>
                <b-col cols="12" md="4" class="mb-2 mb-md-4">
                    <b class="panel-label mb-2"><span class="text-danger"> *</span> Cuota deseada</b>

                    <InputCurrency
                        v-model="dataclient.cuotadeseada"
                        ref="dataclientCuotaDeseada"
                        @change="activeId = ''"
                        label=""
                        id=""
                        name=""
                        placeholder="Cantidad de cuotas"
                        rules="required"
                        validateClass="errorValid"
                    />
                    <!--<b-input-group size="md" prepend="$">
                        <input
                            type="text"
                            class="style-form-control col-md-8"
                            placeholder="Cantidad de cuotas"
                            v-model="dataclient.cuotadeseada"
                            ref="dataclientCuotaDeseada"
                            :class="{ errorValid: activeId == 'dataclientCuotaDeseada' }"
                            @change="activeId = ''"
                        />
                    </b-input-group>-->
                </b-col>
                <b-col cols="12" md="4" class="mb-3 mb-md-0">
                    <b class="panel-label mb-2"><span class="text-danger"> *</span> Plazo </b>
                    <input
                        required
                        class="form-control2"
                        type="text"
                        v-model.number="dataclient.plazo"
                        placeholder="Ingrese el plazo"
                        ref="dataclientPlazo"
                        :class="{ errorValid: activeId == 'dataclientPlazo' }"
                        @change="activeId = ''"
                    />
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="12" md="4" v-if="!dataclient.pagadurias && !flag">
                    <CustomButton text="Consultar Pagadurias" @click="validationForm" />
                </b-col>

                <b-col cols="12" md="4" v-else>
                    <div v-if="!flag">
                        <h3 style="padding-bottom: 24px" class="heading-title">Consultar pagadurias</h3>
                        <b class="panel-label mb-2">Pagadurias</b>
                        <b-form-select
                            v-if="dataclient.pagadurias"
                            v-model="dataclient.pagaduria"
                            required
                            @change="modalConfirmConsultPag"
                        >
                            <option :value="null" disabled hidden>Elija una pagaduria</option>
                            <option
                                v-for="type in pagaduriasTypes"
                                v-if="dataclient.pagadurias[type.key]"
                                :value="type.value"
                                :key="type.key"
                            >
                                {{ type.label }}
                            </option>
                        </b-form-select>

                        <b-form-select v-else v-model="dataclient.pagaduria" class="text-center">
                            <option :value="null" disabled>Ingresa una cedula y presiona consultar</option>
                        </b-form-select>
                    </div>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<!-- <b-button
        type="button"
        variant="black-pearl"
        v-if="dataclient.doc && dataclient.name"
        class="px-4"
        @click="getAllPagadurias"
    >
    CONSULTAR PAGADURIAS
</b-button> -->
<script>
import { mapState, mapMutations } from 'vuex';
import CustomButton from '../../customComponents/CustomButton.vue';
import Download from '../../icons/Download.vue';
import InputCurrency from '../../customComponents/InputCurrency.vue';

export default {
    name: 'FormConsult',
    components: {
        CustomButton,
        Download,
        InputCurrency
    },
    data() {
        return {
            flag: false,
            dataclient: {
                doc: '',
                name: '',
                cuotadeseada: 0,
                monto: 0,
                plazo: null,
                pagaduria: null,
                pagadurias: null,
                pagaduriaKey: null,
                visado: null
            },
            activeId: null,
            isLoading: false
        };
    },
    mounted() {
        console.log(this.pagaduriasTypes);
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
        pdfEmit() {
            this.$emit('downloadPdf');
        },
        selectedPagaduria() {
            this.setPagaduriaType(this.dataclient.pagaduria);

            if (this.dataclient.pagaduria) {
                const type = this.pagaduriasTypes.find(type => type.value === this.dataclient.pagaduria);

                const pagaduria = this.dataclient.pagadurias[type.key];
                this.dataclient.pagaduriaKey = type.key.slice(7).toLowerCase();
                pagaduria.documentType = 'documentType';
                this.dataclient.cargo = pagaduria.cargo;

                const pagaduriaLabel = type.label;
                this.setPagaduriaLabel(pagaduriaLabel);

                this.setCouponsType(type.key.includes('datames') ? `Coupons${type.key.slice(7)}` : type.key);
                this.setEmbargosType(type.key.includes('datames') ? `Embargos${type.key.slice(7)}` : type.key);
                // prettier-ignore
                this.setDescuentosType(type.key.includes('datames') ? `Descuentos${type.key.slice(7)}` : type.key);

                this.setDatamesSed(pagaduria);
            }
        },
        emitInfo() {
            this.getAllPagadurias();
        },
        async validationForm() {
            if (!this.dataclient.doc) {
                this.$bvToast.toast(`Debes llenar el campo de la cédula, es obligatorio`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                }),
                    this.$refs.dataclientDoc.focus();
                this.activeId = 'dataclientDoc';
            } else if (!this.dataclient.name) {
                this.$bvToast.toast(`Debes llenar el campo del nombre, es obligatorio`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                }),
                    this.$refs.dataclientName.focus();
                this.activeId = 'dataclientName';
            } else if (!this.dataclient.monto) {
                this.$bvToast.toast(`Debes colocar el campo del monto, es obligatorio`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                }),
                    this.$refs.dataclientMonto.focus();
                this.activeId = 'dataclientMonto';
            } else if (!this.dataclient.cuotadeseada) {
                this.$bvToast.toast(`Debes colocar el campo de la cuota deseada es obligatorio`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                }),
                    this.$refs.dataclientCuotaDeseada.focus();
                this.activeId = 'dataclientCuotaDeseada';
            } else if (!this.dataclient.plazo) {
                this.$bvToast.toast(`Debes colocar el campo del plazo deseada es obligatorio`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                }),
                    this.$refs.dataclientPlazo.focus();
                this.activeId = 'dataclientPlazo';
            } else {
                this.getAllPagadurias();
            }
        },
        async getAllPagadurias() {
            if (this.dataclient.doc && this.dataclient.name) {
                this.isLoading = true;
                this.dataclient.pagadurias = null;

                this.setDatamesSed(null);
                this.setPagaduriaType('');
                this.setSelectedPeriod('');

                const response = await axios.get(`/pagadurias/per-doc/${this.dataclient.doc}`);
                console.log(response.data);
                if (Object.keys(response.data).length > 0) {
                    this.dataclient.pagadurias = response.data;
                    this.setCuotaDeseada(this.dataclient.cuotadeseada);
                } else {
                    toastr.info('No tenemos información de este documento en el momento');
                }

                this.isLoading = false;
                console.log(this.pagaduriasTypes);
                return Promise.resolve(response.data);
            } else {
                this.$bvToast.toast(`LLenar los campos obligatorios`, {
                    title: '¡Error!',
                    autoHideDelay: 5000,
                    solid: true,
                    variant: 'danger'
                });
            }
        },
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
                        this.dataclient.pagadurias = null;
                        this.flag = true;
                    });
                });
        },
        async saveVisados() {
            try {
                this.isLoading = true;

                // Llamada para obtener datos demográficos
                const demograficoResponse = await axios.get(`/demografico/${this.dataclient.doc}`);
                const demograficoData = demograficoResponse.data;

                // Verificar si se obtuvo el nombre
                if (!demograficoData.nombre_usuario) {
                    toastr.error('No se encontró el nombre del usuario');
                    this.isLoading = false;
                    return;
                }

                // Llamada a selectedPagaduria
                this.selectedPagaduria();

                const data = {
                    pagaduria: this.dataclient.pagaduria,
                    nombre: demograficoData.nombre_usuario,
                    doc: this.dataclient.doc,
                    plazo: this.dataclient.plazo
                };

                const response = await axios.post('/visados', data);

                this.dataclient.visado = response.data;

                return Promise.resolve(response.status);
            } catch (e) {
                toastr.error('Error al guardar el visado');
                return Promise.reject(e);
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>

<style scoped lang="scss">
.panel-label {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
}
</style>
