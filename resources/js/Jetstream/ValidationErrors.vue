<template>
    <div v-if="hasErrors">

        <v-alert border="bottom" colored-border type="warning" elevation="2">
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
            </ul>
        </v-alert>

    </div>
</template>

<script>
export default {
    computed: {
        errors() {
            return this.$page.props.errors
        },

        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    }
}
</script>
