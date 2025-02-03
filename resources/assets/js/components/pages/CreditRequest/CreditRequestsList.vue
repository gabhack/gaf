<template>
  <div class="table-container" v-if="showTable">
    <div class="table-responsive">
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
            <th>Score</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="credit in credits" :key="credit.id">
            <td>{{ credit.id }}</td>
            <td>{{ credit.doc }}</td>
            <td>{{ credit.name }}</td>
            <td>{{ credit.client_type }}</td>
            <!-- Se muestra el nombre de la pagaduria en mayúsculas -->
            <td>{{ getPagaduriaNameById(credit.pagaduria_id) }}</td>
            <td>{{ formatCurrency(credit.cuota) }}</td>
            <td>{{ formatCurrency(credit.monto) }}</td>
            <td>{{ formatPercentage(credit.tasa) }}</td>
            <td>{{ credit.plazo }}</td>
            <td>{{ credit.status }}</td>
            <td></td>
            <td>
              <!-- Botón para aprobar (si aún no está aprobado) -->
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón para ver carteras -->
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>

              <!-- Botón para emitir la información (Visar) y ocultar la tabla -->
              <button class="btn-credit ml-2" @click="emitClientData(credit)">
                Visar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

// Definimos el mapeo de pagadurías (los nombres en minúscula se asocian a un id)
const pagaduriasMap = {
  'sed amazonas': 1,
  'sed antioquia': 130,
  'sed arauca': 109,
  'sed atlantico': 121,
  'sed bolivar': 5,
  'sed boyaca': 110,
  'sed caldas': 139,
  'sed caqueta': 140,
  'sed casanare': 104,
  'sed cauca': 177,
  'sed cesar': 11,
  'sed choco': 12,
  'sed cordoba': 182,
  'sed cundinamarca': 163,
  'sed guajira': 192,
  'sed guaviare': 173,
  'sed huila': 178,
  'sed magdalena': 145,
  'sed meta': 113,
  'sed narino': 143,
  'sed norte de santander': 154,
  'sed putumayo': 184,
  'sed quindio': 166,
  'sed risaralda': 114,
  'sed santander': 26,
  'sed sucre': 175,
  'sed tolima': 122,
  'sed valle': 165,
  'sed vaupes': 132,
  'sed vichada': 32,
  'sem sincelejo': 27,
  'sem armenia': 34,
  'sem barrancabermeja': 160,
  'sem barranquilla': 106,
  'sem bello': 111,
  'sem bucaramanga': 39,
  'sem buenaventura': 40,
  'sem buga': 157,
  'sem cali': 42,
  'sem cartagena': 43,
  'sem cartago': 136,
  'sem chia': 45,
  'sem cienaga': 103,
  'sem cucuta': 47,
  'sem dosquebradas': 112,
  'sem duitama': 49,
  'sem envigado': 115,
  'sem estrella': 168,
  'sem facatativa': 164,
  'sem florencia': 55,
  'sem floridablanca': 170,
  'sem funza': 117,
  'sem fusagasuga': 151,
  'sem girardot': 179,
  'sem giron': 61,
  'sem guainia': 116,
  'sem ibague': 147,
  'sem ipiales': 134,
  'sem itagui': 135,
  'sem jamundi': 146,
  'sem lorica': 67,
  'sem magangue': 133,
  'sem maicao': 69,
  'sem malambo': 161,
  'sem manizales': 174,
  'sem medellin': 180,
  'sem monteria': 176,
  'sem mosquera': 153,
  'sem neiva': 105,
  'sem palmira': 152,
  'sem pasto': 125,
  'sem pereira': 78,
  'sem piedecuesta': 79,
  'sem pitalito': 138,
  'sem popayan': 159,
  'sem quibdo': 162,
  'sem riohacha': 150,
  'sem rionegro': 129,
  'sem sabaneta': 108,
  'sem sahagun': 142,
  'sem san andres': 158,
  'sem santa marta': 126,
  'sem soacha': 119,
  'sem sogamoso': 172,
  'sem soledad': 123,
  'sem tulua': 120,
  'sem tumaco': 93,
  'sem tunja': 141,
  'sem turbo': 137,
  'sem uribia': 144,
  'sem valledupar': 171,
  'sem villavicencio': 124,
  'sem yopal': 100,
  'sem yumbo': 169,
  'sem zipaquira': 156
};

export default {
  name: 'CreditRequestsListWithVisado',
  data() {
    return {
      credits: [],
      showTable: true // controla la visualización de la tabla
    };
  },
  mounted() {
    this.fetchCredits();
  },
  methods: {
    async fetchCredits() {
      try {
        // Se asume que este endpoint devuelve la lista de créditos
        const response = await axios.get('/credit-requests/all');
        this.credits = response.data;
      } catch (error) {
        console.error('Error al obtener la lista de créditos', error);
      }
    },
    formatCurrency(value) {
      const num = parseFloat(value);
      if (!num || isNaN(num)) return '$0';
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
      }).format(num);
    },
    formatPercentage(value) {
      const num = parseFloat(value);
      if (!num || isNaN(num)) return '0%';
      return `${num.toFixed(2)}%`;
    },
    approveRequest(creditId) {
      console.log('Aprobando crédito con ID:', creditId);
    },
    showCarteras(credit) {
      console.log('Mostrando carteras para crédito:', credit);
    },
    // Función para obtener el nombre de la pagaduría en mayúsculas a partir de su id
    getPagaduriaNameById(id) {
      for (const [name, mappedId] of Object.entries(pagaduriasMap)) {
        if (parseInt(mappedId) === parseInt(id)) {
          return name.toUpperCase();
        }
      }
      // Si no se encuentra, se retorna el id o un valor por defecto
      return id;
    },
    emitClientData(credit) {
      // Se arma el objeto dataclient similar al que se usaba en FormConsult,
      // pero se asigna la pagaduría con su nombre en mayúsculas
      const dataclient = {
        doc: credit.doc,
        name: credit.name,
        cuotadeseada: credit.cuota, // ajustar según lógica de negocio
        monto: credit.monto,
        plazo: credit.plazo,
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
        // Otros campos opcionales:
        pagadurias: credit.pagadurias || null,
        pagaduriaKey: credit.pagaduriaKey || null,
        visado: credit.visado || null
      };

      // Emitimos la información al componente padre
      this.$emit('emitInfo', dataclient);
      // Ocultamos la tabla
      this.showTable = false;
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

.table {
  width: 100%;
  margin-top: 20px;
  border-collapse: collapse;
}

th,
td {
  text-align: center;
  vertical-align: middle;
}

.btn-credit {
  color: white;
  background-image: linear-gradient(
    to right,
    #0cedb0 0%,
    #0cedb0 55%,
    #0cedb0 100%
  );
  transition: 0.5s;
  background-size: 200% auto;
  border: none;
  border-radius: 5px;
  padding: 7px 14px;
  font-size: 14px;
  cursor: pointer;
  margin: 2px;
}

.btn-credit:hover {
  background-position: right center;
}

.ml-2 {
  margin-left: 0.5rem;
}
</style>
