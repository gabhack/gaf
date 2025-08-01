<template>
  <b-button @click="print" variant="black-pearl" class="px-3">
    <span class="mr-1">Descargar PDF</span>
    <download-icon></download-icon>
  </b-button>
</template>

<script>
export default {
  methods: {
    print() {
      window.print();
    }
  }
};
</script>

<style lang="scss" scoped>
svg {
  fill: white;
}
</style>