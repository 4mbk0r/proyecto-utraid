<template>
    <v-button :type="type" >
        <slot></slot>
    </v-button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    }
</script>
