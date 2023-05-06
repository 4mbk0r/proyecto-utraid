<template>
    <v-card>
        <v-dialog v-if="dialog" v-model="dialog" class="text-left" persistent>
            <v-toolbar color="primary" elevation="4" class="justify-space-between">
                <v-btn color="primary" class="mx-2" fab dark right small @click="close">
                    <v-icon>
                        mdi-close
                    </v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="mx-2" fab dark right small @click="atras">
                    <v-icon>
                        mdi-arrow-left-box
                    </v-icon>
                </v-btn>
            </v-toolbar>
            <v-card>
                <v-form ref="configuracion">

                    <v-row>
                        <v-col cols="12" md="4">
                            <v-menu v-model="menu2" :close-on-content-click="false" transition="scale-transition" offset-y
                                max-width="290px" min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="fecha" label="Fecha feriado" persistent-hint
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" required
                                        :rules="[v => !!v || 'Se requiere completar']">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="fecha" 
                                    locale="es"
                                    :min="minfecha()" :max="maxfecha()" no-title
                                    @input="menu2 = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field v-model="descripcion" label="Descripcion" required
                                :rules="[v => !!v || 'Se requiere completar']">
                            </v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-btn class="ma-2" color="secondary" @click="guardar_feriado">
                                Adicionar Feriado
                            </v-btn>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select v-model="selectedYear" :items="yearOptions" label="Select a year"
                                @change="seleccionYear($event)"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-data-table :headers="headers_feriado" :footer-props="{

                                'items-per-page-text': '',
                                'items-per-page-all-text': 'Todos'

                            }" :items="feriado" class="elevation-1">
                                <!--
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Feriados</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="d_hoarario" max-width="500px">
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5"></span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>

                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue darken-1" text @click="close">
                                                    Cancelar
                                                </v-btn>
                                                <v-btn color="blue darken-1" text @click="save">
                                                    Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="dialogDelete" max-width="500px">
                                        <v-card>
                                            <v-card-title class="text-h5">Estas seguro que deseas eliminar?
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue darken-1" text @click="closeDelete">
                                                    Cancelar
                                                </v-btn>
                                                <v-btn color="blue darken-1" text
                                                    @click="deleteItemConfirm">OK
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            -->

                                <template v-slot:item.fecha="{ item }">
                                    <span>{{ cambiar_texto(item.fecha) }}</span>
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <v-icon small class="mr-2" @click="editItem(item)">
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon small @click="deleteItem(item)">
                                        mdi-delete
                                    </v-icon>
                                </template>

                                <template v-slot:no-data>
                                    No hay datos
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>

                </v-form>
            </v-card>

        </v-dialog>
        <v-dialog v-model="dialogEdit" max-width="800px">
            <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                    New Item
                </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-menu v-model="menu6" :close-on-content-click="false" transition="scale-transition"
                                    offset-y max-width="290px" min-width="auto">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="editedItem.fecha" label="Fecha feriado" persistent-hint
                                            prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" required
                                            :rules="[v => !!v || 'Se requiere completar']">
                                        </v-text-field>
                                    </template>
                                    <v-date-picker v-model="editedItem.fecha" :min="minfecha()" :max="maxfecha()" no-title
                                    format = "dd/MM/yyyy"    
                                    @input="menu2 = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field v-model="editedItem.descripcion" label="Descripcion" required
                                    :rules="[v => !!v || 'Se requiere completar']">
                                </v-text-field>
                            </v-col>


                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close2">
                        Cancelar
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="actulizar">
                        Actulizar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">
                    Deseas eliminar el elemento?</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete2">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <MensajesError ref="mensaje_error" :mensaje="texto_error"> </MensajesError>
    </v-card>
</template>

<script>
import axios from 'axios'
import moment from 'moment'

