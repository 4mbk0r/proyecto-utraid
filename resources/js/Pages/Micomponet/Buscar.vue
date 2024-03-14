<template>
    <v-card class="mt-2">
        <v-row class="mt-2">
            <v-col md="4" cols="12">
                <v-text-field v-model="dato_busqueda" label="Buscar" placeholder="Escriba el dato a buscar"
                    @keyup.enter="buscar_datos">
                </v-text-field>
            </v-col>
            <v-col md="4" cols="12">
                <v-btn icon @click="buscar_datos">
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </v-col>
            <v-divider></v-divider>
            <v-col md="4" cols="12">
                Buscar por:
                <v-select :items="opciones_busqueda" v-model="busqueda" item-value="value" dense outlined></v-select>
            </v-col>
        </v-row>
        <v-card-title v-if="segunda_busqueda">
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Seguda Busqueda" single-line hide-details>
            </v-text-field>
        </v-card-title>
        <v-card-text>
            <v-data-table :headers="headers" :items="desserts" item-key="ci" hide-action :search="search">
                <template v-slot:item.actions="{ item }">
                    <v-btn small class="mr-2" @click="saludo(item)">
                        Editar
                    </v-btn>
                </template>
            </v-data-table>
        </v-card-text>
        <datos v-if="dialog_persona" @pedir='actualizador' ref="dato">
        </datos>
    </v-card>
    
</template>

<script>
import axios from 'axios'
import datos from '@/Pages/Micomponet/Datospersonales'

import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    components: {
        datos
    },
    data: () => ({

        buscar: false,
        dato_busqueda: '',
        busqueda: 1,
        opciones_busqueda: [
            { text: 'Nombre Completo', value: 1 },
            { text: 'Cedula de Identidad', value: 2 }
        ],
        segunda_busqueda: false,
        search: '',
        headers: [{
            text: 'Nombre',
            align: 'start',
            //sortable: false,
            value: 'nombres',
        },
        {
            text: 'Apellido Paterno',
            value: 'ap_paterno'
        },
        {
            text: 'Apellido Materno',
            value: 'ap_materno'
        },
        {
            text: 'Cedula de Identidad',
            value: 'ci'
        },
        {
            text: 'Celular',
            value: 'celular'
        },
        {
            text: 'Tareas',
            value: 'actions',
            sortable: false
        },
        ],
        desserts: [
            {}
        ],
        dialog_persona:true,
    }),
    methods: {
        
        empty(variable) {
            if (
                variable === null ||
                variable === undefined ||
                (typeof variable === 'number' && isNaN(variable)) ||
                (typeof variable === 'string' && variable.trim() === '') ||
                (Array.isArray(variable) && variable.length === 0)
            ) {
                return true;
            } else {
                return false;
            }
        },
        actualizador(fecha) {
            console.log('.+.+.+.+')
            //this.fecha_calendario =
            console.log(this.fecha_calendario)
            //this.pedir_datos(this.fecha_calendario)
        },
        async buscar_datos() {
            this.segunda_busqueda = false
            var res = await axios({
                method: 'post',
                url: 'api/buscar_persona',
                data: {
                    busqueda: this.busqueda,
                    dato: this.dato_busqueda,
                }
            }).then();
            console.log(res['data']);
            this.desserts = res['data'];
            if (this.desserts.length >= 5) {
                this.segunda_busqueda = true;
            }
        },
        handleRowClick(item) {
            alert('You clicked ' + item.ci);
        },
        saludo(item) {
            console.log(item);
            this.open_agenda(item)
        },
        open_agenda(x) {
            //this.selectedOpen = false
            //this.dialog_persona = true
            setTimeout(() => {
                this.$refs.dato.op1 = 1;
                this.$refs.dato.fecha_cita = this.fecha_calendario
                this.$refs.dato.cita_nueva = x//this.selectedEvent.fichas
                this.$refs.dato.open()
                if (!this.empty(x.ci)) {
                    this.$refs.dato.mostrarDatos(x)
                    this.$refs.dato.op1 = 2;
                    this.$refs.dato.actualizadorLugar()
                }
            }, 1);
        },


    },
}
</script>
