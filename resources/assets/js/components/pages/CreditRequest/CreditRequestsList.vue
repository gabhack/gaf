<template>
  <div class="table-container">
      <!-- Loading overlay -->
      <loading :active.sync="isLoading" :is-full-page="true" color="#0CEDB0" :can-cancel="false" />
  
      <!-- Tabla principal con la lista de créditos -->
      <div v-if="showTable" class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cédula</th>
              <th>Nombre</th>
              <th>Tipo Cliente</th>
              <th>Pagaduría</th>
              <th>Cuota</th>
              <th>Monto</th>
              <th>Tasa (Mensual)</th>
              <th>Plazo</th>
              <th>Estado</th>
              <!-- Nueva columna: Tipo de Crédito -->
              <th>Tipo Crédito</th>
              <th>Documentos</th>
              <!-- Solo se muestra la columna "Acción" si es ADMIN_SISTEMA -->
              <th v-if="isAdminSistema">Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="credit in credits" :key="credit.id">
              <td>{{ credit.id }}</td>
              <td>{{ credit.doc }}</td>
              <td>{{ credit.name }}</td>
              <td>{{ credit.client_type }}</td>
              <!-- Pagaduría -->
              <td>{{ getPagaduriaNameById(credit.pagaduria_id) }}</td>
              <td>{{ formatCurrency(credit.cuota) }}</td>
              <td>{{ formatCurrency(credit.monto) }}</td>
              <td>{{ formatPercentage(credit.tasa) }}</td>
              <td>{{ credit.plazo }}</td>
              <td>{{ credit.status }}</td>
              <!-- Tipo de Crédito -->
              <td>{{ credit.tipo_credito }}</td>
  
              <!-- Documentos -->
              <td>
                <span v-if="credit.documents && credit.documents.length">
                  <div v-for="(doc, docIndex) in credit.documents" :key="doc.id">
                    <a
                      :href="getDownloadUrl(doc.file_path)"
                      target="_blank"
                    >
                      doc-{{ docIndex + 1 }}
                    </a>
                  </div>
                </span>
                <span v-else>
                  No hay documentos
                </span>
              </td>
  
              <!-- Columna de botones si user.role_id === 1 (ADMIN_SISTEMA) -->
              <td v-if="isAdminSistema">
                <!-- Visar Manualmente (abre modal) -->
                <button
                  class="btn-credit ml-2"
                  @click="openManualVisadoModal(credit)"
                >
                  Visar Manualmente
                </button>
  
                <!-- Ver Carteras (abre modal) -->
                <button
                  class="btn-credit ml-2"
                  @click="showCarteras(credit)"
                >
                  <i class="fas fa-eye"></i>
                </button>
  
                <!-- Visar (emitClientData) -->
                <button
                  class="btn-credit ml-2"
                  @click="modalConfirmConsultPag(credit)"
                >
                  Visar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Modal para visado manual (store/update) -->
      <b-modal
        id="modal-visar-manualmente"
        v-model="showVisadoManualModal"
        title="Visar Manualmente"
        hide-footer
        centered
      >
        <div v-if="visadoForm">
          <form @submit.prevent="submitVisadoManual">
            <!-- Documento -->
            <div class="form-group">
              <label for="doc">Documento</label>
              <input
                type="text"
                class="form-control"
                id="doc"
                v-model="visadoForm.doc"
              />
            </div>
  
            <!-- Nombre -->
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input
                type="text"
                class="form-control"
                id="nombre"
                v-model="visadoForm.nombre"
              />
            </div>
  
            <!-- Pagaduría -->
            <div class="form-group">
              <label for="pagaduria">Pagaduría</label>
              <input
                type="text"
                class="form-control"
                id="pagaduria"
                v-model="visadoForm.pagaduria"
              />
            </div>
  
            <!-- Plazo -->
            <div class="form-group">
              <label for="plazo">Plazo</label>
              <input
                type="number"
                class="form-control"
                id="plazo"
                v-model="visadoForm.plazo"
              />
            </div>
  
            <!-- Monto -->
            <div class="form-group">
              <label for="monto">Monto</label>
              <input
                type="number"
                class="form-control"
                id="monto"
                v-model="visadoForm.monto"
              />
            </div>
  
            <!-- Cuota Crédito -->
            <div class="form-group">
              <label for="cuotacredito">Cuota Crédito</label>
              <input
                type="number"
                class="form-control"
                id="cuotacredito"
                v-model="visadoForm.cuotacredito"
              />
            </div>
  
            <!-- Estado (factible / no factible) -->
            <div class="form-group">
              <label for="estado">Estado</label>
              <select class="form-control" id="estado" v-model="visadoForm.estado">
                <option value="factible">factible</option>
                <option value="no factible">no factible</option>
              </select>
            </div>
  
            <!-- Causal (select con motivos) -->
            <div class="form-group">
              <label for="causal">Causal</label>
              <select
                  class="form-control"
                  id="causal"
                  v-model="visadoForm.causal"
                >
                  <option
                    v-for="c in causalesOptions"
                    :key="c.value"
                    :value="c.value"
                  >
                    {{ c.text }}
                  </option>
                </select>
  
            </div>
  
            <!-- Observación (para la tabla visados) -->
              <div class="form-group">
                <label for="observacion">Observación</label>
                <textarea
                  class="form-control"
                  id="observacion"
                  rows="2"
                  v-model="visadoForm.observacion"
                ></textarea>
              </div>
  
           
            <div class="text-center mt-3">
              <button type="submit" class="btn-credit">Guardar</button>
              <button
                type="button"
                class="btn-credit ml-2"
                @click="showVisadoManualModal = false"
              >
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </b-modal>
  
      <!-- Modal para ver Carteras -->
      <!-- Modal para ver Carteras -->
  <b-modal
    id="modal-carteras"
    v-model="showCarterasModal"
    title="Carteras asociadas"
    hide-footer
    centered
  >
    <div v-if="selectedCarteras && selectedCarteras.length">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Tipo de Cartera</th>
              <th>Nombre de la Entidad</th>
              <th>Valor Cuota</th>
              <th>Saldo</th>
              <!-- NUEVO -->
              <th>Opera X Desprendible</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(car, idx) in selectedCarteras" :key="idx">
              <td>{{ car.tipo_cartera }}</td>
              <td>{{ car.nombre_entidad }}</td>
              <td>{{ formatCurrency(car.valor_cuota) }}</td>
              <td>{{ formatCurrency(car.saldo) }}</td>
              <!-- NUEVO: muestra si es true/false -->
              <td>
                <span v-if="car.opera_x_desprendible">Sí</span>
                <span v-else>No</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-else>
      <p>No hay carteras registradas</p>
    </div>
  </b-modal>
  
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
  import {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormFile,
    BFormTextarea,
    BFormCheckbox,
    BButton,
    BTable,
    BRow,
    BCol
  } from "bootstrap-vue";

  const allPagaduriasMap = {
  // Pensionados
  200: "COLPENSIONES",
  201: "FOPEP",
  297: "FIDUPREVISORA",
  296: "CASUR",

  // Docentes (ordenados alfabéticamente, p. ej.)
  1: "sed amazonas",
  130: "sed antioquia",
  109: "sed arauca",
  121: "sed atlantico",
  293: "sed bolivar",
  110: "sed boyaca",
  139: "sed caldas",
  140: "sed caqueta",
  104: "sed casanare",
  177: "sed cauca",
  11: "sed cesar",
  12: "sed choco",
  182: "sed cordoba",
  163: "sed cundinamarca",
  192: "sed guajira",
  173: "sed guaviare",
  178: "sed huila",
  145: "sed magdalena",
  113: "sed meta",
  143: "sed narino",
  154: "sed norte de santander",
  184: "sed putumayo",
  166: "sed quindio",
  114: "sed risaralda",
  26: "sed santander",
  175: "sed sucre",
  122: "sed tolima",
  165: "sed valle",
  132: "sed vaupes",
  32: "sed vichada",
  27: "sem sincelejo",
  34: "sem armenia",
  160: "sem barrancabermeja",
  106: "sem barranquilla",
  111: "sem bello",
  39: "sem bucaramanga",
  40: "sem buenaventura",
  157: "sem buga",
  191: "sem cali",
  189: "sem cartagena",
  136: "sem cartago",
  45: "sem chia",
  103: "sem cienaga",
  47: "sem cucuta",
  112: "sem dosquebradas",
  49: "sem duitama",
  115: "sem envigado",
  168: "sem estrella",
  164: "sem facatativa",
  55: "sem florencia",
  170: "sem floridablanca",
  117: "sem funza",
  151: "sem fusagasuga",
  179: "sem girardot",
  287: "sem giron",
  116: "sem guainia",
  147: "sem ibague",
  134: "sem ipiales",
  135: "sem itagui",
  146: "sem jamundi",
  67: "sem lorica",
  133: "sem magangue",
  69: "sem maicao",
  161: "sem malambo",
  174: "sem manizales",
  180: "sem medellin",
  176: "sem monteria",
  153: "sem mosquera",
  105: "sem neiva",
  152: "sem palmira",
  125: "sem pasto",
  78: "sem pereira",
  79: "sem piedecuesta",
  138: "sem pitalito",
  159: "sem popayan",
  162: "sem quibdo",
  150: "sem riohacha",
  129: "sem rionegro",
  108: "sem sabaneta",
  142: "sem sahagun",
  158: "sem san andres",
  126: "sem santa marta",
  119: "sem soacha",
  172: "sem sogamoso",
  123: "sem soledad",
  120: "sem tulua",
  93: "sem tumaco",
  141: "sem tunja",
  137: "sem turbo",
  144: "sem uribia",
  171: "sem valledupar",
  124: "sem villavicencio",
  289: "sem yopal",
  169: "sem yumbo",
  156: "sem zipaquira",

  
};

  export default {
      props: ['user'],
    
      name: "CreditRequestForm",
    components: {
      BRow,
      BCol,
      BFormGroup,
      BFormInput,
      BFormSelect,
      BFormFile,
      BFormTextarea,
      BFormCheckbox,
      BButton,
      BTable,
      CustomButton,
          Download,
          InputCurrency
    },
      data() {
          return {
              form: {
          doc: "",
          name: "",
          client_type: "",
          pagaduria_id: "",
          tipo_credito: "",
          cuota: "",
          monto: "",
          tasa: "",
          plazo: 1,
          observacion: "", 
     
        },
        
        carteras: [],
        credits: [],
        showTable: true,
      isLoading: false,

      showVisadoManualModal: false,
      visadoForm: null,

      showCarterasModal: false,
      selectedCarteras: [],
        documentos: [],   
        clientTypeOptions: [
          { value: "", text: "Seleccione" },
          { value: "docente", text: "Docente" },
          { value: "pensionado", text: "Pensionado" }
        ],
        docentePagaduriasMap: {
    "sed amazonas": 1,
    "sed antioquia": 130,
    "sem armenia": 34,
    "sed arauca": 109,
    "sed atlantico": 121,
    "sem barrancabermeja": 160,
    "sem barranquilla": 106,
    "sem bello": 111,
    "sed bolivar": 293,
    "sed boyaca": 110,
    "sem bucaramanga": 39,
    "sem buenaventura": 40,
    "sem buga": 157,
    "sed caldas": 139,
    "sem cali": 191,
    "sed caqueta": 140,
    "sed casanare": 104,
    "sem cartagena": 189,
    "sem cartago": 136,
    "sed cauca": 177,
    "sem chia": 45,
    "sem cienaga": 103,
    "sed cesar": 11,
    "sem cucuta": 47,
    "sed choco": 12,
    "sed cordoba": 182,
    "sed cundinamarca": 163,
    "sem dosquebradas": 112,
    "sem duitama": 49,
    "sem envigado": 115,
    "sem estrella": 168,
    "sem facatativa": 164,
    "sem florencia": 55,
    "sem floridablanca": 170,
    "sem funza": 117,
    "sem fusagasuga": 151,
    "sem girardot": 179,
    "sem giron": 287,
    "sem guainia": 116,
    "sed guajira": 192,
    "sed guaviare": 173,
    "sed huila": 178,
    "sem ibague": 147,
    "sem ipiales": 134,
    "sem itagui": 135,
    "sem jamundi": 146,
    "sem lorica": 67,
    "sed magdalena": 145,
    "sem magangue": 133,
    "sem maicao": 69,
    "sem malambo": 161,
    "sed meta": 113,
    "sem manizales": 174,
    "sem medellin": 180,
    "sem monteria": 176,
    "sem mosquera": 153,
    "sem neiva": 105,
    "sed narino": 143,
    "sed norte de santander": 154,
    "sem palmira": 152,
    "sem pasto": 125,
    "sem pereira": 78,
    "sem piedecuesta": 79,
    "sem pitalito": 138,
    "sed putumayo": 184,
    "sed quindio": 166,
    "sem quibdo": 162,
    "sem riohacha": 150,
    "sem rionegro": 129,
    "sed risaralda": 114,
    "sed santander": 26,
    "sem sabaneta": 108,
    "sem sahagun": 142,
    "sem san andres": 158,
    "sem santa marta": 126,
    "sed sucre": 175,
    "sem soacha": 119,
    "sem sogamoso": 172,
    "sem soledad": 123,
    "sed tolima": 122,
    "sem tulua": 120,
    "sem tunja": 141,
    "sem turbo": 137,
    "sem tumaco": 93,
    "sem uribia": 144,
    "sed valle": 165,
    "sem valledupar": 171,
    "sed vaupes": 132,
    "sed vichada": 32,
    "sem villavicencio": 124,
    "sem sincelejo": 27,
    "sem yopal": 289,
    "sem yumbo": 169,
    "sem zipaquira": 156,
    "casur": 296,
    "fiduprevisora": 297
  },  
        tipoCreditoOptions: [
          { value: "", text: "Seleccione" },
          { value: "Libre Inversión", text: "Libre Inversión" },
          { value: "Compra de Cartera", text: "Compra de Cartera" },
          { value: "Refinanciación", text: "Refinanciación" }
        ],
        tipoCarteraOptions: [
          { value: "Banco", text: "Banco" },
          { value: "Cooperativa", text: "Cooperativa" },
          { value: "CFC", text: "CFC" },
          { value: "Financiera", text: "Financiera" },
          { value: "Embargo", text: "Embargo" }
        ],
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
          this.fetchCredits()
      },
      computed: {
        isAdminSistema() {
      return this.user.role_id === 1
    },
    causalesOptions() {
    if (this.visadoForm.estado === 'factible') {
      // Solo "Sin causal"
      return [
        { value: 'Sin causal', text: 'Sin causal' },
      ];
    } else {
      // Cuando es 'no factible', NO mostrar 'Sin causal' sino los demás
      return [
        { value: 'Presenta obligaciones en mora', text: 'Presenta obligaciones en mora' },
        { value: 'Negado por cupo', text: 'Negado por cupo' },
        { value: 'Cliente en proceso de retiro', text: 'Cliente en proceso de retiro' },
        { value: 'No factible por pagaduria', text: 'No factible por pagaduria' },
        { value: 'Ingresa descuento nuevo', text: 'Ingresa descuento nuevo' },
      ];
    }
  },
          userRole() {
              return this.user.role.name;
          },
          ...mapState('datamesModule', ['datamesSed', 'cuotadeseada']),
          ...mapState('pagaduriasModule', ['pagaduriasTypes']),
          showDocenteOptions() {
        return this.form.client_type === "docente";
      },
      showPensionadoOptions() {
        return this.form.client_type === "pensionado";
      },
      docentePagaduriasOptions() {
        let opts = [{ value: "", text: "Seleccione" }];
        for (const [key, val] of Object.entries(this.docentePagaduriasMap)) {
          let cleanText = key
            .replace(/(sed|sem)/gi, "")
            .replace(/\s+/g, "")
            .toUpperCase();
          opts.push({ value: val, text: cleanText });
        }
        return opts;
      }
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
  async fetchCredits() {
    try {
      this.isLoading = true
      const response = await axios.get('/credit-requests/all')
      this.credits = response.data
    } catch (error) {
      console.error('Error al obtener lista de créditos', error)
    } finally {
      this.isLoading = false
    }
  },

  // "Visar Manualmente": abre modal
  openManualVisadoModal(credit) {
    this.visadoForm = {
      doc: credit.doc || '',
      nombre: credit.name || '',
      pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
      plazo: credit.plazo || '',
      monto: credit.monto || '',
      cuotacredito: credit.cuota || '',
      estado: credit.status || 'factible',
      causal: '',
      visado_id: credit.visado_id || null,
      creditId: credit.id
    }
    this.showVisadoManualModal = true
  },
  async submitVisadoManual() {
  try {
    console.log("📥 Iniciando envío del formulario de visado manual...");
    this.isLoading = true;

    // Validación de campos obligatorios
    const requiredFields = {
      doc: 'Documento',
      nombre: 'Nombre',
      pagaduria: 'Pagaduría',
      plazo: 'Plazo',
      monto: 'Monto',
      cuotacredito: 'Cuota Crédito',
      estado: 'Estado',
      causal: 'Causal',
      observacion: 'Observación',
    };

    for (const [key, fieldName] of Object.entries(requiredFields)) {
      if (
        this.visadoForm[key] === '' ||
        this.visadoForm[key] === null ||
        this.visadoForm[key] === undefined
      ) {
        alert(`El campo "${fieldName}" es obligatorio.`);
        this.isLoading = false;
        return;
      }
    }

    const payload = {
      estado: this.visadoForm.estado,
      cuotacredito: this.visadoForm.cuotacredito,
      monto: this.visadoForm.monto,
      causal: this.visadoForm.causal,
      observacion: this.visadoForm.observacion,
      creditId: this.visadoForm.creditId,
      doc: this.visadoForm.doc,
      nombre: this.visadoForm.nombre,
      pagaduria: this.visadoForm.pagaduria,
      plazo: this.visadoForm.plazo
    };

    console.log("🚀 Payload para enviar:", payload);

    let response;
    if (this.visadoForm.visado_id) {
      console.log(`🔄 Actualizando visado existente con ID: ${this.visadoForm.visado_id}`);
      response = await axios.put(`/visados/${this.visadoForm.visado_id}`, payload);
    } else {
      console.log("✨ Creando un nuevo visado...");
      response = await axios.post(`/visados`, payload);
    }

    console.log("✅ Respuesta del servidor:", response.data);

    alert("Visado manual guardado con éxito.");
    this.showVisadoManualModal = false;

    await this.fetchCredits();

  } catch (err) {
    console.error("❌ Error al enviar el formulario de visado manual:", err);
    alert(`Error guardando visado manual: ${err.response?.data?.message || err.message}`);
  } finally {
    this.isLoading = false;
    console.log("🛑 Fin del proceso de envío del formulario.");
  }
},

  // Ver Carteras => modal
  showCarteras(credit) {
    if (credit.carteras && credit.carteras.length) {
      this.selectedCarteras = credit.carteras
    } else {
      this.selectedCarteras = []
    }
    this.showCarterasModal = true
  },

  // Helpers
  getPagaduriaNameById(id) {
    return allPagaduriasMap[id] || `ID: ${id}`
  },
  formatCurrency(value) {
    const num = parseFloat(value)
    if (!num || isNaN(num)) return '$0'
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      minimumFractionDigits: 0
    }).format(num)
  },
  formatPercentage(value) {
    const num = parseFloat(value)
    if (!num || isNaN(num)) return '0%'
    return `${num.toFixed(2)}%`
  },
  getDownloadUrl(filePath) {
    // Ajusta según tu storage
    return `/storage/${filePath.replace('public/', '')}`
  },

      
          emitInfo() {
              this.getAllPagadurias();
          },
          showToastError(message, ref) {
              this.$bvToast.toast(message, {
                  title: '¡Error!',
                  autoHideDelay: 5000,
                  solid: true,
                  variant: 'danger'
              });
  
              if (ref) {
                  this.$nextTick(() => {
                      this.$refs[ref].focus();
                      this.activeId = ref;
                  });
              }
          },
          async validationForm() {
              if (!this.dataclient.doc) {
                  this.showToastError('Debes llenar el campo de la cédula, es obligatorio', 'dataclientDoc');
                  return;
              }
  
              if (!this.dataclient.name) {
                  this.showToastError('Debes llenar el campo del nombre, es obligatorio', 'dataclientName');
                  return;
              }
  
              if (!this.dataclient.monto) {
                  this.showToastError('Debes colocar el campo del monto, es obligatorio', 'dataclientMonto');
                  return;
              }
  
              if (!this.dataclient.cuotadeseada) {
                  this.showToastError(
                      'Debes colocar el campo de la cuota deseada es obligatorio',
                      'dataclientCuotaDeseada'
                  );
                  return;
              }
  
              if (!this.dataclient.plazo) {
                  this.showToastError('Debes colocar el campo del plazo deseada es obligatorio', 'dataclientPlazo');
                  return;
              }
  
              if (this.userRole != 'ADMIN_SISTEMA') {
                  if (this.user.consultas_diarias <= 0) {
                      this.showToastError(`No tienes consultas disponibles`);
                      return;
                  }
              }
  
              this.getAllPagadurias();
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
  
          async modalConfirmConsultPag(credit) {
  console.log("⚙️ Iniciando consulta con credit:", credit);

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
    .then(async confirmed => {
      console.log("✅ Confirmación modal:", confirmed);
      if (!confirmed) return;

      this.isLoading = true;

      try {
        const response = await axios.get(`/pagadurias/per-doc/${credit.doc}`);
        console.log("🔍 Pagadurías obtenidas:", response.data);

        if (Object.keys(response.data).length > 0) {
          this.dataclient.pagadurias = response.data;
          console.log("📌 Pagadurías asignadas:", this.dataclient.pagadurias);
          this.setCuotaDeseada(credit.cuota);
          console.log("📌 Cuota deseada asignada:", credit.cuota);
        }

        const pagaduriaName = this.getPagaduriaNameById(credit.pagaduria_id);
        console.log("📌 Pagaduría encontrada:", pagaduriaName, "para ID:", credit.pagaduria_id);

        if (!pagaduriaName) {
          console.warn("⚠️ No se pudo obtener nombre de pagaduría con ID:", credit.pagaduria_id);
          this.isLoading = false;
          return;
        }

        const normalize = str => str?.toString().toUpperCase().replace(/\s+/g, '');
        const pagaduriaValue = normalize(pagaduriaName);
        console.log("📌 Pagaduría normalizada:", pagaduriaValue);

        this.setPagaduriaType(pagaduriaValue);
        console.log("📌 PagaduriaType establecido:", pagaduriaValue);

        const type = this.pagaduriasTypes.find(t => normalize(t.value) === pagaduriaValue);
        console.log("🔎 Resultado búsqueda de type:", type);

        if (!type || !this.dataclient.pagadurias[type.key]) {
          console.warn("⚠️ Formato de pagaduría no válido o tipo no encontrado:", { couponType: type, pagaduriaLabel: pagaduriaValue });
          toastr.warning("Formato de pagaduría no válido o no encontrado.");
          this.isLoading = false;
          return;
        }

        const pagaduria = this.dataclient.pagadurias[type.key];
        console.log("📌 Pagaduria obtenida desde dataclient.pagadurias:", pagaduria);

        this.dataclient.pagaduriaKey = type.key.slice(7).toLowerCase();
        console.log("📌 PagaduriaKey asignado:", this.dataclient.pagaduriaKey);

        pagaduria.documentType = 'documentType';
        this.dataclient.cargo = pagaduria.cargo;
        console.log("📌 Cargo asignado:", this.dataclient.cargo);

        const pagaduriaLabel = type.label;
        this.setPagaduriaLabel(pagaduriaLabel);
        console.log("📌 PagaduriaLabel asignado:", pagaduriaLabel);

        const baseKey = type.key.includes('datames') ? type.key.slice(7) : type.key;
        console.log("📌 BaseKey calculado:", baseKey);

        const couponType = type.key.includes('datames') ? `Coupons${baseKey}` : type.key;
        const embargosType = type.key.includes('datames') ? `Embargos${baseKey}` : type.key;
        const descuentosType = type.key.includes('datames') ? `Descuentos${baseKey}` : type.key;

        this.setCouponsType(couponType);
        this.setEmbargosType(embargosType);
        this.setDescuentosType(descuentosType);

        console.log("📌 couponType establecido:", couponType);
        console.log("📌 embargosType establecido:", embargosType);
        console.log("📌 descuentosType establecido:", descuentosType);

        this.setDatamesSed(pagaduria);
        console.log("📌 DatamesSed asignado:", pagaduria);

        this.dataclient = {
          doc: credit.doc,
          name: credit.name,
          cuotadeseada: credit.cuota,
          monto: credit.monto,
          plazo: credit.plazo,
          pagaduria: pagaduriaValue,
          pagadurias: response.data,
          pagaduriaKey: this.dataclient.pagaduriaKey,
        };
        console.log("✅ dataclient actualizado:", this.dataclient);

        const status = await this.saveVisados(credit);
        console.log("📌 Status devuelto por saveVisados:", status);

        if (status === 201) {
          const payload = {
            ...this.dataclient,
            carteras: credit.carteras || []
          };

          console.log("✅ Emitiendo información (emitInfo) con payload:", payload);
          this.$emit('emitInfo', payload);

          this.flag = true;
          this.showTable = false;
          console.log("✅ Tabla ocultada y flag activado");
        }

      } catch (error) {
        console.error("❌ Error en proceso visado:", error);
        toastr.error("Error durante el proceso de visado");
      } finally {
        this.isLoading = false;
        console.log("🛑 Fin de consulta");
      }
    });
},

async saveVisados(credit) {
  try {
    this.isLoading = true;
    console.log("Save visado para:", credit);

    const demograficoResponse = await axios.get(`/demografico/${credit.doc}`);
    const demograficoData = demograficoResponse.data;

    if (!demograficoData.nombre_usuario) {
      toastr.error('No se encontró el nombre del usuario');
      return;
    }

    const data = {
      pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
      nombre: demograficoData.nombre_usuario,
      doc: credit.doc,
      plazo: credit.plazo
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
  
<style scoped>
.table-container {
  width: 100%;
  padding: 20px;
}
.table-responsive {
  overflow-x: auto;
  width: 100%;
}
.btn-credit {
  color: white;
  background: #0cedb0;
  border: none;
  border-radius: 5px;
  padding: 7px 14px;
  font-size: 14px;
  cursor: pointer;
  margin: 2px;
}
.modal-content,
.modal-body {
  background-color: #ffffff !important;
  color: #000000 !important;
}
label {
  color: #000 !important;
  font-weight: 600;
}
.form-control {
  color: #000 !important;
  background-color: #fff !important;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>