import Salas from '@/Pages/Micomponet/Sala'
import Equipo from '@/Pages/Configuracion/Equipo'
import MensajesError from './MensajesError.vue'
import { emit } from 'process'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    components: {
        Salas,
        Equipo,
        MensajesError
    },
    props: {
        dialog: Boolean,
        item: Object
    },
    data: () => ({
        //dialog: false,
        dialogEdit: false,
        dialogDelete: false,
        editedItem: {},
        date: '',
        menu1: false,
        configuracion: {},
        calendario: {},
        color: '#1976D2FF',
        mask: '',
        menu: false,
        menu2: false,
        menu6: false,
        paso: 1,
        alert: false,
        errors_descripcion: [],
        equipo: [],
        salas: [],
        dates: [],
        arrayEvents: [],
        fechas_vigentes: [],
        fecha: '',
        descripcion: '',
        feriado: [],
        texto_error: '',
        formTitle: '',
        headers_feriado: [

            {
                text: 'Fecha',
                align: 'start',
                sortable: true,
                value: 'fecha',
                format: "dd/MM/yyyy0",
            },
            {
                text: 'Descripcion',
                align: 'start',
                sortable: true,
                value: 'descripcion',
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            },
        ],
        selectedYear: null,
        yearOptions: [],

    }),
    created() {
        console.log('inicio');
        //this.get_fechas();
    },
    mounted() {
        console.log('sdfasdklfjasf');
        const currentYear = new Date().getFullYear();
        const yearEnd = currentYear + 20;
        const startYear = currentYear - 0; // Change this number to adjust the range of years
        for (let year = startYear; yearEnd >= year; year++) {
            this.yearOptions.push(year);
        }
        this.selectedYear = currentYear;
        this.seleccionYear()
    },
    beforeMount() {

    },
    computed: {
        async seleccionYear(){
            var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/api/feria/`+this.selectedYear,
                    
                }).then(
                    (response) => {
                        console.log(response);
                        this.feriado = response.data
                        /*if (response.data.redireccionar) {
                            alert('Se cambio la contraseña correctamente. Se cerra la sesión para verificar que este correctamente. ')
                            window.location.href = `/${process.env.MIX_CARPETA}/login`
                        }

                        this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));*/
                    }
                ).catch(err => {
                    if (err.response.status == 401) {
                        window.location.href = `/${process.env.MIX_CARPETA}/login`

                    }
                    if (err.response.status == 419) {
                        window.location.href = `/${process.env.MIX_CARPETA}/login`

                    }
                    console.log(err)
                    console.log("err->", err.response.data)
                    //this.$alert('Se fallo en cambio.').then(res => this.$err("Cambiar contraseña!"));
                    return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
                });
        },
        computedDateFormattedMomentjs() {
            moment.locale('es');
            this.calendario.fecha_inicio = this.date
            return this.date ? moment(this.date).format('dddd, MMMM Do YYYY') : ''
        },
        swatchStyle() {
            const { color, menu } = this
            return {
                backgroundColor: color,
                cursor: 'pointer',
                height: '30px',
                width: '30px',
                borderRadius: menu ? '50%' : '4px',
                transition: 'border-radius 200ms ease-in-out'
            }
        }
    },
    watch: {
        limpiar_errors() {
            this.errors_descripcion = []
        }
    },

    methods: {
        cambiar_texto(val) {
            moment.locale('es')
            let date = moment(val);
            return (date.format(" Do MMMM"));
        },
        minfecha() {

            ////console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
            
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
            
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            ////console.log(this.item);
            ////console.log(this.item)
            let f = this.$store.getters.gethoy
            return f
            if (moment(f).isSameOrAfter(this.item.fecha_inicio)) {
                return f
            }
            return this.item.fecha_inicio

        },
        maxfecha() {

            ////console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
            
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
            
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            ////console.log(this.item);
            ////console.log(this.item)
            let t_year = moment(this.$store.getters.gethoy).add(3, 'year')
            return t_year.format('dd-mm-yyyy')
            ////console.log(t_year);
            if (t_year.isSameOrAfter(this.item.fecha_final)) {//>
                return this.item.fecha_final
            }
            return t_year.format('yyyy-mm-dd')

        },
        limpiar_errors() {
            this.errors_descripcion = []
        },
        close() {
            //this.dialog = false
            ////console.log(this.date);
            this.$emit('cerrar', false)
        },
        cancelar() {
            this.close()
        },
        atras() {
            this.paso = (this.paso > 1) ? this.paso - 1 : this.paso
        },

        async guardar_feriado() {
            if (this.$refs.configuracion.validate()) {

                let datos = {
                    atencion: 'feriado',
                    fecha: this.fecha,
                    codigo: '01',
                    descripcion: this.descripcion
                }
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/api/fechas_feriados`,
                    data: {
                        feriado: datos
                    },
                }).then(
                    (response) => {
                        console.log(response);
                        this.feriado.push(datos)
                        //this.feriado = response.data

                    }
                ).catch(err => {
                    //this.$refs.mensaje_error.dialog = true
                    console.log(err)
                    console.log("err->", err.response.data)
                    console.log(this.$refs.mensaje_error.dialog);
                    this.$refs.mensaje_error.dialog = true
                    console.log(err.response.data['message'])
                    this.texto_error = err.response.data['message']
                    //console.log("err->", err.response.data)


                    //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
                });

            }

        },
        async get_fechas() {
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/api/fechas_feriados`,
            }).then(
                (response) => {
                    console.log(response);
                    this.feriado = response.data

                }
            ).catch(err => {

                //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
        },
        initialize() {
            console.log(this.desserts);

        },

        async editItem(item) {

            this.editedIndex = 1
            this.editedItem = Object.assign({}, item)
            this.dialogEdit = true
            console.log(this.dialogEdit);
        },

        deleteItem(item) {
            this.editedIndex = 1
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        async deleteItemConfirm() {
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/fechas_delete`,
                data: {
                    fecha: this.editedItem
                }
            }).then(
                (response) => {
                    console.log(response);
                    this.get_fechas()
                    //this.feriado = response.data

                }
            ).catch(err => {

                //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            this.closeDelete2()
        },
        async actulizar() {
            console.log(this.editedItem);
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/fechas_update`,
                data: {
                    fecha: this.editedItem
                }
            }).then(
                (response) => {
                    console.log(response);
                    this.get_fechas()
                    //this.feriado = response.data

                }
            ).catch(err => {

                //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
        },


        close2() {
            this.dialogEdit = false
            /*this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })*/
        },

        closeDelete2() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            this.editedItem = false
        },

    }
}
</script>