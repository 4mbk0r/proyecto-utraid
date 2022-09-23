<template>
    <app-layout>
        <!--<welcome />-->

        <v-card>
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Configuraciones </v-toolbar-title>
            </v-toolbar>
            <v-tabs vertical>
                <v-tab>
                    <v-icon left>
                        mdi-account
                    </v-icon>
                    Feriados
                </v-tab>
                <v-tab>
                    <v-icon left>
                        mdi-lock
                    </v-icon>
                    Atención
                </v-tab>


                <v-tab-item>
                    <v-card>
                        <v-form>
                            <!--
                            <v-row align="center">
                                <v-col cols="12" sm="6">
                                    <v-select v-model="datos.dias" :items="items" attach chips
                                        label="Dias de no atencion" multiple>
                                    </v-select>
                                </v-col>
                            </v-row>
                            -->
                            <v-row align="center">
                                <v-col cols="12">
                                    <v-data-table :headers="headers" :items="desserts" class="elevation-1">
                                        <template v-slot:top>
                                            <v-toolbar flat>
                                                <v-toolbar-title>Feriados</v-toolbar-title>
                                                <v-divider class="mx-4" inset vertical></v-divider>
                                                <v-spacer></v-spacer>
                                                <v-dialog v-model="dialog" max-width="500px">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn color="primary" dark class="mb-2" v-bind="attrs"
                                                            v-on="on">
                                                            Adicionar Fecha
                                                        </v-btn>
                                                    </template>
                                                    <v-card>
                                                        <v-card-title>
                                                            <span class="text-h5">Feriados</span>
                                                        </v-card-title>

                                                        <v-card-text>
                                                            <v-container>
                                                                <v-row>
                                                                    <v-col cols="12">
                                                                        <v-text-field type="date"
                                                                            v-model="editedItem.fecha" label="Fecha">
                                                                        </v-text-field>
                                                                    </v-col>
                                                                    <v-col cols="12">
                                                                        <v-text-field v-model="editedItem.titulo"
                                                                            label="Titulo">
                                                                        </v-text-field>
                                                                    </v-col>


                                                                </v-row>
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
                                                        <v-card-title class="text-h5">Tu quieres eliminar esta fecha?

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

                                        <!--<template v-slot:[`item.actions`]="{ item }">
                                            <v-icon small class="mr-2" @click="editItem(item)">
                                                mdi-pencil
                                            </v-icon>
                                            <v-icon small @click="deleteItem(item)">
                                                mdi-delete
                                            </v-icon>
                                        </template>
                                        -->
                                    </v-data-table>
                                </v-col>
                            </v-row>

                        </v-form>

                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat>
                        <v-form ref="configuracion">
                            <v-row>
                                <v-col cols="12" md="4">

                                    <v-text-field v-model="n_sala" type="number" label="Numero de salas"
                                        :rules="[v => !!v || 'Se requiere completar']">
                                    </v-text-field>

                                </v-col>


                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field v-model="inicio_atencion" label="Inicio de atencion" type="time"
                                        suffix="Inicio de atencion" required
                                        :rules="[v => !!v || 'Se requiere completar']">
                                    </v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field v-model="fin_atencion" @change="Adicionarhoras"
                                        label="Fin de atencion" type="time" suffix="Fin de atencion" required
                                        :rules="[v => !!v || 'Se requiere completar']">
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field v-model="tiempo_atencion" @change="Adicionarhoras" type="number"
                                        label="Tiempo promedio de atencion" suffix="Minutos" required
                                        :rules="[v => !!v || 'Se requiere completar']">
                                    </v-text-field>
                                </v-col>

                            </v-row>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-menu v-model="menu2" :close-on-content-click="false"
                                        transition="scale-transition" offset-y max-width="290px" min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="fecha_config" label="Fecha de la configuracion"
                                                hint="MM/DD/YYYY " persistent-hint prepend-icon="mdi-calendar" readonly
                                                v-bind="attrs" v-on="on" required
                                                :rules="[v => !!v || 'Se requiere completar']">
                                            </v-text-field>
                                        </template>
                                        <v-date-picker v-model="fecha_config" no-title @input="menu2 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-data-table :headers="headers_sala" :footer-props="{
                                     
                                         'items-per-page-text':'',
                                         'items-per-page-all-text':'Todos'
                                                 
                                    }" :items="horario" sort-by="calories" class="elevation-1">
                                        <template v-slot:top>
                                            <v-toolbar flat>
                                                <v-toolbar-title>Horarios de atencion</v-toolbar-title>
                                                <v-divider class="mx-4" inset vertical></v-divider>
                                                <v-spacer></v-spacer>
                                                <v-dialog v-model="dialog" max-width="500px">
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

                                        <template v-slot:no-data>
                                            No hay datos
                                        </template>
                                    </v-data-table>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn class="ma-2" color="secondary" @click="guardar_config">
                                        Guardar
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-tab-item>
                <v-tab-item>

                </v-tab-item>
            </v-tabs>

        </v-card>

        <!--:datos_cita="fechas"-->
        <!--<barrasu/>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import Agenda from '@/Pages/Micomponet/Agenda'
