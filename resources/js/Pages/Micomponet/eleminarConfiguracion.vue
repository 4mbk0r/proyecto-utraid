<template>
    <v-dialog v-if="dialog" v-model="dialog" persistent max-width="600px">

        <v-card>
            <v-card-title>
                <span class="text-h5">{{ mensaje.titulo }}</span>
            </v-card-title>
            <v-card-text>
                <h1>
                    {{ mensaje.texto }}
                </h1>

            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="aceptar">
                    Eliminar
                </v-btn>
                <v-btn color="red darken-1" text @click="close">
                    Cancelar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>



</template>

<script>
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        dialog: Boolean,
        mensaje: Object,
        datos: Object,
    },
    data: () => ({
        //dialog: false,

    }),
    mounted() {
        //console.log('sssss', this.item);
    },
    computed: {

    },
    methods: {
        close() {
            //this.dialog = false
            //console.log(this.date);
            this.$emit('respuesta', false)
        },
        aceptar() {
            //this.dialog = false
            //console.log(this.date);
            this.$emit('respuesta', true)
        },
    },
}
</script>
