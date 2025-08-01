<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-12">
        <p>Cargar el archivo en formato CSV.</p>
        <div class="d-flex aligns-items-center">
          <b-form-file
            v-model="csvFile"
            :state="Boolean(csvFile)"
            placeholder="Elegir un archivo aquí..."
            drop-placeholder="Arrastrar un archivo aquí..."
            accept=".csv"
            :disabled="sending"
            class="w-auto mr-3"
          ></b-form-file>
          <b-button variant="elf-green" type="button" @click="handleSend" :disabled="sending || !Boolean(csvFile)">
            <div v-if="sending"><b-spinner small></b-spinner> <span class="sr-only">Loading...</span></div>
            <span v-else> Enviar </span>
          </b-button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      csvFile: null,
      sending: false
    };
  },
  methods: {
    fileToJson() {
      return new Promise((resolve, reject) => {
        const jsonData = [];
        const reader = new FileReader();

        reader.readAsText(this.csvFile);
        reader.onload = e => {
          const contents = e.target.result;
          const lines = contents.split('\r' + '\n');
          const headers = lines[0].toLowerCase().split(';');

          for (let i = 1; i < lines.length; i++) {
            if (lines[i]) {
              const obj = {};
              const currentline = lines[i].split(';');
              for (let j = 0; j < headers.length; j++) {
                obj[headers[j]] = currentline[j].trim();
              }
              jsonData.push(obj);
            }
          }

          resolve(jsonData);
        };
      });
    },
    sendMessage(data) {
      axios.post('/whatsapp-bot', data).catch(error => {
        console.log(error);
      });
    },
    async handleSend() {
      this.sending = true;
      const jsonData = await this.fileToJson();

      for (const data of jsonData) {
        this.sendMessage(data);
      }

      toastr.success('Se enviaron los mensajes');
      this.csvFile = null;
      this.sending = false;
    }
  }
};
</script>