import moment from 'moment'


export default {
    data() {
        return {
            menu2: false,

            fecha_config: '',
            n_sala: "",
            tiempo_atencion: "",
            datos: {
                feriados: {},
                dias: []

            },
            items: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            value: ["dom", "lun", "mar", "mie", "jue", "vie", "sab"],
            dialog: false,
            dialogDelete: false,
            headers: [
                {
                    text: 'Fecha',
                    align: 'start',
                    sortable: true,
                    value: 'fecha',
                },
                {
                    text: 'Titulo',
                    align: 'start',
                    sortable: true,
                    value: 'titulo',
                },
                //{ text: 'Accion', value: 'actions', sortable: false },
            ],
            headers_sala: [

                {
                    text: 'Hora de atencion',
                    align: 'start',
                    sortable: true,
                    value: 'hora_atencion',
                },
                { text: 'Accion', value: 'actions', sortable: false },
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {

            },
            defaultItem: {

            },
            salas: [],
            inicio_atencion: '',
            fin_atencion: '',
            horario: [],
        }
    },
    props: {
        // fechas: Array,
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
        Agenda,
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        menuItems() {
            return this.menu
        },
        async initialize() {
            await this.$store.dispatch('pedirConfig')
            var datos = await this.$store.getters.getConfig.dias
            if (typeof datos == 'undefined') this.datos.dias = ['Domingo', 'Sabado']
            else this.datos.dias = JSON.parse(datos)
            var datos = await this.$store.getters.getConfig.feriados
            if (typeof datos == 'undefined') this.des = {}
            else this.desserts = await JSON.parse(this.$store.getters.getConfig.feriados);
        },
        async guardar_datos() {
            this.datos.feriados = this.desserts;
            this.datos.lugar = 'utraid'
            var res = await axios({
                method: 'put',
                url: 'configs/' + this.datos,
                data: this.datos,

            }).then();
            console.log('-----')
            console.log(res)

        },
        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },
        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
            this.guardar_datos()
        },

        save() {
            if (this.editedIndex > -1) {

                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
            this.guardar_datos()
        },
        crearsalas() {
            console.log(this.n_sala)
            this.salas = [];
            let opciones = {};
            for (let index = 0; index < this.n_sala; index++) {
                opciones.sala = index + 1;
                opciones.hora_atencion = '';
                this.salas.push(opciones);
                opciones = {}
            }
            this.Adicionarhoras()
        },
        Adicionarhoras() {
            if (this.inicio_atencion == '') {
                return
            }
            console.log(this.fin_atencion, this.fin_atencion)
            let tiempo_i = moment(this.inicio_atencion, 'hh:mm')
            let tiempo_f = moment(this.fin_atencion, 'hh:mm')
            console.log(tiempo_i, tiempo_f)

            this.horario = []
            let i = 0;
            if (!tiempo_i.isBefore(tiempo_f)) return
            if (this.tiempo_atencion > 0 && this.tiempo_atencion != '') {
                while (tiempo_i.isBefore(tiempo_f)) {
                    let op = {}
                    op.hora_atencion = tiempo_i.format('hh:mm')
                    this.horario.push(op);
                    let s = tiempo_i.add(this.tiempo_atencion, 'minutes')
                    tiempo_i = s
                    console.log(tiempo_i, tiempo_f)
                }
            }

        },
        async guardar_config() {
            this.$refs.configuracion.validate()

            if (this.$refs.configuracion.validate()) {
                let datos = {}
                datos.n_sala = this.n_sala
                datos.inicio_atencion = this.inicio_atencion
                datos.fin_atencion = this.fin_atencion
                datos.fecha_config = this.fecha_config
                var res = await axios({
                    method: 'post',
                    url: 'configurageneral/',
                    data: {
                        datos: datos,
                    }
                }).then();
                console.log(res);
            }

        }
    },
    created() {
        this.initialize()

    },



}
</script>
