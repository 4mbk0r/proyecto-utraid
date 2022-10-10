<template>
    <v-app>

        <v-data-table :headers="headers" :items="desserts" sort-by="calories" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Configuraciones</v-toolbar-title>

                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" dark class="mb-2" @click="dialog=true">
                        Adicionar configuraciones temporales
                    </v-btn>

                    <v-dialog v-if="dialog" v-model="dialog" max-width="800px">


                        <v-stepper v-model="e1">
                            <v-stepper-header>
                                <v-stepper-step :complete="e1 > 1" step="1">
                                    Paso 1
                                </v-stepper-step>

                                <v-divider></v-divider>

                                <v-stepper-step :complete="e1 > 2" step="2">
                                    Paso 2
                                </v-stepper-step>

                                <v-divider></v-divider>

                                <v-stepper-step step="3">
                                    Paso 3
                                </v-stepper-step>
                            </v-stepper-header>

                            <v-stepper-items>
                                <v-stepper-content step="1">
                                    <v-card>

                                        <v-card-title>
                                            <span class="text-h5"> {{ formTitle }}</span>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-form ref="paso1">
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field v-model="editedItem.descripcion"
                                                            label="Descripcion" required
                                                            :rules="[v => !!v || 'Se requiere completar']">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row v-if="editedItem.atencion">

                                                    <v-col cols="12" v-if='editedItem.tipo=="permanente"'>
                                                        <v-menu ref="menu" v-model="menu"
                                                            :close-on-content-click="false" :return-value.sync="date"
                                                            transition="scale-transition" offset-y min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="date" label="Fecha de Vigencia"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                    v-on="on"
                                                                    :rules="[v => !!v || 'Se requiere completar']">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="date" :min="inicioFecha()"
                                                                :max="getMeses(3)" :allowed-dates="diasnoValidos"
                                                                no-title scrollable>
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn text color="primary"
                                                                    @click="$refs.menu.save(date)">
                                                                    OK
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                    <v-col v-if='editedItem.tipo=="temporal"'>
                                                        <v-col cols="12">
                                                            <v-checkbox v-model="editedItem.atencion"
                                                                label="Se realiara la atencion">
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-menu ref="menu" v-model="menu"
                                                            :close-on-content-click="false"
                                                            :return-value.sync="date_temp" transition="scale-transition"
                                                            offset-y min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="date_temp"
                                                                    label="Fecha de Configuracion temporal"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                    v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="date_temp" multiple no-title
                                                                scrollable>
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn text color="primary"
                                                                    @click="$refs.menu.save(date_temp)">
                                                                    OK
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                </v-row>
                                                <v-row>

                                                </v-row>
                                            </v-form>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" @click="step2">
                                                Continue
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>


                                </v-stepper-content>

                                <v-stepper-content step="2">
                                    <v-card>

                                        <v-card-title>
                                            <span class="text-h5"> {{ formTitle }}</span>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-container>
                                                <sala ref="salas" :id_configuracion="editedItem.id"></sala>
                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-btn color="primary" @click="e1 = 1">
                                                Atras
                                            </v-btn>
                                            <v-spacer></v-spacer>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" @click="step3()">
                                                Continue
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>


                                </v-stepper-content>

                                <v-stepper-content step="3">
                                    <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

                                    <v-btn color="primary" @click="e1 = 1">
                                        Continue
                                    </v-btn>

                                    <v-btn text>
                                        Cancel
                                    </v-btn>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>

                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Estas seguro que quieres eliminar</v-card-title>
                            <v-card-text>
                                <span>ID: {{editedItem.id}}</span>
                                <v-divider></v-divider>
                                <span>Descripcion {{editedItem.descripcion}}</span>
                                <v-divider></v-divider>
                                <span>Fechas: {{editedItem.fecha_inicio}}</span>
                                <span v-if="!editedItem.principal"> - {{editedItem.fecha_final}}</span>


                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">Aceptar</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>

            <template v-slot:item.atencion="{ item }">
                <v-simple-checkbox v-model="item.atencion" disabled></v-simple-checkbox>
            </template>
            <template v-slot:item.fecha_final="{ item }">
                <span v-if="item.fecha_final=='9999-01-01'">
                    Eterno
                </span>
                <span v-else>
                    {{item.fecha_final}}
                </span>

            </template>
            <template v-slot:item.principal="{ item }">
                <v-simple-checkbox v-model="item.principal" disabled></v-simple-checkbox>
            </template>


            <template v-slot:item.actions="{ item }">
                <v-icon v-if="item.principal" small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon  small class="mr-2" @click="consultasItem(item)">
                    mdi-phone
                </v-icon>
                <v-icon v-if="item.historial!=0" small @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <span>No se tiene elementos </span>
            </template>
        </v-data-table>
        <v-dialog v-model="permanente" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Estas seguro que quieres eliminar</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="permanente=false">Cancelar</v-btn>
                    <v-btn color="blue darken-1" text @click="permanente=false">Aceptar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-if="edit_consulta" v-model="edit_consulta" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Consulta</v-card-title>
                <v-card-text>
                    <sala ref="solo_salas" :id_configuracion="editedItem.id"></sala>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="edit_consulta=false">Cancelar</v-btn>
                    <v-btn color="blue darken-1" text @click="edit_consulta=false">Aceptar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>


    </v-app>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Sala from '@/Pages/Micomponet/Sala'
