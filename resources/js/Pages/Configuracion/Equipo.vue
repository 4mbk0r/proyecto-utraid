
<template>
    <v-app>
        <v-card>

            <v-row>

                <v-col cols="4">
                    Seleccion de Equipos
                </v-col>
                <v-col cols="4">
                    <v-btn class="float-right" dense small @click="delete_all">
                        <v-icon color="yello w darken-3">
                            mdi-delete
                        </v-icon>
                        Eliminar Todo
                    </v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn class="float-right" dense small @click="save_all">
                        <v-icon color="yello w darken-3">
                            mdi-save
                        </v-icon>
                        Guardar
                    </v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="6">

                    <v-list two-line>
                        <v-list-item-group v-model="selected_equipo" active-class="pink--text">
                            <template v-for="(item, index) in equipo">
                                <v-list-item :key="item.title" @click="seleccion_equipo(item)">
                                    <template v-slot:default="{ active }">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.descripcion"></v-list-item-title>

                                            <v-list-item-subtitle class="text--primary"
                                                v-text="item.headline"></v-list-item-subtitle>

                                            <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                            <v-icon v-if="item.lista.length >= 1" color="grey lighten-1">
                                                {{ item.lista.length }}
                                            </v-icon>

                                            <v-icon v-else color="yello w darken-3">

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

                                <v-list-item v-if="item.equipo === selected_equipo" :key="item.title">
                                    <template v-slot:default="{ active }">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                            <v-list-item-subtitle class="text--primary"
                                                v-text="item.headline"></v-list-item-subtitle>

                                            <v-list-item-subtitle
                                                v-text="item.ap_paterno + item.ap_materno"></v-list-item-subtitle>
                                            <v-list-item-subtitle v-text="item.cargo"></v-list-item-subtitle>

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
            <v-data-table :headers="headers" :items="eventos" item-key="ci" :search="search" @click:row="addequipo($event)"
                class="elevation-1 pa-6">
                <template v-slot:top>
                    <!-- v-container, v-col and v-row are just for decoration purposes. -->
                    <v-container>
                        <v-row>

                            <v-col cols="6">
                                <v-row class="pa-6">
                                    <!-- Filter for dessert name-->
                                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line
                                        hide-details></v-text-field>

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
        selected_equipo: 0,
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

        eventos() { return this.items },
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
                    //filter: this.esta_en_equipo
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
                    filter: this.esta_en_equipo,
                    show: false,
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
                    url: `/${process.env.MIX_CARPETA}/api/` + "personal_servicio",
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
        seleccion_equipo(dato) {
            console.log(dato.lista);

            return
            //console.log(i);
            this.selected_medico = ''
            this.selected_psicologo = ''
            this.selected_trabajo = ''
            console.log('-------');
            console.log(this.selected_equipo)
            console.log(this.equipo[this.selected_equipo]);

        },
        addequipo(item) {
            console.log(item);
            console.log(this.selected_equipo);
            if (typeof this.selected_equipo == 'undefined' || this.selected_equipo === '') {
                this.selected_medico = ''
                this.selected_psicologo = ''
                this.selected_trabajo = ''
                return

            }
            if (item.equipo >= 0) {
                alert('ya tiene equipo esta en ' + item.equipo);
                return;
            }

            else {
                //alert('ya tiene un  ' + item.cargo + ' en el equipo');
                //return;

                let findx = this.items.findIndex(o => o.cargo === item.cargo && o.equipo == this.selected_equipo)
                if (findx >= 0) {

                    alert('ya tiene un  ' + item.cargo + ' en el equipo');
                    return;
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
            this.selected_equipo = -1
            this.selected_equipo = item.equipo
            var s = structuredClone(this.items)
            this.items = []
            this.items = s

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
            for (const key in this.equipo) {
               this.equipo[key].lista = []
            }
            this.selected_medico = ''
            this.selected_psicologo = ''
            this.selected_trabajo = ''
            this.selected_equipo = 0
        },
        validar_equipo() {
            if (this.equipo.length == 0) {
                alert('no se tiene equipo');
                return false
            }
            for (let index = 0; index < this.equipo.length; index++) {
                if (this.equipo[index].lista.length == 0) {
                    alert('falta elementos en ' + this.equipo[index].equipo)
                    return false
                }

            }
            return true
        },
        async save_all() {

            //this.paso6()
            if (this.validar_equipo()) {
                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/equipo2`,
                    data: {
                        equipo: this.equipo
                    },
                }).then(
                    (response) => {
                        ////console.log('validat');
                        /*
                        console.log('-____--------');
                        console.log(response.data);
                        console.log(resp);*/
                        var r = {
                            resp: true,
                            datos: response.data
                        }
                        //r = JSON.stringify(r)
                        this.$emit('next', r);

                        //console.log('__configuracion ___');
                        //console.log(response.data);
                        //this.close()


                    },
                ).catch((error) => {
                    //console.log(error.response.data.mensaje);

                });
            }
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
            
            try {
                var e  = this.equipo[this.selected_equipo].lista
            } catch (error) {
                return true
            }
            for (const key in e ) {
                if(value == e[key].cargo)
                    return false
            }
            return true
            // If this filter has no value we just skip the entire filter.
            if (!this.caloriesFilterValue) {
                return true;
            }

            // Check if the current loop value (The calories value)
            // equals to the selected value at the <v-select>.
            return value === this.caloriesFilterValue;
        },
        esta_en_equipo(value) {
            if(typeof value == 'undefined') return true
            return !(value >= 0)
        }
    },
}
</script>

