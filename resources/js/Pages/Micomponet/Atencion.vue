<template>

    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>ATENCION
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="dialog = false">
                        Guardar
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card>
                <v-container fluid>
                    <v-data-table :headers="dessertHeaders" :items="equipo" :single-expand="singleExpand"
                        :expanded.sync="expanded" item-key="id" show-expand class="elevation-1">
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title>Expandable Table</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-switch v-model="singleExpand" label="Single expand" class="mt-2"></v-switch>
                            </v-toolbar>
                        </template>
                        <template v-slot:expanded-item="{ item }">

                            <td>

                                Cedula de identidad: {{ item.ci }} {{ item.expedido }}
                            </td>
                            <td>
                                Contratado {{ item.id_establecimiento }}
                            </td>

                            <td>
                                celular  {{ item.celular }}

                            </td>

                            <td>
                                <v-container>
                                    <v-row>
                                        <v-col>

                                            email {{ item.email }}
                                        </v-col>
                                    </v-row>
                                    
                                </v-container>
                            </td>



                            <v-row>
                                <v-col>
                                    <v-btn color="info">
                                        cambiar
                                    </v-btn>

                                </v-col>
                            </v-row>




                        </template>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-card>

    </v-dialog>


</template>
<script>

import { throwStatement } from '@babel/types';
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {

    props: {
        equipo: Array,

    },

    data: () => ({
        vista: true,
        dialog: false,

        seleccion_profesion: ['foo', 'bar', 'fizz', 'buzz', 'add'],
        lista_profesion: ['foo', 'bar', 'fizz', 'add'],
        selected: [],
        selected2: [],
        items_t: [],
        items: [
            {
                action: '15 min',
                headline: 'Brunch this weekend?',
                subtitle: `I'll be in your neighborhood doing errands this weekend. Do you want to hang out?`,
                title: 'Ali Connors',
            },
            {
                action: '2 hr',
                headline: 'Summer BBQ',
                subtitle: `Wish I could come, but I'm out of town this weekend.`,
                title: 'me, Scrott, Jennifer',
            },
            {
                action: '6 hr',
                headline: 'Oui oui',
                subtitle: 'Do you have Paris recommendations? Have you ever been?',
                title: 'Sandra Adams',
            },
            {
                action: '12 hr',
                headline: 'Birthday gift',
                subtitle: 'Have any ideas about what we should get Heidi for her birthday?',
                title: 'Trevor Hansen',
            },
            {
                action: '18hr',
                headline: 'Recipe to try',
                subtitle: 'We should eat this: Grate, Squash, Corn, and tomatillo Tacos.',
                title: 'Britta Holt',
            },
        ],

        expanded: [],
        singleExpand: false,
        dessertHeaders: [
            /*{
                text: 'Dessert (100g serving)',
                align: 'start',
                sortable: false,
                value: 'name',
            },*/
            { text: 'Nombres', value: 'nombre' },
            { text: 'Apellido Paterno', value: 'ap_paterno' },
            { text: 'Apellido Materno', value: 'ap_materno' },
            { text: 'Cedula de identidad', value: 'ci' },

            { text: 'Cargo', value: 'cargo' },



            { text: '', value: 'data-table-expand' },
        ],
        desserts: [
        ],
    }),
    created() {
        console.log("inicioao");

    },
    updated() {

    },
    mounted() {


    },
    methods: {
        open() {
            this.dialog = true
            this.lista_cargo()
        },
        close() {
            this.dialog = false
        },
        save() {
            this.dialog = true
        },
        async lista_cargo() {
            try {
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/api/listar_profesion/`,
                }).then(
                    (response) => {
                        console.log(response);
                        this.lista_profesion = []
                        this.seleccion_profesion = []
                        this.lista_profesion = response.data
                        this.seleccion_profesion = response.data

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }

        },

        elegir(active) {
            console.log(active.title)
            let s = this.items_t.findIndex(x => x.title === active.title)
            console.log(s);
            if (s >= 0) {
                this.items_t.splice(s, 1);
            } else {
                this.items_t.push(active)

            }
        },
        comparar(x) {

            console.log(x.title)
            return this.items_t.title === x.title;
        },
    }
}
</script>
