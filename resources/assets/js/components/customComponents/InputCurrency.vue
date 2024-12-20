<template>
    <div class="form-group">
        <div class="input-group">
            <span v-if="currency" class="input-group-text">$</span>
            <input
                :id="id"
                class="style-form-control"
                style="width: 88%"
                :class="validateClass === true ? 'errorValid' : 'er'"
                type="text"
                :value="formattedValue"
                @input="updateValue"
                :placeholder="placeholder"
                required
            />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: [String, Number],
            required: true
        },
        label: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            default: 'Placeholder'
        },
        rules: {
            type: String,
            default: 'required'
        },
        currency: {
            type: Boolean,
            default: true
        },
        validateClass: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        formattedValue() {
            return this.value ? new Intl.NumberFormat('es-CO').format(this.value) : '';
        }
    },
    methods: {
        updateValue(event) {
            const rawValue = event.target.value.replace(/\./g, '');
            this.$emit('input', rawValue ? Number(rawValue) : '');
        }
    }
};
</script>
<style scoped>
.form-control {
    background-color: white !important;
}
</style>
