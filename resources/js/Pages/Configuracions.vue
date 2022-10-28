<template>
    <v-app>

        <v-data-table :headers="headers" :items="desserts" item-key="fecha_inicio" :sort-by="['fecha_inicio']"
            :sort-desc="[false]" multi-sort class="elevation-1" :header-props="{ sortByText: 'Ordenar por' }"
            :items-per-page="20" :footer-props="{
                'items-per-page-text': 'Configuraciones por pagina',
                'items-per-page-options': [20, 30, 50, 100, -1], 'items-per-page-all-text': 'Todos'
            
            }">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Configuraciones</v-toolbar-title>

                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" dark class="mb-2" @click="adicionar_temp()">
                        Adicionar configuraciones temporales
                    </v-btn>

                    <v-dialog fullscreen hide-overlay transition="dialog-bottom-transition" v-if="dialog"
                        v-model="dialog">

                        <v-toolbar dark color="primary">
                            <v-btn icon dark @click="close()">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                            <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
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
                                                <!--v-if="editedItem.atencion"-->
                                                <v-row>

                                                    <v-col cols="12" v-if='editedItem.tipo == "permanente"'>
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
                                                                :max="getMeses(12)" :allowed-dates="diasnoValidos"
                                                                no-title scrollable>
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">
                                                                    Cancelar
                                                                </v-btn>
                                                                <v-btn text color="primary"
                                                                    @click="$refs.menu.save(date)">
                                                                    OK
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                    <v-col v-if='editedItem.tipo == "temporal"'>
                                                        <v-col cols="12">
                                                            <v-checkbox v-model="editedItem.atencion"
                                                                label="Se realizara la atencion">
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-checkbox v-model="editedItem.feriado"
                                                                label="Sera feriado">
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-menu ref="menu" v-model="menu"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="date_temp"
                                                                transition="scale-transition" offset-y min-width="auto">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-text-field v-model="date_temp"
                                                                        label="Fecha de Configuracion temporal"
                                                                        prepend-icon="mdi-calendar" readonly
                                                                        v-bind="attrs" v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="date_temp" multiple no-title
                                                                    :min="inicioFecha()" :max="getMeses(12)"
                                                                    :allowed-dates="diasnoValidos" scrollable>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn text color="primary" @click="menu = false">
                                                                        Cancelar
                                                                    </v-btn>
                                                                    <v-btn text color="primary"
                                                                        @click="$refs.menu.save(date_temp)">
                                                                        OK
                                                                    </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-col>
                                                    </v-col>
                                                </v-row>
                                                <v-row>

                                                </v-row>
                                            </v-form>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-btn color="primary" @click="step2">
                                                Continue
                                            </v-btn>
                                            <v-spacer></v-spacer>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
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
                                            <v-container v-if="e1 >= 2">
                                                <sala ref="salas" :id_configuracion="editedItem.id"></sala>
                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" @click="e1 = 3">
                                                Continue
                                            </v-btn>
                                            <v-spacer></v-spacer>
                                            <v-btn color="primary" @click="e1 = 1">
                                                Atras
                                            </v-btn>

                                        </v-card-actions>
                                    </v-card>

                                </v-stepper-content>

                                <v-stepper-content step="3">
                                    <v-card>
                                        <v-card-title>
                                            Se planificara la siguiente configuración:
                                        </v-card-title>
                                        <v-card-text>
                                            <span>ID: {{ editedItem.id }}</span>
                                            <v-divider></v-divider>
                                            <span>Descripcion {{ editedItem.descripcion }}</span>
                                            <v-divider></v-divider>
                                            <span>Para la Fechas: {{ editedItem.fecha_inicio }}</span>
                                            <span v-if="!editedItem.principal"> - {{ editedItem.fecha_final }}</span>
                                        </v-card-text>

                                    </v-card>
                                    <v-card-actions>
                                        <v-btn color="primary" @click="step3()">
                                            Aceptar
                                        </v-btn>

                                        <v-btn color="primary" @click="close()">
                                            Cancel
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" @click="atras3()">
                                            Atras
                                        </v-btn>

                                    </v-card-actions>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>

                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Estas seguro que quieres eliminar</v-card-title>
                            <v-card-text>
                                <span>ID: {{ editedItem.id }}</span>
                                <v-divider></v-divider>
                                <span>Descripcion {{ editedItem.descripcion }}</span>
                                <v-divider></v-divider>
                                <span>Fechas: {{ editedItem.fecha_inicio }}</span>
                                <span v-if="!editedItem.principal"> - {{ editedItem.fecha_final }}</span>


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
                <span v-if="item.fecha_final == '9999-01-01'">
                    Indefinido
                </span>
                <span v-else>
                    <span v-if="item.tipo != 'temporal'">
                        {{ item.fecha_final }}
                    </span>

                </span>

            </template>
            <template v-slot:item.principal="{ item }">
                <v-simple-checkbox v-model="item.principal" disabled></v-simple-checkbox>
            </template>


            <template v-slot:item.actions="{ item }">
                <v-icon v-if="item.principal" small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon v-show="item.atencion" small class="mr-2" @click="consultasItem(item)">
                    mdi-home-circle
                </v-icon>
                <v-icon v-if="item.historial != 0 || item.tipo != 'permanente'" small @click="deleteItem(item)">
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
                    <v-btn color="blue darken-1" text @click="permanente = false">Cancelar</v-btn>
                    <v-btn color="blue darken-1" text @click="permanente = false">Aceptar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-if="edit_consulta" v-model="edit_consulta" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Consulta</v-card-title>
                <v-card-text>

                    <sala ref="solo_salas" :editar_consulta="edit_consulta" :id_configuracion="editedItem.id"></sala>
                </v-card-text>

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
        fechas_no_validas: Array,
    },
    data: () => ({
        permanente: false,
        dialog: false,
        edit_consulta: false,
        dialogDelete: false,
        headers: [
            {
                text: 'Fecha inicio',
                align: 'start',
                value: 'fecha_inicio',

            },
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
            tipo: '',
            atencion: true,
            feriado: false,
        },
        defaultItem: {
            id: '',
            descripcion: '',
            tipo: '',
            atencion: true,
            feriado: false,

        },
        menu: false,
        date: '',
        date_inicio: '',
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

            console.log(item)
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.date_inicio = structuredClone(this.editedItem.fecha_inicio)
            console.log('ss' + this.editedIndex)
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
            this.date_inicio = ''
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

            if (this.editedItem.tipo == 'permanente') {
                console.log(this.editedItem.atencion)
                console.log(this.editedItem)
                if (this.$refs.paso1.validate()) {
                    var res = await axios({
                        method: 'get',
                        url: `/${process.env.MIX_CARPETA}/api/verificar_fecha/` + this.date,

                    }).then(
                        (response) => {
                            console.log(response);
                            this.verificar_fecha(response.data)

                        }, (error) => {
                            console.log(error);
                        }
                    );
                }
            }
            if (this.editedItem.tipo == 'temporal') {
                if (this.$refs.paso1.validate()) {
                    console.log(this.editedItem.atencion)
                    console.log(this.editedItem)

                    var res = await axios({
                        method: 'post',
                        url: `/${process.env.MIX_CARPETA}/api/verificar_fecha`,
                        data: {
                            datos: this.editedItem,
                            fecha: this.date_temp,


                        }
                    }).then(
                        (response) => {
                            console.log(response);
                            this.verificar_fecha(response.data)
                        }, (error) => {
                            console.log(error);
                        }
                    );


                }
            }
        },
        verificar_fecha(resp) {
            if (resp['verificar']) {

                if (this.editedItem.tipo == 'temporal') {
                    this.date_temp.sort()
                    this.editedItem.fecha_inicio = this.date_temp[0]
                    this.editedItem.fecha_final = this.date_temp[this.date_temp.length - 1]
                    //console.log(resp['default'][0].id);
                    this.editedItem.id = resp['default'][0].id


                } else {
                    this.editedItem.fecha_inicio = this.date
                }
                if (this.editedItem.atencion) {
                    this.e1 = 2
                    console.log(this.$refs.salas);
                    setTimeout(() => {
                        this.$refs.salas.editedIndex = -1

                    }, 0)

                } else {
                    this.e1 = 3
                }


            } else {

                let mensaje = "ya existe configuracion para fecha:"
                for (const key in resp['lista_fechas']) {
                    mensaje += "\n" + resp['lista_fechas'][key]
                }
                alert(mensaje)
            }
        },
        async step3() {

            console.log(`/${process.env.MIX_CARPETA}/configuracion2`,)
            let salas = []
            if (this.editedItem.atencion) {
                salas = structuredClone(this.$refs.salas.desserts)

            }
            try {
                var res = await this.axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/configuracion2`,
                    data: {
                        datos: this.editedItem,
                        fecha_temporales: this.date_temp,
                        salas: salas
                    }

                }).then(
                    (response) => {
                        //this.headers = response.data
                        console.log(response.data);
                        this.desserts = response.data
                        this.e1 = 1
                        this.close()

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (error) {

            }

        },
        inicioFecha() {

            //console.log(this.fecha_server, '  ', this.date_inicio)
            let f1 = moment(this.fecha_server).add(1, "d")
            let f2 = moment(this.date_inicio).add(1, 'd')
            if (f1.isBefore(f2)) { //<

                this.minfecha = f2.format("YYYY-MM-DD")
                return this.minfecha
            }
            this.minfecha = f1.format("YYYY-MM-DD")
            return this.minfecha

        },
        adicionar_temp() {
            this.dialog = true
            this.editedItem.tipo = "temporal"
            /*this.$refs.salas.editedIndex = -1
            console.log(this.editedIndex);*/
        },
        getMeses(m) {
            let fecha_final = moment(this.fecha_server).add(m, "M")
            return fecha_final.format("YYYY-MM-DD")

        },
        diasnoValidos(val) {
            //console.log(this.fechas_no_validas)

            var d = new Date(val).getDay();
            for (let i = 0; i < this.fechas_no_validas.length; i++) {
                //console.log(this.fechas_no_validas)
                if (this.fechas_no_validas[i] == val) {
                    var d = new Date(val).getDay();
                    if (d == 5) {

                        let f = moment(val).add(2, "d").format("YYYY-MM-DD")
                        if (!this.fechas_no_validas.includes(f))
                            this.fechas_no_validas.push(f)
                    }
                    if (d == 6) {
                        let f = moment(val).add(1, "d").format("YYYY-MM-DD")
                        if (!this.fechas_no_validas.includes(f))
                            this.fechas_no_validas.push(f)
                    }
                    return false
                }

            }
            if (d == 5) return false;
            if (d == 6) return false;
            return true;
        },
        getSalas(value) {
            Console.log(value)
        },
        consultasItem(item) {
            this.editedItem = item
            this.edit_consulta = true
            this.id_configuracion = item.id


        },
        atras3() {
            if (this.editedItem.atencion) {
                this.e1 = 2
            } else {
                this.e1 = 1
            }
        },
        calcular_horario(editedItem) {
            let horario = []
            if (editedItem.tiempo_apertura == '') {
                return horario = []
            }

            //console.log(this.fin_atencion, this.fin_atencion)
            let tiempo_i = moment(editedItem.tiempo_apertura, 'hh:mm')
            let tiempo_f = moment(editedItem.tiempo_cierre, 'hh:mm')
            //console.log(tiempo_i, tiempo_f)
            horario = []
            let i = 0;
            let tiempo_descanso = true
            if (!tiempo_i.isBefore(tiempo_f)) return horario
            //console.log('calcular_horario', this.editedItem.tiempo_atencion);

            if (editedItem.min_promedio_atencion > 0 && editedItem.min_promedio_atencion != '') {

                while (tiempo_i.isBefore(tiempo_f)) {
                    let op = {}
                    op.hora_inicio = tiempo_i.format('hh:mm')

                    let s = tiempo_i.add(editedItem.min_promedio_atencion, 'minutes')
                    op.hora_final = s.format('hh:mm')
                    op.sala = editedItem.sala
                    horario.push(op);
                    if (tiempo_i.isSameOrAfter(moment(editedItem.tiempo_descanso, 'hh:mm')) && tiempo_descanso == true) {
                        let mensaje = 'hora de descanso sera ' + tiempo_i.format('hh:mm')
                        tiempo_i = tiempo_i.add(30, 'minutes')
                        mensaje += ' - ' + tiempo_i.format('hh:mm')
                        //alert(mensaje)
                        tiempo_descanso = false
                    } else {
                        tiempo_i = s
                        //console.log(tiempo_i, tiempo_f)
                    }

                }
            }
            return horario
        }
    },

}
</script>
    