<template>
    <v-app id="inspire">
        <v-card flat>
            <v-toolbar class="mt-2">
                <v-container class="mt-1 pt-6">

                    <v-row>
                        <v-col md="3">
                            <v-text-field v-model="dato_busqueda" hide-details label="Buscar"
                                placeholder="Escriba el dato a buscar">
                            </v-text-field>
                        </v-col>
                        <v-col md="3">
                            <v-select :items="opciones_busqueda" v-model="busqueda" dense outlined></v-select>
                        </v-col>
                        <v-col md="auto" sm="auto" xs="auto">
                            <v-btn icon @click="buscar_datos">
                                <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                        </v-col>
                    </v-row>

                </v-container>

            </v-toolbar>
            <v-card-title v-if="segunda_busqueda">
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Seguda Busqueda" single-line
                    hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="desserts" item-key="ci" hide-action :search="search">

                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="saludo(item)">
                        mdi-pencil
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>
    </v-app>



</template>

<script>
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    data: () => ({

        buscar: false,
        dato_busqueda: '',
        busqueda: 'Nombre',
        opciones_busqueda: ['Nombre', 'ci'],
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
            text: 'Actions',
            value: 'actions',
            sortable: false
        },
        ],
        desserts: [

        ],
    }),
    methods: {
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
            this.$emit('actulizar_ci',item)
            this.$emit('actulizar_av_buscar',false)
            
        }
    },
}
</script>
