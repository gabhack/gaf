<template>
    <div class="container mt-5" v-if="!loadingOptions">
        <h3 class="heading-title pb-2">Parámetros Comparativa</h3>

        <!-- Select for Pensionados or Docentes -->
        <div class="form-group">
            <label for="tipo">Tipo:</label>
            <select v-model="form.tipo" class="form-control2" @change="handleTipoChange">
                <option value="">Seleccione Tipo</option>
                <option value="pensionados">Pensionados</option>
                <option value="docentes">Docentes</option>
            </select>
        </div>

        <!-- Step 1: Select Genders -->
        <div v-if="form.tipo" class="form-group">
            <label>Seleccione Género(s):</label>
            <div>
                <input type="checkbox" id="masculino" value="M" v-model="form.generos" @change="handleGenderSelection">
                <label for="masculino">Masculino</label>
            </div>
            <div>
                <input type="checkbox" id="femenino" value="F" v-model="form.generos" @change="handleGenderSelection">
                <label for="femenino">Femenino</label>
            </div>
        </div>

        <!-- Step 2: Age, Type of Contract, and Job Title per Gender -->
        <div v-for="gender in form.generos" :key="gender">
            <h4 class="heading-title pb-2">Género: {{ gender === 'M' ? 'Masculino' : 'Femenino' }}</h4>

            <div class="form-group">
                <label :for="'edad-' + gender">Edad:</label>
                <input type="number" class="form-control2" v-model="form['edad_' + gender]" :id="'edad-' + gender" :disabled="!form['edadEnabled_' + gender]">
            </div>

            <div class="form-group">
                <label :for="'tipo_contrato-' + gender">Tipo de Contrato:</label>
                <select v-model="form['tipo_contrato_' + gender]" :id="'tipo_contrato-' + gender" class="form-control2">
                    <option value="">Seleccione Tipo de Contrato</option>
                    <option v-for="contrato in tiposContrato" :key="contrato" :value="contrato">{{ contrato }}</option>
                </select>
            </div>

            <div class="form-group">
                <label :for="'cargo-' + gender">Cargo:</label>
                <select v-model="form['cargo_' + gender]" :id="'cargo-' + gender" class="form-control2" @change="handleCargoChange(gender)">
                    <option value="">Seleccione Cargo</option>
                    <option v-for="cargo in cargos" :key="cargo" :value="cargo">{{ cargo }}</option>
                </select>
            </div>

            <!-- Extra Fields for Specific Job Titles -->
            <div v-if="compareString(form['cargo_' + gender], 'celador')" class="form-group">
                <label>¿Incluye horas extras?</label>
                <input type="checkbox" v-model="form['horas_extras_' + gender]">
                <div v-if="form['horas_extras_' + gender]" class="form-group">
                    <label>Porcentaje:</label>
                    <input type="number" v-model="form['porcentaje_' + gender]" class="form-control2" min="0" max="100">
                </div>
            </div>

            <div v-if="compareString(form['cargo_' + gender], 'coordinador')" class="form-group">
                <label>¿Incluye asignación AA?</label>
                <input type="checkbox" v-model="form['asignacion_aa_' + gender]">
                <div v-if="form['asignacion_aa_' + gender]" class="form-group">
                    <label>Porcentaje:</label>
                    <input type="number" v-model="form['porcentaje_' + gender]" class="form-control2" min="0" max="100">
                </div>
            </div>

            <div v-if="compareString(form['cargo_' + gender], 'rector')" class="form-group">
                <label>¿Incluye asignación AAA?</label>
                <input type="checkbox" v-model="form['asignacion_aaa_' + gender]">
                <div v-if="form['asignacion_aaa_' + gender]" class="form-group">
                    <label>Porcentaje:</label>
                    <input type="number" v-model="form['porcentaje_' + gender]" class="form-control2" min="0" max="100">
                </div>
            </div>
        </div>

        <!-- Input for Coupon Code -->
        <div v-if="form.generos.length > 0" class="form-group">
            <label for="codigo_cupon">Código de Cupón:</label>
            <input type="text" v-model="form.codigo_cupon" id="codigo_cupon" class="form-control2" required>
        </div>

        <CustomButton v-if="form.tipo && form.generos.length > 0" text="Guardar" @click="submitForm" />

    </div>

    <!-- Loading Spinner for Options -->
    <div v-else class="loading-spinner text-center">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Cargando opciones...
    </div>
