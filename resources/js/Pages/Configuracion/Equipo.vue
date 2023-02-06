
<template>
    <v-app>
        <v-card>

            <v-row>

                <v-col cols="6">
                    Seleccion de Equipos
                </v-col>
                <v-col cols="6">
                    <v-btn class="float-right" dense small @click="delete_all">
                        <v-icon color="yello w darken-3">
                            mdi-delete
                        </v-icon>
                        Eliminar Todo
                    </v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="6">

                    <v-list two-line>
                        <v-list-item-group v-model="selected_equipo" active-class="pink--text">
                            <template v-for="(item, index) in equipo">
                                <v-list-item :key="item.title" @click="seleccion_equipo">
                                    <template v-slot:default="{ active }">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.equipo"></v-list-item-title>

                                            <v-list-item-subtitle class="text--primary"
                                                v-text="item.headline"></v-list-item-subtitle>

                                            <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                            <v-icon v-if="!active" color="grey lighten-1">
                                                mdi-star-outline {{ getnumero_por_equipo }}
                                            </v-icon>

                                            <v-icon v-else color="yello w darken-3">
                                                mdi-star
                                            </v-icon>
                                        </v-list-item-action>
                                    </template>
                                </v-list-item>

                                <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                            </template>
                        </v-list-item-group>
                    </v-list>


                </v-col>
                <!--seleccion_equipo(selected_equipo)">-->

                <v-col cols="6">


                    <v-divider></v-divider>
                    <v-list two-line v-if="selected_equipo >= 0">
                        <v-list-item-group v-model="selected_lista" active-class="pink--text">
                            <template v-for="(item, index) in items">

                                <v-list-item v-if="item.equipo === selected_equipo && item.guardar" :key="item.title">
                                    <template v-slot:default="{ active }">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                            <v-list-item-subtitle class="text--primary"
                                                v-text="item.headline"></v-list-item-subtitle>

                                            <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                            <v-icon v-if="!active" color="grey lighten-1">
                                                mdi-star-outline
                                            </v-icon>

                                            <v-icon v-else color="yello w darken-3">
                                                mdi-star
                                            </v-icon>
                                            <v-btn @click="selected_delete(item)">
                                                <v-icon color="yello w darken-3">
                                                    mdi-delete
                                                </v-icon>
                                            </v-btn>
                                        </v-list-item-action>
                                    </template>
                                </v-list-item>

                                <v-divider v-if="index < items.length - 1" :key="index"></v-divider>

                            </template>

                        </v-list-item-group>
                    </v-list>
                </v-col>

            </v-row>
            <v-card-title>

            </v-card-title>
            <v-data-table :headers="headers" :items="items" item-key="ci" :search="search"
                @click:row="addequipo($event)" class="elevation-1 pa-6">
                <template v-slot:top>
                    <!-- v-container, v-col and v-row are just for decoration purposes. -->
                    <v-container fluid>
                        <v-row>

                            <v-col cols="6">
                                <v-row class="pa-6">
                                    <!-- Filter for dessert name-->
                                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line
                                        hide-details></v-text-field>

                                </v-row>
                            </v-col>

                            <v-col cols="6">
                                <v-row class="pa-6">
                                    <!-- Filter for calories -->
                                    <v-select :items="caloriesList" item-text="cargo" v-model="caloriesFilterValue"
                                        label="Cargos"></v-select>
                                </v-row>
                            </v-col>

                        </v-row>
                    </v-container>

                </template>
            </v-data-table>



        </v-card>
    </v-app>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        dialog: Boolean,
        mensaje: Object,
        datos: Object,
        equipo: Array,
        //cargos: Array,
    },
    created() {
        this.pedir_datos();
    },
    data: () => ({
        selected_medico: '',
        selected_psicologo: '',
        selected_trabajo: '',
        selected: [2],
        items: [],
        /*equipo: [
            {
                equipo: 'Equipo 1',
                lista: [
                ]
            },
            {
                equipo: 'Equipo 2',
                lista: [

                ]
            },
            {
                equipo: 'Equipo 3',
                lista: [

                ]
            }
        ],*/
        search: '',
        selected_equipo: '',
        selected_lista: [],
        caloriesList: [
            {
                cargo: 'Todos', value: null
            },
        ],

        caloriesFilterValue: null,

        // Filter models.
        dessertFilterValue: '',

        // Table data.


    }),
    mounted() {
        //console.log('sssss', this.item);
        if (this.equipo.length > 0) {
            this.selected_equipo = 0
        }
        console.log(this.cargos);
        this.pedir_cargos()
        /*if(this.cargos.length > 0 ){
            for (const key in this.cargos) {
                if (Object.hasOwnProperty.call(this.cargos, key)) {
                    //const element = this.cargos[key];
                    this.caloriesList.push(this.cargos[key])        
                }
            }
            
        }*/

    },
    components: {
        AppLayout
    },
    computed: {
        getnumero_por_equipo() {
            if (typeof this.equipo.lista === 'undefined') return ''
            if (this.equipo.length > 0) {
                return this.equipo[this.selected_equipo].lista.length
            }
            return 0
        },
        headers() {
            return [
                {
                    text: 'Nombres',
                    align: 'left',
                    //sortable: false,
                    value: 'nombre',
                    //filter: this.nameFilter,
                },
                {
                    text: 'Apellido Paterno',
                    value: 'ap_paterno',
                    //filter: this.caloriesFilter,
                },
                {
                    text: 'Apellido Materno',
                    value: 'ap_materno',
                    //filter: this.caloriesFilter,
                },
                {
                    text: 'Cedula de identidad',
                    value: 'ci',
                    //filter: this.caloriesFilter,
                },

                {
                    text: 'Cargo',
                    value: 'cargo',
                    filter: this.cargoFilter
                    //filter: this.caloriesFilter,
                },

                {
                    text: 'Equipo',
                    value: 'equipo',
                    //filter: this.cargoFilter
                    //filter: this.caloriesFilter,
                },


            ]
        },
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
        async pedir_cargos() {
            console.log("datops");
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/api/` + "cargo_servicio",
                }).then(
                    (response) => {
                        //console.log('dsf'+response); 
                        this.cargos = response.data
                        for (const key in this.cargos) {
                            if (Object.hasOwnProperty.call(this.cargos, key)) {
                                //const element = this.cargos[key];
                                this.caloriesList.push(this.cargos[key])
                            }
                        }
                        console.log(response.data)

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }

        },

        async pedir_datos() {
            console.log("datops");
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/` + "lista_personal",
                }).then(
                    (response) => {
                        //console.log('dsf'+response);
                        this.items = response.data
                        this.desserts = response.data

                        console.log('dato')
                        console.log(response.data)

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }

        },
        selected_delete(item) {
            console.log(item);
            item.guardar = false
            item.equipo = -1

            if (typeof this.selected_equipo == 'undefined') return
            let findy = this.equipo[this.selected_equipo].lista.findIndex(o => o.ci === item.ci)
            if (findy > -1) {
                this.equipo[this.selected_equipo].lista.splice(findy, 1)
            }
            //this.update_item()

        },
        seleccion_equipo() {
            //console.log(i);
            this.selected_medico = ''
            this.selected_psicologo = ''
            this.selected_trabajo = ''


        },
        addequipo(item) {
            console.log(item);
            console.log(this.selected_equipo);
            //console.log('-->', index);*/
            this.equipo[this.selected_equipo].lista.push(item)
            item.equipo =  this.selected_equipo
            item.guardar=  true
            console.log(item);
            console.log(this.items);
            /*
            if (typeof this.selected_equipo == 'undefined' || this.selected_equipo === '') {
                this.selected_medico = ''
                this.selected_psicologo = ''
                this.selected_trabajo = ''
                return

            }
            let findx = this.items.findIndex(o => o.cargo === item.cargo && o.equipo == this.selected_equipo)

            if (item.equipo >= 0) {
                item.equipo = -1
                item.guardar = false
                this.selected_delete(item)

            } else {
                if (findx >= 0) {

                    this.items[findx].equipo = -1
                    this.items[findx].guardar = false
                    //
                    this.selected_delete(this.items[findx])
                }
                item.equipo = this.selected_equipo
                item.guardar = true
                this.equipo[this.selected_equipo].lista.push(item)
            }
            console.log(this.items)
            //this.update_item()
            */
        },
        verificar(active, item) {
            return

        },
        verificar_delete_all() {

        },
        delete_all() {

            for (let i = 0; i < this.items.length; i++) {
                this.selected_delete(this.items[i]);
            }
            this.selected_medico = ''
            this.selected_psicologo = ''
            this.selected_trabajo = ''
            this.selected_equipo = 0
        },
        update_item() {
            this.$emit('update_equipo', this.equipo);
        },
        nameFilter(value) {
            // If this filter has no value we just skip the entire filter.
            if (!this.dessertFilterValue) {
                return true;
            }

            // Check if the current loop value (The dessert name)
            // partially contains the searched word.
            return value.toLowerCase().includes(this.dessertFilterValue.toLowerCase());
        },

        /**
         * Filter for calories column.
         * @param value Value to be tested.
         * @returns {boolean}
         */
        cargoFilter(value) {

            // If this filter has no value we just skip the entire filter.
            if (!this.caloriesFilterValue) {
                return true;
            }

            // Check if the current loop value (The calories value)
            // equals to the selected value at the <v-select>.
            return value === this.caloriesFilterValue;
        }
    },
}
</script>

