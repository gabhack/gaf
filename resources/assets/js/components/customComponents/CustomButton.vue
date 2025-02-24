<template>
    <b-button :size="size" :disabled="disabled" :block="block" :class="color" :type="type" @click="$emit('click')">
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
        block: {
            type: Boolean,
            default: false
        },
        color: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'button',
            validator: value => ['button', 'submit', 'reset'].includes(value)
        }
    }
};
</script>

<style scoped lang="scss">
button {
    padding: 8px 16px;
    border-radius: 8px;
    border-color: none;
    font-size: 14px;
    font-weight: 500;
    line-height: 18.23px;
    min-height: 40px;
    background-color: #0e866c;
    border-color: #0e866c;
    color: white;

    &:hover {
        border: 1px solid #0e866c;
        color: #0e866c;
    }

    &:active {
        background-color: #0e866c;
        border-color: #0e866c;
        color: white;
    }

    &.white {
        background-color: #fff;
        border: 1px solid #0e866c;
        color: #0e866c !important;

        &:hover {
            background-color: #0e866c;
            color: white !important;
        }
    }
}
</style>