import moment from 'moment'

export default {
    components: {
        Sala
    },
    props: {
        configuracion: Array,
        fecha_server: '',
    },
    data: () => ({
        permanente: false,
        dialog: false,
        edit_consulta: false,
        dialogDelete: false,
        headers: [
            {
                text: 'ID',
                align: 'start',
                sortable: true,
                value: 'id',
            },
            { text: 'Fecha inicio', value: 'fecha_inicio' },
            { text: 'Fecha final', value: 'fecha_final' },

            { text: 'Descripcion', value: 'descripcion' },
            { text: 'Principal', value: 'principal' },
            { text: '', value: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            id: '',
            descripcion: '',
        },
        defaultItem: {
            id: '',
            descripcion: '',

        },
        menu: false,
        date: '',
        date_temp: [],
        minfecha: '',
        e1: 1,
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Nueva Configuracion' : 'Editar Configuracion'
        },
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    created() {
        this.initialize()
    },
    methods: {
        initialize() {
            this.desserts = this.configuracion
            let date = new Date(this.fecha_server)
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
        async deleteItemConfirm() {
            var res = await axios({
                method: 'delete',
                url: '/main/configuracion2/' + this.editedItem.id,
            }).then(
                (response) => {
                    console.log(response);
                    this.desserts = response.data
                    //this.verificar_fecha(response.data)
                }, (error) => {
                    console.log(error);
                }
            );
            //this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },
        close() {
            this.dialog = false
            this.e1 = 1
            this.editedItem = {}
            this.date = ''
            this.date_temp = []
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
        },
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
        async next_sala() {
            if (this.editedIndex === -1)
                return;

        },
        step1() {

            this.e1 = 1
        },
        async step2() {


            if (this.$refs.paso1.validate()) {
                var res = await axios({
                    method: 'get',
                    url: '/main/api/verificar_fecha/' + this.date,

                }).then(
                    (response) => {
                        console.log(response);
                        this.verificar_fecha(response.data)
                    }, (error) => {
                        console.log(error);
                    }
                );


            }
        },
        verificar_fecha(resp) {
            if (resp['verificar']) {
                this.editedItem.fecha_inicio = this.date
                this.e1 = 2
            } else {

                let mensaje = "ya existe configuracion para fecha:"
                for (const key in resp['lista_fechas']) {
                    mensaje += "\n" + resp['lista_fechas'][key].fecha
                }
                alert(mensaje)
            }
        },
        async step3() {
            console.log(`/${process.env.MIX_CARPETA}/configuracion2`,)
            let salas = structuredClone(this.$refs.salas.desserts)
            var res = await this.axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/configuracion2`,
                data: {
                    datos: this.editedItem,
                    salas: salas
                }

            }).then(
                (response) => {
                    //this.headers = response.data
                    console.log(response.data);
                    this.desserts = response.data

                }, (error) => {
                    console.log(error);
                }
            );
            this.e1 = 3;
        },
        inicioFecha() {
            let f1 = moment(this.fecha_server).add(1, "d")
            let f2 = moment(this.editedItem.fecha_inicio).add(1, 'd')
            if (f1.isBefore(f2)) { //<
                this.minfecha = f2.format("YYYY-MM-DD")
                return this.minfecha
            }
            this.minfecha = f1.format("YYYY-MM-DD")
            return this.minfecha

        },
        getMeses(m) {
            let fecha_final = moment(this.fecha_server).add(m, "M")
            return fecha_final.format("YYYY-MM-DD")

        },
        diasnoValidos(val) {
            var d = new Date(val).getDay();
            if (d == 5) return false;
            if (d == 6) return false;
            return true;
        },
        getSalas(value) {
            Console.log(value)
        },
        consultasItem(item)
        {
            edit_consulta=true
        }
    },

}
</script>
    