</template>

<script>
import CustomButton from '../../components/customComponents/CustomButton.vue'
export default {
    components:{
        CustomButton
    },
    data() {
        return {
            form: {
                tipo: '',
                generos: [],
                edad_M: '',
                edadEnabled_M: false,
                tipo_contrato_M: '',
                cargo_M: '',
                horas_extras_M: false,
                asignacion_aa_M: false,
                asignacion_aaa_M: false,
                porcentaje_masculino: 0,
                edad_F: '',
                edadEnabled_F: false,
                tipo_contrato_F: '',
                cargo_F: '',
                horas_extras_F: false,
                asignacion_aa_F: false,
                asignacion_aaa_F: false,
                porcentaje_femenino: 0,
                codigo_cupon: ''
            },
            cargos: [],
            tiposContrato: [],
            loading: false,
            loadingOptions: true
        };
    },
    methods: {
        loadOptions() {
            this.loadingOptions = true;
            axios.get('/parametros-comparativa/opciones')
                .then(response => {
                    this.cargos = response.data.cargos;
                    this.tiposContrato = response.data.tiposContrato;
                })
                .catch(error => {
                    console.error('Error loading options:', error);
                })
                .finally(() => {
                    this.loadingOptions = false;
                });
        },
        handleTipoChange() {
            // Reset form fields on type change
            this.form.generos = [];
            this.resetForm();
        },
        handleGenderSelection() {
            this.form.generos.forEach(gender => {
                this.form['edadEnabled_' + gender] = true; // Enable age input for selected gender
            });
        },
        handleCargoChange(gender) {
            // Reset specific extra fields when cargo changes
            this.form['horas_extras_' + gender] = false;
            this.form['asignacion_aa_' + gender] = false;
            this.form['asignacion_aaa_' + gender] = false;
            this.form['porcentaje_' + gender] = 0;
        },
        compareString(str1, str2) {
            // Normalize the strings to compare without case or accent sensitivity
            return (
                str1 &&
                str2 &&
                str1
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "")
                    .toLowerCase() ===
                    str2
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "")
                    .toLowerCase()
            );
        },
        resetForm() {
            this.form.edad_M = '';
            this.form.edadEnabled_M = false;
            this.form.tipo_contrato_M = '';
            this.form.cargo_M = '';
            this.form.horas_extras_M = false;
            this.form.asignacion_aa_M = false;
            this.form.asignacion_aaa_M = false;
            this.form.porcentaje_masculino = 0;
            this.form.edad_F = '';
            this.form.edadEnabled_F = false;
            this.form.tipo_contrato_F = '';
            this.form.cargo_F = '';
            this.form.horas_extras_F = false;
            this.form.asignacion_aa_F = false;
            this.form.asignacion_aaa_F = false;
            this.form.porcentaje_femenino = 0;
            this.form.codigo_cupon = '';
        },
        submitForm() {
            this.loading = true;
            axios.post('/parametros-comparativa/store', this.form)
                .then(response => {
                    if (response.data.success) {
                        alert('Parámetros guardados exitosamente.');
                    }
                })
                .catch(error => {
                    console.error('Error saving parameters:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    },
    mounted() {
        this.loadOptions(); // Load options when the component is mounted
    }
}
</script>

<style scoped>
.loading-spinner {
    padding-top: 20px;
    font-size: 18px;
}
</style>
