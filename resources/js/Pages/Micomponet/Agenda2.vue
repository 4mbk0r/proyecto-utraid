<template>
    <v-app>

        <v-row class="fill-height">

            <v-col>
                <v-sheet height="64">
                    <v-toolbar flat>
                        <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                            Hoy
                        </v-btn>
                        <v-btn fab text small color="grey darken-2" @click="prev">
                            <v-icon small>
                                mdi-chevron-left
                            </v-icon>
                        </v-btn>
                        <v-btn fab text small color="grey darken-2" @click="next">
                            <v-icon small>
                                mdi-chevron-right
                            </v-icon>
                        </v-btn>
                        <v-toolbar-title v-if="$refs.calendar">
                            {{ $refs.calendar.title }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-menu bottom right>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>
                                        mdi-menu-down
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="changeType('category2')">
                                    <v-list-item-title>Atencion</v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="changeType('category')">
                                    <v-list-item-title>Citas</v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="changeType('month')">
                                    <v-list-item-title>Mes</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>
                </v-sheet>
                <v-sheet>
                    <v-calendar v-if="estado == 'calendario'" ref="calendar" v-model="fecha_calendario" :type="type"
                        color="error" :events="events" :categories="categories" :event-color="getEventColor"
                        event-overlap-mode="stack" @click:event="showEvent" @click:more="viewDay" @click:date="viewDay"
                        @change="updateRange" :max-days=5 weekdays="1, 2, 3, 4, 5" :weekday-format="getDay"
                        :first-interval=7 :interval-minutes=60 :interval-count=12>
                        <template #day-body="{ date, category }">
                            <div class="v-current-time" :class="{ first: true }" :style="{ top: nowY }">
                            </div>
                        </template>
                    </v-calendar>
                    <v-calendar v-if="estado == 'cita'" ref="calendar" v-model="fecha_calendario" color="error"
                        event-overlap-mode="stack" type="category" category-show-all :categories="categories"
                        :events="events" :event-color="getEventColor" :weekday-format="getDay" @click:event="showEvent"
                        :interval-minutes=60 :first-interval=7 :interval-count=14>
                        <template #day-body="{ date, category }">
                            <div class="v-current-time" :class="{ first: true }" :style="{ top: nowY }">
                            </div>
                        </template>
                    </v-calendar>

                    <v-calendar v-if="estado == 'atencion'" ref="calendar" v-model="fecha_calendario" color="error"
                        type="category" category-show-all :categories="categories" :events="events"
                        event-overlap-mode="stack" :event-color="getEventColor" :weekday-format="getDay"
                        @click:event="showEvent" :interval-minutes=60 :first-interval=7 :interval-count=14></v-calendar>
                    <v-menu v-if="selectedOpen" v-model="selectedOpen" :close-on-content-click="false"
                        :activator="selectedElement" offset-x>
                        <v-card min-width="350px" flat>
                            <v-toolbar :color="selectedEvent.color">

                                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn
                                    v-if="comparaFechas() && get_datos_ficha(selectedEvent) != null && !getvalores(selectedEvent.fichas, 'id_designado')"
                                    @click="cambiar_ficha($event)" icon>
                                    <v-icon>mdi-account-convert</v-icon>
                                </v-btn>
                                <v-btn
                                    v-if="comparaFechas() && get_datos_ficha(selectedEvent) != null && !getvalores(selectedEvent.fichas, 'id_designado')"
                                    @click="eliminar_ficha($event)" icon>
                                    <v-icon>mdi-account-remove-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    v-if="comparaFechas() && get_datos_ficha(selectedEvent) != null && getvalores(selectedEvent.fichas, 'id_designado')"
                                    @click="eliminar_atender($event)" icon>
                                    <v-icon>mdi-home-remove-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    v-if="comparaFechas() && get_datos_ficha(selectedEvent) != null && !getvalores(selectedEvent.fichas, 'id_designado')"
                                    @click="imprimir($event)" icon>
                                    <v-icon>mdi-printer</v-icon>
                                </v-btn>
                            </v-toolbar>

                            <v-card-text v-if="get_datos_ficha(selectedEvent) != null">
                                <V-row no-gutters>
                                    <v-col>
                                        CI:

                                    </v-col>
                                    <v-col>
                                        {{ valores(selectedEvent.fichas, 'ci') }}
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col>
                                        <span class="v-btn--large"> Nombre Completo:</span>
                                    </v-col>
                                    <v-col>
                                        {{ valores(selectedEvent.fichas, 'nombres') }}
                                        {{ valores(selectedEvent.fichas, 'ap_paterno') }}
                                        {{ valores(selectedEvent.fichas, 'ap_materno') }}

                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col>
                                        <span> Fecha:</span>
                                    </v-col>
                                    <v-col>
                                        {{ valores(selectedEvent.fichas, 'fecha') }}
                                    </v-col>
                                </v-row>

                                <v-form v-if="today == $store.getters.getfecha_server" ref="seleccion_equipo">
                                    <v-row no-gutters>
                                        <v-col>
                                            <v-select v-model="selectequipo" persistent-placeholder
                                                placeholder="No se tiene datos" :items="equipos"
                                                :item-text="item => get_nombre_equipo(item)"
                                                :item-value="item => select_nombre_equipo(item)" :rules="selecionRules"
                                                label="Seleccione equipo que atendera" required>
                                            </v-select>
                                            <v-btn color="success" v-if="!getvalores(selectedEvent.fichas, 'id_designado')"
                                                @click="save_atender">
                                                Guardar
                                            </v-btn>

                                        </v-col>

                                    </v-row>
                                    <V-row>
                                        <v-col>
                                            <v-data-table :headers="encabezado" :items="selectequipo.lista"
                                                hide-default-footer disable-pagination>
                                                <template v-slot:no-results>
                                                    <span>No existen datos</span>
                                                </template>
                                            </v-data-table>
                                        </v-col>
                                    </V-row>
                                </v-form>
                                <v-form v-el se ref="seleccion_equipo">
                                    <v-row v-if="equipos.length > 0" no-gutters>
                                        <v-col>
                                            <v-select v-model="selectequipo" persistent-placeholder
                                                placeholder="No se tiene datos" :items="equipos"
                                                :item-text="item => get_nombre_equipo(item)"
                                                :item-value="item => select_nombre_equipo(item)" :rules="selecionRules"
                                                label="Seleccione equipo que atendera" required>
                                            </v-select>
                                            <v-btn color="success" v-if="!getvalores(selectedEvent.fichas, 'id_designado')"
                                                @click="save_atender">
                                                Guardar
                                            </v-btn>

                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-data-table :headers="encabezado" :items="selectequipo.lista"
                                                hide-default-footer disable-pagination>
                                                <template v-slot:no-results>
                                                    <span>No existen datos</span>
                                                </template>
                                            </v-data-table>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>
                            <v-card-text v-else>

                                <v-btn v-if="fecha_calendario >= $store.getters.getfecha_server" class="ma-2"
                                    color="primary" @click="open_agenda()">
                                    Dar cita
                                    <v-icon end icon> mdi-pencil</v-icon>
                                </v-btn>


                            </v-card-text>

                            <v-card-actions>
                                <v-btn text color="secondary" @click="selectedOpen = false">
                                    Cancelar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-menu>
                </v-sheet>

            </v-col>

        </v-row>
        <v-dialog v-model="dialog_equipo" fullscreen :scrim="false" transition="dialog-bottom-transition">

            <v-card>
                <v-toolbar dark color="black">
                    <v-btn icon dark @click="dialog_equipo = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Configuracion Atencion</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="dialog_equipo = false">
                            Guardar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-list three-line subheader>
                    <v-subheader>Configuracon Sala</v-subheader>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>{{ selectedEvent.category }}</v-list-item-title>
                            <!--<v-list-item-subtitle>{{ selectedEvent }}</v-list-item-subtitle>-->
                        </v-list-item-content>
                    </v-list-item>
                    <v-subheader>Equipo
                        <v-icon aria-hidden="false" @click="ver_equipo = !ver_equipo">
                            mdi-eye-outline
                        </v-icon>


                    </v-subheader>

                    <v-list-item v-if="ver_equipo">
                        <v-list-item-content>
                            <v-list-item-subtitle>Integrantes:</v-list-item-subtitle>
                            <mequipo v-if="dialog_equipo" :fecha="fecha_calendario" :datos="selectedEvent.consultorio">
                            </mequipo>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list>
                    <v-subheader>Viaje</v-subheader>
                    <viaje v-if="dialog_equipo" :fecha="fecha_calendario" :datos="selectedEvent.consultorio"></viaje>
                    <!--{{ selectedEvent }}-->
                    <!--<v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="notifications"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Notifications</v-list-item-title>
                            <v-list-item-subtitle>Notify me about updates to apps or games that I
                                downloaded</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="sound"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Sound</v-list-item-title>
                            <v-list-item-subtitle>Auto-update apps at any time. Data charges may
                                apply</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="widgets"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Auto-add widgets</v-list-item-title>
                            <v-list-item-subtitle>Automatically add home screen widgets</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                -->
                </v-list>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog_imprimir" fullscreen :scrim="false" transition="dialog-bottom-transition">

            <v-card>
                <v-toolbar dark color="black">
                    <v-btn icon dark @click="dialog_imprimir = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Imprimir</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="dialog_equipo = false">
                            Guardar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <imprimir v-if="dialog_imprimir" ref="print">
                </imprimir>
                <!--<v-list three-line subheader>
                    <v-subheader>Imprimir</v-subheader>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>{{ selectedEvent.category }}</v-list-item-title>
                            <!--<v-list-item-subtitle>{{ selectedEvent }}</v-list-item-subtitle>-->
                <!--</v-list-item-content>
                    </v-list-item>
                    <v-subheader>Equipo
                        <v-icon aria-hidden="false" @click="ver_equipo = !ver_equipo">
                            mdi-eye-outline
                        </v-icon>


                    </v-subheader>

                    <v-list-item v-if="ver_equipo">
                        <v-list-item-content>
                            <v-list-item-subtitle>Integrantes:</v-list-item-subtitle>
                            <imprimir v-if="dialog_imprimir" ref="imprimir" >
                            </imprimir>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>-->
                <v-divider></v-divider>
                <!--<v-list>
                    <v-subheader>Viaje</v-subheader>
                    <viaje v-if="dialog_equipo" :fecha="fecha_calendario" :datos="selectedEvent.consultorio"></viaje>
                    {{ selectedEvent }}-->
                <!--<v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="notifications"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Notifications</v-list-item-title>
                            <v-list-item-subtitle>Notify me about updates to apps or games that I
                                downloaded</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="sound"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Sound</v-list-item-title>
                            <v-list-item-subtitle>Auto-update apps at any time. Data charges may
                                apply</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="widgets"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Auto-add widgets</v-list-item-title>
                            <v-list-item-subtitle>Automatically add home screen widgets</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
               
                </v-list> -->
            </v-card>
        </v-dialog>
        <datos v-if="dialog_persona" @pedir='actualizador' ref="dato">
        </datos>
        <atencion v-if="estado == 'atencion'" ref="atender" :equipo="lista_equipo"></atencion>


    </v-app>
</template>

<script>
import moment from 'moment'
import buscar from '@/Pages/Micomponet/Buscar'
import datos from '@/Pages/Micomponet/Datospersonales'
import atencion from '@/Pages/Micomponet/Atencion'
import imprimir from '@/Pages/Micomponet/imprimir'

import mequipo from '@/Pages/Configuracion/seleccionequipo'

import viaje from '@/Pages/Configuracion/viaje'



export default {
    components: {
        buscar,
        datos,
        atencion,
        mequipo,
        viaje,
        imprimir
    },
    data: () => ({

        value: '',
        ready: false,
        dialog_imprimir: false,
        dialog_persona: false,
        dialog_equipo: false,
        today: new Date(),
        dialog: false,
        estado: 'calendario',
        fecha_calendario: new Date().toISOString().substr(0, 10),
        type: 'month',
        typeToLabel: {
            month: 'Mes',
            week: 'Semana',
            day: 'Category',
            'category': 'Citas',
            'category2': 'Atencion'
        },
        ver_equipo: false,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Reunion', 'Cita', 'Viaje'],
        categories: [],
        fecha_min: '',
        lista_equipo: [],
        salas: [],
        selectequipo: '',
        nombreRules: [
            (v) => !!v || "Dato requerido",

            //v => v.length <= 10 || 'Name must be less than 10 characters',
        ],
        equipos: [],
        selectequipo: 'Equipo 1',
        encabezado: [
            {
                text: "Nombres",
                align: "start",
                value: "nombres",
            },
            {
                text: "Apellido Paterno",
                value: "ap_paterno",
            },
            {
                text: "Apellido Materno",
                value: "ap_materno",
            },
            {
                text: "Cargo",
                value: "cargo",
            },

        ],
        selecionRules: [
            v => !!v || "Se requiere seleccion"],
        cambiar_datos: {},
        cambio_ficha: false
    }),
    created() {


    },
    updated() {

    },
    mounted() {

        this.$refs.calendar.checkChange()
        console.log(this.$root.$refs);

        //console.log();
    },
    computed: {
        cal() {
            return this.ready ? this.$refs.calendar : null
        },
        nowY() {
            console.log('sss');
            console.log(this.cal.times.now);
            return this.cal ? this.cal.timeToY(this.cal.times.now) + 'px' : '-10px'
        }
    },
    methods: {
        imprimir() {
            // Abrir la pestaña principal
            //this.$store.commit('update_imprimir', this.selectedEvent.fichas);
            //console.log(this.selectedEvent.fichas);
            //this.$store.commit('update_imprimir', this.selectedEvent.fichas)
            this.$store.dispatch('guardar_imprimir',this.selectedEvent.fichas);
            localStorage.setItem("usuario",  JSON.stringify(this.selectedEvent.fichas));
            setTimeout(() => {
                
            }, 2);

            let mainTab = window.open(`/${process.env.MIX_CARPETA}/imprimir`, '_blank');
            // Esperar a que la pestaña principal esté lista
            /*mainTab.addEventListener('load', function () {
                // Abrir la mini pestaña en la pestaña principal
                let miniTab = mainTab.open('http://localhost/main/imprimir', 'miniTab', 'width=400,height=300');

                // Comunicar entre las dos pestañas
                window.addEventListener('message', function (event) {
                    if (event.origin !== 'https://www.ejemplo.com') return; // verificar el origen del mensaje
                    console.log('Mensaje recibido:', event.data); // manejar el mensaje recibido
                });

                miniTab.postMessage('Hola, pestaña principal!', 'http://localhost/main/imprimir'); // enviar un mensaje a la mini pestaña
            });*¨/




            /*this.dialog_imprimir = true;
            setTimeout(() => {

            }, 1);
            this.$refs.print.cita = this.selectedEvent.fichas;
            */

            //console.log(this.selectedEvent.fichas);
        },
        
        comparaFechas() {
            const fechaActual = new Date(this.$store.getters.getfecha_server + 'T00:00:00');
            const fechaCa = new Date(this.fecha_calendario + 'T00:00:00');
            /*console.log(this.$store.getters.getfecha_server);
            console.log(fechaActual,'<',fechaCa);*/
            //console.log(fechaActual < fechaCa);
            return fechaActual <= fechaCa;
        },
        async eliminar_atender($e) {
            let f = this.selectedEvent.fichas.id_ficha
            var res = await axios({
                method: 'delete',
                url: `/${process.env.MIX_CARPETA}/atender/` + f,
            }).then(
                (response) => {
                    console.log(response);
                    this.pedir_datos()
                    this.selectedOpen = false
                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

            //console.log(this.selectedEvent);
        },
        actualizador(fecha) {
            console.log('.+.+.+.+')
            //this.fecha_calendario =
            console.log(this.fecha_calendario)
            this.pedir_datos(this.fecha_calendario)
        },
        async changeType(nombre) {
            this.ready = false
            if (nombre == 'category2') {
                this.estado = 'atencion'
                this.type = 'category'
                //this.categories = ['Doctor 1', 'Doctor 2']
                this.pedir_doctores(this.fecha_calendario)
                //this.pedir_datos(this.fecha_calendario)
                return;
            }
            if (nombre == 'category') {
                this.estado = 'cita'
                console.log(this.$refs)

                this.type = nombre
                //this.categories = ['Salas 1', 'Doctor 2']
                this.pedir_datos(this.fecha_calendario)


            } else {
                this.estado = 'calendario'
                this.type = nombre

            }
        },
        viewDay({ date }) {
            this.fecha_calendario = date
            //alert(date)
            this.estado = ''
            this.type = 'category'
            this.changeType(this.type)
            /*//this.pedir_datos(date);
            this.ready = true
            this.scrollToTime()
            this.updateTime()*/

        },
        async pedir_doctores(date) {

            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/api/lista_equipo/` + date,
                }).then(
                    (response) => {
                        console.log(response);
                        let start = moment(`${this.fecha_calendario}T00:00:00`),
                            intervalos = 1440 / 45;
                        let list_intervalo = []
                        for (let i = 0; i < intervalos; i++) {
                            //console.log(start.format('hh:mm A'))
                            list_intervalo.push(start.add(45, 'm').format('hh:mm A'))

                        }
                        this.categories = [];
                        let events = [];
                        this.events = [];
                        let start2 = new Date(this.fecha_calendario + 'T01:01:00-04:00')
                        let end = new Date(this.fecha_calendario + 'T21:50:00-04:00')
                        let fecha_server = moment(this.$store.getters.getfecha_server + 'T00:00:00-04:00')
                        this.fecha_min = fecha_server.format('YYYY-MM-DD')
                        let salas = response.data['equipo_lista']
                        let fichas = response.data['fichas']
                        console.log('-------------------');
                        console.log(fichas);
                        for (const key in salas) {
                            //console.log(start);
                            //console.log(end);
                            //console.log(response.data[key]['descripcion'])
                            //if()
                            console.log(salas[key]['equipo'][0]['nombre_equipo']);
                            this.categories.push(salas[key]['equipo'][0]['nombre_equipo'])
                            /*
                            this.events.push({
                                name: 'Atencion',
                                start: start2,
                                end: end,
                                color: 'green',
                                timed: 0,
                                category: this.categories[key],
                                //consultorio: response.data[key],
                                integrantes: salas[key]['integrantes']
                            })
 
                            */

                            /*this.events.push({
                                name: 'Cita',
                                start: new Date(this.fecha_calendario + 'T08:01:00-04:00'),
                                end: new Date(this.fecha_calendario + 'T09:01:00-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[key],
                            })*/
                        }
                        for (const key in fichas) {
                            var r = fichas
                            this.events.push({
                                //name: 'Atencion',
                                start: new Date(this.fecha_calendario + 'T' + fichas[key].hora_inicio + '-04:00'),
                                end: new Date(this.fecha_calendario + 'T' + fichas[key].hora_final + '-04:00'),
                                color: 'green',
                                timed: 1,
                                category: fichas[key].nombre_equipo,
                                //consultorio: response.data[key],
                                //integrantes: salas[key]['integrantes']
                            })
                        }
                        //console.log(this.type);

                        //this.fetchEvents()
                        //console.log(this.events)
                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
            /*try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_agenda/` + date,
                }).then(
                    (response) => {
                        let pacientes = response.data;
                        for (const key in pacientes) {
                            let paciente = pacientes[key];
                            //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);
                            this.events.push({
                                name: paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                start: new Date(paciente.fecha + 'T' + paciente.hora_inicio + '-04:00'),
                                end: new Date(paciente.fecha + 'T' + paciente.hora_final + '-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[paciente.consultorio-1],
                                paciente: structuredClone(paciente)
                            })
                            /*if (Object.hasOwnProperty.call(object, key)) {
                                const element = object[key];
                                
                            }*//*
}

}, (error) => {
console.log(error);
}
);
} catch (err) {
console.log("err->", err.response.data)
return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
}*/
        },
        async pedir_datos(date) {
            console.log(this.fecha_calendario);

            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/lista_configuracion/` + this.fecha_calendario,
            }).then(
                (response) => {
                    console.log(response);
                    let salas = response.data['salas'];
                    let salas_disponibles = response.data['salas_diponibles'];
                    this.equipos = response.data['equipo'];
                    /*console.log('_equipo----');
                    console.log(this.equipos);
                    console.log(salas_disponibles);
                    */
                    //console.log('__'+salas)

                    this.salas = salas
                    this.categories = [];
                    //let events = [];
                    this.events = [];
                    let start2 = new Date(this.fecha_calendario + 'T01:01:00-04:00')
                    let end = new Date(this.fecha_calendario + 'T21:50:00-04:00')
                    let fecha_server = moment(this.$store.getters.getfecha_server + 'T00:00:00-04:00')
                    this.fecha_min = fecha_server.format('YYYY-MM-DD')
                    for (const key in salas) {
                        //console.log(start);
                        //console.log(end);
                        /*console.log('----')
                        console.log(salas)
                        console.log(salas_disponibles)
                        */
                        console.log('-000000---');
                        console.log(salas
                        );
                        this.categories.push(salas[key]['descripcion'])
                        this.events.push({
                            name: salas[key]['nombre_equipo'],
                            start: start2,
                            end: end,
                            color: 'black',
                            timed: 0,
                            category: this.categories[key],
                            consultorio: salas[key],
                            equipo: true
                        })
                        //}


                        /*this.events.push({
                            name: 'Cita',
                            start: new Date(this.fecha_calendario + 'T08:01:00-04:00'),
                            end: new Date(this.fecha_calendario + 'T09:01:00-04:00'),
                            color: 'blue',
                            timed: 1,
                            category: this.categories[key],
                        })*/
                    }
                    console.log(this.fecha_calendario)
                    for (const key in salas_disponibles) {
                        let fichas = salas_disponibles[key];
                        //console.log(fichas);
                        //console.log(typeof ficha.id_oersiba);

                        for (const x in fichas) {
                            let ficha = fichas[x];
                            //console.log(ficha.id_persona);
                            let categoria = ''
                            for (const key in this.salas) {
                                /*console.log('------');
                                console.log(this.salas[key]);
                                console.log(this.salas[key].id_equipo, ' ', ficha.id_equipo);
                                */
                                if (this.salas[key].id_equipo == ficha.id_equipo && this.salas[key].id_sala == ficha.id_sala) {
                                    //console.log('si');
                                    //categoria=this.categories[key]

                                    this.events.push({
                                        name: (!ficha.id_persona) ? 'Sin asignar' : ficha.nombres,
                                        //paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                        start: new Date(this.fecha_calendario + 'T' + ficha.hora_inicio + '-04:00'),
                                        end: new Date(this.fecha_calendario + 'T' + ficha.hora_final + '-04:00'),
                                        color: (!ficha.id_persona) ? 'grey' : (!ficha.id_designado) ? 'blue' : 'green',
                                        timed: 1,
                                        category: this.categories[key],
                                        fichas: fichas[x],
                                        //atencion: fichas[atencion]
                                        //paciente: structuredClone(paciente)
                                    })

                                }
                            }

                        }
                        //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);

                        /*if (Object.hasOwnProperty.call(object, key)) {
                            const element = object[key];
                            
                        }**/
                    }
                    this.ready = true
                    this.scrollToTime()
                    this.updateTime()

                    //console.log(this.type);

                    //this.fetchEvents()
                    //console.log(this.events)
                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            /*
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_agenda/` + date,
                }).then(
                    (response) => {
                        console.log("---")
                        console.log(response);
                        let pacientes = response.data;
                        for (const key in pacientes) {
                            let paciente = pacientes[key];
                            //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);
                            this.events.push({
                                name: paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                start: new Date(paciente.fecha + 'T' + paciente.hora_inicio + '-04:00'),
                                end: new Date(paciente.fecha + 'T' + paciente.hora_final + '-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[paciente.consultorio-1],
                                paciente: structuredClone(paciente)
                            })
                            /*if (Object.hasOwnProperty.call(object, key)) {
                                const element = object[key];
                                
                            }***
                        }
 
                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }*/
        },
        async save_atender() {
            if (this.$refs.seleccion_equipo.validate()) {
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/atender`,
                    data: {
                        ficha: this.selectedEvent.fichas,
                        equipo: this.selectequipo.equipo
                    }
                }).then(
                    (response) => {
                        //console.log("---_:.-.-.-.-.-.-.::::::::----");
                        console.log(response);
                        this.selectedEvent.fichas = structuredClone(response.data);
                        //this.selectedEvent.color = 'yellow'
                        this.pedir_datos()
                        this.selectedOpen = false

                    }
                ).catch(err => {
                    console.log(err)

                });
            }

        },

        cambiar_ficha() {
            this.selectedOpen = false
            alert('seleccione usuario a cambiar');
            this.cambio_ficha = true
            this.cambia_datos = structuredClone(this.selectedEvent)

        },
        async eliminar_ficha(event) {
            //console.log(this.selectedEvent.fichas.id_ficha);
            //return;
            var res = await axios({
                method: 'delete',
                url: `/${process.env.MIX_CARPETA}/dar_ficha/` + this.selectedEvent.fichas.id_ficha,
                data:
                    this.selectedEvent.fichas,
                //equipo: this.selectequipo.equipo

            }).then(
                (response) => {

                    this.pedir_datos()
                    this.selectedOpen = false
                }
            ).catch(err => {
                console.log(err)

            });
        },
        async atencion_equipos() {
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/equipos_elegidos`,
                data:
                    this.selectedEvent.fichas,
                //equipo: this.selectequipo.equipo

            }).then(
                (response) => {
                    var r = response.data.seleccion
                    console.log(response.data);
                    this.selectedOpen = true
                    this.equipos = response.data.equipo
                    if (r) {
                        this.selectequipo = response.data.equipo[0]
                    }



                }
            ).catch(err => {
                console.log(err)

            });

        },
        getcolor(ficha) {
            return (!ficha.id_persona) ? 'white' : (!ficha.id_designado) ? 'blue' : 'green'
        },
        getvalores(objecto, x) {
            console.log('---------------s-s--s-s');
            console.log(objecto['' + x]);
            if (typeof objecto == "undefined") return false
            //console.log(objecto)
            console.log(objecto['' + x] === null);
            if (objecto['' + x] === null) return false
            return true
            return objecto['' + x]
        },
        valores(objecto, x) {
            if (typeof objecto == "undefined") return
            //console.log(objecto)
            return objecto['' + x]
        },
        getEventColor(event) {
            return event.color
        },
        setToday() {
            var f = new Date();
            this.fecha_calendario = ''
            this.fecha_calendario = f.toISOString().substr(0, 10);
            if (this.estado == 'atencion') {
                this.pedir_doctores(this.fecha_calendario);
                return
            }
            this.pedir_datos(this.fecha_calendario)

        },
        prev() {
            console.log(this.type);
            if (this.estado == 'atencion') {
                var valor = -1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.prev(contador);
                this.pedir_doctores(this.fecha_calendario)
                return;
            }
            if (this.type == 'category' || this.type == 'day') {
                var valor = -1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.prev(contador);
                this.pedir_datos(this.fecha_calendario)

            } else {
                this.$refs.calendar.prev();
            }

        },
        next() {

            if (this.estado == 'atencion') {
                var valor = +1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.next(contador);
                this.pedir_doctores(this.fecha_calendario)
                return
            }
            if (this.type == 'category' || this.type == 'day') {
                var valor = +1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.next(contador);
                this.pedir_datos(this.fecha_calendario)

            } else {
                this.$refs.calendar.next();
            }
        },
        diavalido(day) {
            console.log(day);
            console.log(day.getUTCDay());
            if (day.getUTCDay() == 0 || day.getUTCDay() == 6) {
                return true;
            }
            /*feriados*/
            return false;
        },
        contarvalidos(valor) {
            let fecha = structuredClone(this.fecha_calendario);
            console.log(fecha);
            var dateStr = fecha + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            var contador = 0;
            contador++;
            f.setDate(f.getDate() + valor);

            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;

            }
            console.log("saltar", contador);
            this.fecha_calendario = f.toISOString().substr(0, 10);
            return contador;
        },
        async api_cambiar(dato1, dato2) {
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/cambiar_cita`,
                data: {
                    ficha1: dato1.fichas,
                    ficha2: dato2.fichas,
                }
                //this.selectedEvent.fichas,
                //equipo: this.selectequipo.equipo

            }).then(
                (response) => {
                    console.log(response);
                    this.pedir_datos()

                }
            ).catch(err => {
                console.log(err)

            });
        },

        async showEvent({ nativeEvent, event }) {

            if (this.cambio_ficha) {
                console.log(event)
                console.log('cambiar por')
                console.log(this.cambiar_datos)
                this.api_cambiar(event, this.cambia_datos)

                this.selectedOpen = false
                this.cambio_ficha = false;
                return;
            }

            console.log(event);
            if (event.equipo == true) {
                this.selectedEvent = structuredClone(event)
                this.dialog_equipo = true;
                return;
            }

            if (event.name == 'Atencion') {
                //this.dialog = true
                //this.selectedEvent = event

                console.log('datos');
                console.log(event.integrantes)
                this.lista_equipo = event.integrantes
                //this.$refs.atender.op1 = 1;
                //this.$refs.a.fecha_cita = this.fecha_calendario
                //this.$refs.dato.consultorio = event.consultorio.sala
                this.$refs.atender.open()
                return;
                //this.selectedElement = nativeEvent.target
                //nativeEvent.stopPropagation()
            }
            if (event.name == 'Agendar') {
                //this.dialog = true
                //this.selectedEvent = event

                console.log('datos');
                console.log(event.consultorio)

                console.log(this.fecha_calendario);

                this.$refs.dato.op1 = 1;
                this.$refs.dato.fecha_cita = this.fecha_calendario
                this.$refs.dato.consultorio = event.consultorio.sala
                this.$refs.dato.open()
                return;
                //this.selectedElement = nativeEvent.target
                //nativeEvent.stopPropagation()
            } else {
                const open = () => {
                    this.selectedEvent = structuredClone(event)
                    this.selectedElement = nativeEvent.target
                    this.selectequipo = ''
                    this.atencion_equipos()
                    //requestAnimationFrame(() => requestAnimationFrame(() => ))
                }
                if (this.selectedOpen) {
                    this.selectedOpen = false
                    this.selectequipo = ''
                    requestAnimationFrame(() => requestAnimationFrame(() => open()))
                } else {
                    open()
                }
                nativeEvent.stopPropagation()
            }



        },
        updateRange({ start, end }) {
            let events = []
            this.events = []
            /*
            const min = new Date(`${start.date}T00:00:00`)
            const max = new Date(`${end.date}T23:59:59`)
            console.log(min)
            console.log(max)
            const days = (max.getTime() - min.getTime()) / 86400000
            const eventCount = this.rnd(days, days + 20)
 
            for (let i = 0; i < eventCount; i++) {
                const allDay = this.rnd(1, 3) === 0
                const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                const second = new Date(first.getTime() + secondTimestamp)
 
                events.push({
                    name: this.names[this.rnd(0, this.names.length - 1)],
                    start: first,
                    end: second,
                    color: this.colors[this.rnd(0, this.colors.length - 1)],
                    timed: !allDay,
                    category: this.categories[this.rnd(0, this.categories.length - 1)],
                })
            }
 
            this.events = events
            console.log(this.events)*/

        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        getDay(date) {

            const WeekDays = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

            return WeekDays[date.weekday];

        },
        fetchEvents() {
            const events = []
            console.log('---' + this.fecha_calendario)
            const min = new Date(this.fecha_calendario + ' T00:00')
            const max = new Date(this.fecha_calendario + ' T23:59')
            const days = (max.getTime() - min.getTime()) / 86400000
            const eventCount = this.rnd(days, days + 20)

            for (let i = 0; i < eventCount; i++) {
                const allDay = this.rnd(1, 3) === 0
                const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                const second = new Date(first.getTime() + secondTimestamp)

                events.push({
                    name: this.names[this.rnd(0, this.names.length - 1)],
                    start: first,
                    end: second,
                    color: this.colors[this.rnd(0, this.colors.length - 1)],
                    timed: 1,
                    category: this.categories[this.rnd(0, this.categories.length - 1)],
                })
            }
            //this.events.push(events)
        },
        get_datos_ficha(X) {
            console.log('....');
            if (typeof X.fichas == 'undefined') {
                return null
            }
            //console.log(X.fichas.id_persona);

            return X.fichas.id_persona
        },
        open_agenda() {
            this.selectedOpen = false
            this.dialog_persona = true
            setTimeout(() => {
                this.$refs.dato.op1 = 1;
                this.$refs.dato.fecha_cita = this.fecha_calendario
                this.$refs.dato.cita_nueva = this.selectedEvent.fichas
                //console.log();
                this.$refs.dato.open()
            }, 1);


        },
        get_nombre_equipo($value) {
            /*console.log("------");
            console.log($value)*/
            return $value.equipo.nombre_equipo
        },
        select_nombre_equipo($value) {
            /*console.log("--slect ----");
            console.log($value)*/
            return $value
        },
        getCurrentTime() {
            return this.cal ? this.cal.times.now.hour * 60 + this.cal.times.now.minute : 0
        },
        scrollToTime() {
            const time = this.getCurrentTime()
            const first = Math.max(0, time - (time % 30) - 30)
            console.log("11111111111111");
            console.log(first);
            this.cal.scrollToTime(first)
        },
        updateTime() {
            console.log('2222222222222');
            console.log(this.cal.updateTimes());
            setInterval(() => this.cal.updateTimes(), 60 * 2000)
        },
    }
}

</script>


