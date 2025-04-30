<template>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Tipo Solicitante</th>
            <th>Empresa</th>
            <th>Pagaduría</th>
            <th>Cuota</th>
            <th>Monto</th>
            <th>Tasa&nbsp;(Mensual)</th>
            <th>Plazo</th>
            <th>Estado</th>
            <th>Tipo&nbsp;Crédito</th>
            <th>Creado</th>
            <th>Documentos</th>
            <th v-if="isAdmin">Acción</th>
          </tr>
        </thead>
  
        <tbody>
          <tr v-for="credit in credits" :key="credit.id">
            <td>{{ credit.id }}</td>
            <td>{{ credit.doc }}</td>
            <td>{{ credit.name }}</td>
            <td>{{ credit.client_type }}</td>
            <td>{{ credit.empresa }}</td>
            <td>{{ getPagaduriaNameById(credit.pagaduria_id) }}</td>
            <td>{{ formatCurrency(credit.cuota) }}</td>
            <td>{{ formatCurrency(credit.monto) }}</td>
            <td>{{ formatPercentage(credit.tasa) }}</td>
            <td>{{ credit.plazo }}</td>
  
            <!--  ─────  Badge de estado  ─────  -->
            <td>
              <span class="status-badge"
                    :class="statusClass(credit.status)">
                <i :class="iconClass(credit.status)"
                   class="status-icon" />
                {{ statusLabel(credit.status) }}
              </span>
            </td>
  
            <td>{{ credit.tipo_credito }}</td>
            <td>{{ formatDate(credit.created_at) }}</td>
  
            <td>
              <template v-if="credit.documents && credit.documents.length">
                <div v-for="(doc, i) in credit.documents" :key="doc.id">
                  <a :href="getDownloadUrl(doc.file_path)" target="_blank">
                    doc-{{ i + 1 }}
                  </a>
                </div>
              </template>
              <span v-else>No hay documentos</span>
            </td>
  
            <td v-if="isAdmin">
              <button class="btn-credit"
                      @click="$emit('open-visado', credit)">
                Visar Manualmente
              </button>
              <button class="btn-credit ml-2"
                      @click="$emit('view-carteras', credit)">
                <i class="fas fa-eye" />
              </button>
              <button class="btn-credit ml-2"
                      @click="$emit('visar', credit)">
                Visar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CreditRequestTable',
    props: {
      credits: { type: Array, required: true },
      isAdmin: { type: Boolean, default: false }
    },
    data () {
      return {
        allPagaduriasMap: {
        /* Pensionados */
        200: 'COLPENSIONES',
        201: 'FOPEP',
        297: 'FIDUPREVISORA',
        296: 'CASUR',

        /* Docentes */
        1: 'SED AMAZONAS',
        130: 'SED ANTIOQUIA',
        109: 'SED ARAUCA',
        121: 'SED ATLANTICO',
        293: 'SED BOLIVAR',
        110: 'SED BOYACA',
        139: 'SED CALDAS',
        140: 'SED CAQUETA',
        104: 'SED CASANARE',
        177: 'SED CAUCA',
        11: 'SED CESAR',
        294: 'SED CHOCO',
        182: 'SED CORDOBA',
        163: 'SED CUNDINAMARCA',
        192: 'SED GUAJIRA',
        173: 'SED GUAVIARE',
        178: 'SED HUILA',
        145: 'SED MAGDALENA',
        113: 'SED META',
        143: 'SED NARIÑO',
        154: 'SED N. SANTANDER',
        184: 'SED PUTUMAYO',
        166: 'SED QUINDIO',
        114: 'SED RISARALDA',
        26: 'SED SANTANDER',
        175: 'SED SUCRE',
        122: 'SED TOLIMA',
        165: 'SED VALLE',
        132: 'SED VAUPES',
        32: 'SED VICHADA',
        27: 'SED SINCELEJO',
        34: 'SEM ARMENIA',
        160: 'SEM BARRANCABERMEJA',
        106: 'SEM BARRANQUILLA',
        111: 'SEM BELLO',
        39: 'SEM BUCARAMANGA',
        40: 'SEM BUENAVENTURA',
        157: 'SEM BUGA',
        191: 'SEM CALI',
        189: 'SEM CARTAGENA',
        136: 'SEM CARTAGO',
        45: 'SEM CHÍA',
        103: 'SEM CIÉNAGA',
        286: 'SEM CÚCUTA',
        112: 'SEM DOSQUEBRADAS',
        49: 'SEM DUITAMA',
        115: 'SEM ENVIGADO',
        168: 'SEM ESTRELLA',
        164: 'SEM FACATATIVA',
        55: 'SEM FLORENCIA',
        170: 'SEM FLORIDABLANCA',
        117: 'SEM FUNZA',
        151: 'SEM FUSAGASUGÁ',
        179: 'SEM GIRARDOT',
        287: 'SEM GIRÓN',
        116: 'SEM GUAINÍA',
        147: 'SEM IBAGUÉ',
        134: 'SEM IPIALES',
        135: 'SEM ITAGÜÍ',
        146: 'SEM JAMUNDÍ',
        67: 'SEM LORICA',
        133: 'SEM MAGANGUÉ',
        69: 'SEM MAICAO',
        161: 'SEM MALAMBO',
        174: 'SEM MANIZALES',
        180: 'SEM MEDELLÍN',
        176: 'SEM MONTERÍA',
        153: 'SEM MOSQUERA',
        105: 'SEM NEIVA',
        152: 'SEM PALMIRA',
        125: 'SEM PASTO',
        78: 'SEM PEREIRA',
        79: 'SEM PIEDECUESTA',
        138: 'SEM PITALITO',
        162: 'SEM QUIBDÓ',
        150: 'SEM RIOHACHA',
        129: 'SEM RIONEGRO',
        108: 'SEM SABANETA',
        142: 'SEM SAHAGÚN',
        158: 'SEM SAN',
        126: 'SEM SANTA MARTA',
        119: 'SEM SOACHA',
        172: 'SEM SOGAMOSO',
        123: 'SEM SOLEDAD',
        120: 'SEM TULUÁ',
        93:  'SEM TUMACO',
        141: 'SEM TUNJA',
        137: 'SEM TURBO',
        144: 'SEM URIBIA',
        171: 'SEM VALLEDUPAR',
        124: 'SEM VILLAVICENCIO',
        289: 'SEM YOPAL',
        169: 'SEM YUMBO',
        156: 'SEM ZIPAQUIRÁ'
      }
    }
    },
    methods: {
      /* ───── utilidades de formato ───── */
      formatCurrency (v) {
        const n = parseFloat(v)
        return isNaN(n)
          ? '$0'
          : new Intl.NumberFormat('es-CO', {
              style: 'currency',
              currency: 'COP',
              minimumFractionDigits: 0
            }).format(n)
      },
      formatPercentage (v) {
        const n = parseFloat(v)
        return isNaN(n) ? '0%' : `${n.toFixed(2)}%`
      },
      formatDate (v) {
        return new Date(v).toLocaleDateString('es-CO')
      },
      getDownloadUrl (p) {
        return `/storage/${p.replace('public/', '')}`
      },
      getPagaduriaNameById (id) {
        return this.allPagaduriasMap[id] || `ID:${id}`
      },
  
      /* ─────  Lógica de estado / badge ───── */
      normalizeStatus (raw) {
        return (raw || '')
          .toString()
          .trim()
          .toLowerCase()
      },
      statusLabel (raw) {
        const s = this.normalizeStatus(raw)
        if (s.includes('no factible') || s.includes('rejected')) return 'Rechazado'
        if (s.includes('factible')     || s.includes('approved')) return 'Aprobado'
        return 'Pendiente'
      },
      statusClass (raw) {
        const s = this.normalizeStatus(raw)
        return {
          'status-rejected':  s.includes('no factible') || s.includes('rejected'),
          'status-approved':  s.includes('factible') && !s.includes('no factible') || s.includes('approved'),
          'status-pending':   !s.includes('factible')  && !s.includes('no factible') && !s.includes('approved') && !s.includes('rejected')
        }
      },
      iconClass (raw) {
        const s = this.normalizeStatus(raw)
        if (s.includes('no factible') || s.includes('rejected'))  return 'fas fa-times-circle'
        if (s.includes('factible')    || s.includes('approved'))  return 'fas fa-check-circle'
        return 'fas fa-info-circle'  // pendiente
      }
    }
  }
  </script>
  
  <style scoped>
  /*  Contenedor tabla  */
  .table-responsive { overflow-x: auto; }
  
  /*  Botón reutilizado  */
  .btn-credit {
    color:#fff; background:#0cedb0;
    border:none; border-radius:5px;
    padding:7px 14px; font-size:14px;
    cursor:pointer; margin:2px;
  }
  
  /*  Badge de estado  */
  .status-badge{
    display:inline-flex; align-items:center;
    gap:6px; padding:4px 14px;
    border-radius:9999px; font-size:.9rem;
    font-weight:600;
  }
  .status-icon{ font-size:1.1em; }
  
  /*  Colores  */
  .status-approved{ background:#2e7d32; color:#fff; }
  .status-rejected{ background:#d32f2f; color:#fff; }
  .status-pending { background:#e0e0e0; color:#000; }
  </style>
  