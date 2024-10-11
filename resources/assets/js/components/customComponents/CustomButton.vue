<template>
    <b-button 
      :size="size" 
      :disabled="disabled"
      :block="block"
      @click="$emit('click')"
    >
        <slot>{{ text }}</slot>
    </b-button>
</template>

<script>
export default {
    name: 'CustomButton',
    props: {
        text: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'md'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        block:{
            type: Boolean,
            default: false
        }
    }
};
</script>

<style scoped lang="scss">
  button{
    background-color: #0E866C;
    border-radius: 8px;
    border-color: none;
    font-size: 14px;
    font-weight: 500;
    line-height: 18.23px;
    min-height: 40px;
  }
</style>
