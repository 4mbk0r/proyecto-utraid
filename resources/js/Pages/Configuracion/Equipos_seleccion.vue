
<template>
    <v-dialog  v-model="dialogVisible" fullscreen :scrim="false" transition="dialog-bottom-transition">
        <v-toolbar dark color="primary">
            <v-btn icon dark @click="cerrar()">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Crear equipo </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn variant="text" @click="save()">
                    Crear
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-card>

            <v-card-title class="text-h5">{{ this.datos.descripcion }}</v-card-title>

            <v-card-text>
                <v-form ref="equipo">
                    <v-row>
                        <v-col>
                            <v-text-field v-model="nombre_equipo" label="Nombre Equipo"
                            :rules="nameRules"
                            required>
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="6">
                            <v-data-table :headers="headers" :items="item_equipo" item-key="ci" :search="search"
                                :fixed-header="true" :hide="['sala']" @click:row="addequipo($event)"
                                class="elevation-1 pa-6">
                                <template v-slot:[`header.sala`]="{ header }">
                                    <template v-if="header.text !== 'Sala'">
                                        {{ header.text }}
                                    </template>
                                </template>
                            </v-data-table>
                        </v-col>

                        <v-col cols="6">
                            <v-data-table :headers="headers2" :items="item_equipo" item-key="ci" :search="search"
                                @click:row="addequipo($event)" class="elevation-1 pa-6">
                            </v-data-table>
                        </v-col>
                    </v-row>
                </v-form>

            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="cerrar">Cancelar</v-btn>
                <v-btn color="blue darken-1" text @click="save">Aceptar</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        //dialog: Boolean,
        //mensaje: Object,
        datos: Object,
        item_equipo_prop: Array,
        dialogVisible: Boolean,

        //editgrupo: Boolean,
        //equipo: Array,
        //cargos: Array,
    },
    created() {
        //this.pedir_datos();

    },
    data: () => ({

        nombre_equipo: '',
        item_equipo: [],
        equipo_seleccion: [],
        selected_medico: '',
        selected_psicologo: '',
        selected_trabajo: '',
        selected: [],
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

        lista_equipo: [],
        show_lista: true,
        equipo: []
        // Table data.


    }),
    mounted() {
        console.log('dsf')
        if (this.dialogVisible) {

            this.item_equipo = this.item_equipo_prop
        }
        //console.log('sssss', this.item);
        /*if(this.cargos.length > 0 ){
            for (const key in this.cargos) {
                if (Object.hasOwnProperty.call(this.cargos, key)) {
                    //const element = this.cargos[key];
                    this.caloriesList.push(this.cargos[key])        
                }
            }
            
        }*/

    },
    watch: {
        item_equipo_prop(nuevoValor, viejoValor) {
            /*
            console.log('el valor cambia');
            console.log(nuevoValor)
            console.log(viejoValor)
            */
            this.item_equipo = nuevoValor
        },
        datos(nuevo, anterior) {
            this.equipo_seleccion = this.filtrarPorSala(this.item_equipo, nuevo.descripcion)
            if(this.equipo_seleccion.length > 0){
                
                this.nombre_equipo = this.equipo_seleccion[0].equipo
            }else{
                
                this.nombre_equipo = ''
            }

        },
        nombre_equipo(nuevo, anterior) {
            //console.log(this.equipo_seleccion)
            for (const key in this.equipo_seleccion) {
                this.equipo_seleccion[key].equipo = nuevo
            }
        }

    },
    components: {
        AppLayout
    },
    computed: {
        nameRules() {
            return [
                v => !!v || 'El nombre es requerido',
            ];
        },

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
                    value: 'nombres',
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
                    text: 'Sala',
                    value: 'sala',
                    filter: this.esta_en_equipo,
                    show: false,
                    //filter: this.caloriesFilter,
                },

            ]
        },
        headers2() {
            return [
                {
                    text: 'Nombres',
                    align: 'left',
                    //sortable: false,
                    value: 'nombres',
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
                    filter: this.cargoFilter2
                    //filter: this.caloriesFilter,
                },

                {
                    text: 'Sala',
                    value: 'sala',
                    filter: this.esta_en_equipo2,

                    //filter: this.caloriesFilter,
                },


            ]
        },
    },
    methods: {
        cerrar() {
            this.$emit("eventoCerrarEditgrupo", false);
        },
        save() {
            if (this.$refs.equipo.validate()){
                //dialogVisible = false
                
                var respuesta = {
                    dialog: false,
                    item_equipo: this.item_equipo
                    
                }
                this.$emit("eventoCrearEditgrupo", respuesta);
            }
                
            
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
        isEmpty(value) {
            return value === undefined || value === null || value === '' || (typeof value === 'object' && Object.keys(value).length === 0);
        },
        addequipo(item) {
            if (this.isEmpty(item.sala)) {
                console.log(this.datos.descripcion)
                item.sala = this.datos.descripcion
                item.equipo = this.nombre_equipo
                var s = structuredClone(this.item_equipo)
                this.item_equipo = []
                this.item_equipo = s
                this.equipo_seleccion = this.filtrarPorSala(this.item_equipo, this.datos.descripcion)
                return

            } else {
                item.sala = undefined
                item.equipo = this.nombre_equipo
                var s = structuredClone(this.item_equipo)
                this.item_equipo = []
                this.item_equipo = s
                this.equipo_seleccion = this.filtrarPorSala(this.item_equipo, this.datos.descripcion)

            }
            // console.log(item)


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

        filtrarPorSala(array, salaElegido) {
            return array.filter((empleado) => empleado.sala === salaElegido);
        },
        cargoFilter(value) {


            for (const objeto of this.equipo_seleccion) {
                if (objeto.cargo === value) {
                    return false
                }
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
        cargoFilter2(value) {
            try {
                var e = this.equipo[this.selected_equipo].lista
            } catch (error) {
                return true
            }
            for (const key in e) {
                if (value == e[key].cargo)
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
            if (typeof value == 'undefined') return true
            return false
        },
        esta_en_equipo2(value) {
            if (typeof value != 'undefined' && value == this.datos.descripcion) return true

            return false
        }
    },
}
</script>

