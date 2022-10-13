<template>
    <v-app>
        <div>
            <v-container>
                <div v-if="msg.fecha_validacion"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ msg.fecha_validacion }}</strong>
                </div>
                <v-row>
                    <v-col>
                        <v-sheet>
                            <v-toolbar>
                                <v-spacer />
                                <v-btn @click="setToday">
                                    Hoy
                                </v-btn>
                                <v-btn type="button" @click="ventana_modal">Agendar</v-btn>
                                <v-spacer />
                            </v-toolbar>
                            <v-divider></v-divider>
                            <v-toolbar elevation="23" flat extension height="80px">

                                <v-menu ref="menu1" max-width="290px" v-model="menu1" :close-on-content-click="false">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="$store.state.fecha_actual" label="Date"
                                            prepend-icon="mdi-calendar" filled v-bind="attrs" v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="$store.state.fecha_actual" no-title @input="menu1 = false"
                                        @change="formatDate" locale="es-ES">
                                    </v-date-picker>
                                </v-menu>
                                <v-btn fab text small @click="prev">
                                    <v-icon small>
                                        mdi-chevron-left
                                    </v-icon>
                                </v-btn>
                                <v-btn fab text small @click="next">
                                    <v-icon small>
                                        mdi-chevron-right
                                    </v-icon>
                                </v-btn>
                                <v-toolbar-title v-if="$refs.calendar">
                                    <!--{{ $refs.calendar.title }}-->
                                    {{ textoDate($store.state.fecha_actual) }}
                                </v-toolbar-title>
                                <v-spacer></v-spacer>

                            </v-toolbar>

                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-sheet class="fill-height">
                            <!--@change=""-->
                            <v-calendar ref="calendar" v-model="focus" color="primary" type="category" category-show-all
                                :categories="categories" :events="$store.state.events" :event-color="getEventColor"
                                @click:event="showEvent" :first-interval=12 :interval-minutes=30 :interval-count=24>
                                <template v-slot:event="{ event }">
                                    {{ event.nombres }}
                                    {{ event.ap_paterno }}
                                    {{ event.ap_materno }} <br>
                                    {{ event.hora_inicio }} <br>
                                </template>


                            </v-calendar>
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
            <v-dialog v-model="dialog" hide-overlay transition="dialog-bottom-transition">
                <v-toolbar color="#1CA698">
                    <v-btn icon @click="cerrar_dialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Datos Personales</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>

                <v-card>
                    <v-container>

                        <v-btn icon @click="av_buscar = !av_buscar">
                            <v-icon v-if="av_buscar">
                                mdi-close
                            </v-icon>
                            <v-icon v-else>
                                mdi-magnify
                            </v-icon>


                        </v-btn>
                        <strong class="white--text">Busqueda Avanzada</strong>
                        <buscar v-if="av_buscar" @actulizar_ci="prueba2($event)"
                            @actulizar_av_buscar="av_buscar = $event"></buscar>
                    </v-container>
                    <v-container>

                        <v-row>
                            <v-col cols="12">
                                <v-text-field v-model="paciente.ci" v-on:keyup.enter="prueba3" placeholder="Placeholder"
                                    color="green" filled label="Ingrese el Carnet" required>
                                </v-text-field>

                            </v-col>
                        </v-row>


                    </v-container>
                </v-card>
            </v-dialog>
            <datospersonales ref="datospersonales" />
            <!-- mensaje de guia para el usuario-->
            <v-dialog v-model="v_mensaje" persistent max-width="290">

                <v-card>
                    <v-card-title class="text-h5">
                        {{ msg_usuario.titulo }}
                    </v-card-title>
                    <v-card-text>{{ msg_usuario.text }}</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click="siguiente_ventana">
                            Registrar
                        </v-btn>
                        <v-btn color="black darken-1" text @click="v_mensaje = false">
                            Cancelar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                <v-card :color="($vuetify.theme.dark) ? 'blue-grey darken-2' : 'white'" min-width="350px" flat>
                    <v-toolbar :color="selectedEvent.color" dark>
                        <v-btn icon @click="guardar_se_presento">
                            <v-icon>mdi-content-save</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html="selectedEvent.nombre"></v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="editar_cita">
                            <v-icon>mdi-table-edit</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <span v-html="selectedEvent.details"></span>
                        <label class="font-bold">
                            Carnet: {{ selectedEvent.ci }}
                        </label><br>
                        <label class="font-bold">
                            Nombre Completo: {{ selectedEvent.name }} {{ selectedEvent.ap_paterno }}
                            {{ selectedEvent.ap_materno }}
                        </label><br>
                        <label class="font-bold">
                            Tipo de cita: {{ selectedEvent.tipo_cita }}
                        </label><br>
                        <label class="font-bold">
                            Se presento:
                            <v-select v-model="selectedEvent.se_presento" :items="opsepresento"
                                :menu-props="{ top: true, offsetY: true }" label=""></v-select>
                        </label><br>

                        <label class="font-bold" v-if="selectedEvent.se_presento == 'Si'">
                            Fue Atendido:
                            <v-select v-model="selectedEvent.ci_doctor" hint="Pick your meal" item-text="nombre"
                                item-value="ci" :items="doctores" :menu-props="{ top: true, offsetY: true }" label="">
                            </v-select>
                        </label>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="primary" @click="imprimir">
                            Boleta
                        </v-btn>
                        <v-btn text color="secondary" @click="selectedOpen = false">
                            Cancel
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>

        </div>
        <viewcita ref="viewcita" />
    </v-app>
</template>

<script>
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)
import buscar from '@/Pages/Micomponet/Buscar';
import datospersonales from '@/Pages/Micomponet/Datospersonales'
import viewcita from '@/Pages/Micomponet/FormEditarCita'

export default {
    components: {
        buscar,
        datospersonales,
        viewcita,
    },
    props: {
        //datos_cita: Array,
    },
    data: () => ({
        menu1: false,
        date1: day1,

        dark: false,
        actulizar_ci: {},
        msg: [],
        //fecha_hoy: day1,
        //calendar
        focus: '',

        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party', 'Cita'],
        categories: ['Equipo 1', 'Equipo 2', 'Equipo 3', 'Equipo 4'],
        equipo: [1, 2, 3, 4],


        v_informacion: false,
        //ventana agendar
        dialog: false,

        //ventana cita
        v_agendar: false,

        //ventana vista
        v_mensaje: false,
        msg_usuario: {},
        v_datos_informacion: false,
        datos_informacion: 'atender',
        paciente: {},
        paciente_cat: 0,
        datos_usuario: null,

        op22: [
            'agendar', 'atender',
        ],
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',

        av_buscar: false,
        headers: [{
            text: 'Fecha de cita',
            align: 'start',
            //sortable: false,
            value: 'fecha',
        },
        {
            text: 'Hora de inicio',
            value: 'hora_inicio'
        },
        {
            text: 'Tipo de cita',
            value: 'tipo_cita'
        },
        {
            text: 'Se presento',
            value: 'se_presento'
        },
        {
            text: 'Equipo',
            value: 'equipo'
        },
        {
            text: 'Observacion',
            value: 'observacion'
        },
        {
            text: 'Accion',
            value: 'actions',
            sortable: false
        },
        ],


        fecha_uso: day1,
        maxFechaNac: day1,
        minFechaNac: '1899-01-01',
        anterior_dia: '',
        fecha_hoy_actual: day1,
        fechacitaMin: day1,
        name: null,
        details: null,
        start: null,
        end: null,
        color: "#ffffff",

        v_agendar: false,
        nombre: null,
        paterno: null,
        materno: null,
        ci: null,
        departamento: null,
        correo: '',
        numero: '',
        fecha_nacimiento: null,
        direccion: null,
        ssexo: ['No se tiene registro', 'Masculino', 'Femenino'],
        sexo: 'No se tiene registro',
        fecha_cita: null,
        tiempo_inicio: null,
        timepo_fin: null,
        sdepartamentos: ['No se tiene registro', 'LP', 'OR', 'CH', 'CB', 'PT', 'TJ', 'SC', 'BE', 'PD'],
        deparamento: 'No se tiene registro',
        selectequipo: null,
        nombreRules: [v => !!v || 'Se requiere el dato'],
        selectRules: [v => v != 'No se tiene registro' || 'Se requiere el dato'],
        rules: [
            v => !!v || 'Se requiere dato',
            v => v >= minFechaNac || 'El valor es menor',
            v => v <= maxFechaNac || 'El valor es mayor',
        ],
        items: ['Dr Mamani', 'Dr Magi', 'Dr Fernandez', 'Dr Kantuta'],
        opsepresento: ['Si', 'No'],

        desserts: [],
        fecha_cita_actual: "",
        hora_inicial: null,
        equipo_actual: null,
        informacion: true,
        Historia: true,
        //equipos
        equipos_actuales: [],
        tiempos_actuales: [],
        lista_tiempos: {},
        t_equipo: null,
        dark: true,
        //ventana modal 
        selectedEvent: {},
        selecevent: {},
        selectedElement: null,
        selectedOpen: false,
        v_carga: false,
        pendingRequests: 0,
        totalRequests: 0,
        resx: '',
        seprento: '',
        tobservacion: '',
        sortDesc: true,
        doctores: [],

        v_imprimir: false,
        v_editar_agendar: false,
        editar: {},
        citas: [],
    }),
    mounted() {
        this.$refs.calendar.checkChange()
    },
    created() {
        this.initialize()
    },
    methods: {
        async editItem(item) {
            console.log(item);
            this.v_editar_agendar = true
            this.editar = item;

            Object.assign(this.editar, {
                hora: this.editar.hora_inicio
            });

            this.tiempos_actuales = [];
            console.log("---este es un evento---")
            console.log(this.editar)

            this.selecevent = this.selectedEvent;
            this.selectedEvent = item;
            await this.change_fecha(this.editar);
            this.v_editar_agendar = true;
            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            //this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.tiempos_actuales.push();
        },
        async deleteItem(item) {
            await this.eliminar_cita2(item);
            var b = await axios.post('api/datos_citas/' + item.ci).then();
            this.desserts = b['data'];
            console.log(b);
            if (this.desserts.length == 0) {
                this.desserts = [];
            }

        },
        async guardar_se_presento() {
            var res = await axios({
                method: 'post',
                url: 'api/guardar_datos',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
            console.log(res);
            this.traerdatos();

        },
        cerrar_editar_cita() {
            this.v_editar_agendar = false
        },
        async editar_cita() {
            console.log(this.selectedEvent)
            this.$refs.viewcita.cita_nueva = this.selectedEvent.cita
            this.$refs.viewcita.cita_anterior = structuredClone(this.selectedEvent.cita)
            this.$refs.viewcita.open()
            this.$refs.viewcita.change_fecha2()
            /*this.datos_informacion = a;
            var a = await axios.post('api/datos_usuario/' + this.selectedEvent.cita.ci).then();
            var datos = a['data'];
            console.log(datos['opcion']);
            var opcion = datos['opcion']

            
            console.log(this.selectedEvent.cita)
            this.$refs.datospersonales.open()
            this.$refs.datospersonales.tabselect(1);
            this.$refs.datospersonales.las_citas = datos['citas'];
        
            
            
            this.$refs.datospersonales.paciente = datos['datos'][0];
            this.$refs.datospersonales.paciente_edit = structuredClone(this.$refs.datospersonales.paciente);
            this.$refs.datospersonales.cita_nueva = structuredClone(this.selectedEvent.cita);
            this.$refs.datospersonales.change_fecha2()
            */

        },
        cerrar_agendar() {
            this.v_agendar = false;
        },
        ventana_agendar() {
            this.v_agendar = true;
            this.change_fecha2();
        },
        limipiar_form() {
            this.paciente = {}
            this.datos_usuario = {}

            //this.$refs.formcita.reset();
        },
        cerrar_dialog() {
            this.dialog = false;
            this.limipiar_form();

        },
        imprimir_directo() {
            this.selectedEvent = {
                fecha: this.fecha_cita_actual,
                hora: this.t_equipo,
                equipo: this.equipo_actual,
                tipo_cita: this.ttipocita,
                se_presento: '',
                observacion: this.tobservacion,
                lugar: this.tlugar,
                ci: this.ci,
                name: this.nombre,
                ap_paterno: this.paterno,
                ap_materno: this.materno,
            }
            console.log(this.tobservacion);

            this.v_imprimir = true;

        },
        imprimir() {
            //this.v_imprimir = true;
            console.log(this.selectedEvent)
            localStorage.setItem('cita', JSON.stringify(this.selectedEvent.cita));
            let w = window.open('/main/imprimir').focus();
        },
        async imprimir_boleta() {

            /*let printContents = document.getElementById('print').innerHTML;
            /*w.document.write('<style>.titulo{font-family:Helvetica,Arial,sans-serif;font-weight:100%;font-size:14px}.titulo2{font-family:Arial,sans-serif;font-weight:700;font-size:12px}.titulo3{font-size:9px;font-weight:900}.aling{align-items:center;align-content:center;text-align:center;padding:5px}.center_columna{justify-content:center;align-content:center;display:flex;justify-content:center;align-items:center;border:3px solid gray}.aling{align-items:center;align-content:center;text-align:center;padding:0,0,0,0;font-size:10px}.alinear_elemento{justify-content:center;align-items:center;padding:5px;border-radius:5px;border:2px solid gray}.total_ancho{width:100%;background-color:#1ca698}.box{border-radius:10px;border:2px solid gray;justify-content:center;align-items:center;align-content:center;align-self:center;height:90px;align-items:center;align-content:center;text-align:center;align-content:center;padding:1px;justify-content:center;word-break:break-all}.wrapper{display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:minmax(10px,90px);justify-content:center;text-align:center;padding:0,0,0,0;font-size:20px;border:0 solid gray;align-content:center;text-align:center;word-break:break-all}.one{grid-column:1/4;grid-row:1;align-content:center;height:30px}.two{grid-column:1;grid-row:2}.three{grid-column:2;grid-row:2}.four{grid-column:3;grid-row:2;font-size:14px}.five{grid-column:1/4;grid-row:3}.label{display:flex;flex-wrap:wrap;align-content:center;justify-content:center}.grid-container{display:grid;grid-template-columns:auto auto auto;padding:1px;font-size:20px}.grid-item{background-color:rgba(255,255,255,.8);border:1px solid rgba(0,0,0,.8);padding:1px;font-size:20px;text-align:center}.grid-nombre{background-color:rgba(255,255,255,.8);border:1px solid rgba(0,0,0,.8);font-size:20px;text-align:center}.texto_nombre{padding:0}.texto_mediano{font-size:16px}.observaciones{width:max-content}</STYLE>');

            w.document.write(printContents);

            w.document.close(); // necessary for IE >= 10
            w.focus(); // necessary for IE >= 10
            w.print();
            w.close();
            return true;*/

            window.print();

            //this.$inertia.get('api/imprimir', usuario);
        },
        showEvent({
            nativeEvent,
            event
        }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                requestAnimationFrame(() => requestAnimationFrame(() => open()))
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        limipiar_formcita() {

        },
        async eliminar_cita() {
            console.log("----")
            console.log(this.selectedEvent)
            var res = await axios({
                method: 'post',
                url: 'api/eliminar_cita',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
            console.log(res)
            this.v_editar_agendar = false
            this.traerdatos();
        },
        async eliminar_cita2() {
            alert("ss");
            var res = await axios({
                method: 'post',
                url: 'api/eliminar_cita2',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
        },
        async guardar_cita_edit() {

            let hof = moment('2017-08-30T' + this.editar.hora).add(1, "h");
            if (this.$refs.form_cita_edit.validate()) {
                var cita = {
                    fecha: this.editar.fecha,
                    hora_inicio: this.editar.hora,
                    equipo: this.edita.equipo,
                    hora_final: hof.format("HH:mm:ss"),
                    tipo_cita: this.editar.tipo_cita,
                    se_presento: '',
                    observacion: this.editar.observacion,
                    lugar: this.editar.lugar,
                    ci: this.editar.id,
                    ci_doctor: '-1',

                }
                console.log(".....")
                console.log(this.editar)
                var res = await axios({
                    method: 'post',
                    url: 'api/dar_cita',
                    data: {
                        cita: cita,
                    }
                }).then();
                console.log(res['data']);

                this.v_editar_agendar = false;
                this.$refs.form_cita_edit.reset();
                var esp = await this.eliminar_cita()

            }

        },
        ventana_modal() {
            console.log("----" + this.$store.state.fecha_actual);
            this.citas.fecha = this.$store.state.fecha_actual;
            this.dialog = true;
        },
        cambioequipo() {
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.t_equipo = this.tiempos_actuales[0];
        },
        cambioequipos() {
            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            this.editar.hora = this.tiempos_actuales[0];
        },
        async change_fecha2() {
            var a = await axios.post('api/citas_actuales/' + this.fecha_cita_actual).then();
            console.log(a['data']);
            var array = a['data']
            this.equipos_actuales = [];
            var primera = true;
            var p, w = "";
            this.lista_tiempos = {};
            for (let i = 0; i < array.length; i++) {
                if (Object.keys(array[i]).length >= 1) {
                    this.equipos_actuales.push(i + 1);
                    var aux = [];
                    for (const key in array[i]) {
                        aux.push(array[i][key]);
                        if (primera) {
                            p = i,
                                w = array[i][key]
                            primera = false;
                        }
                    }
                    this.lista_tiempos['' + (i + 1)] = aux;
                }
            }
            this.equipo_actual = p + 1;
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];

        },
        async change_fecha(evento) {
            var a = await axios.post('api/citas_actuales/' + evento.fecha).then();
            //console.log(a['data']);
            var array = a['data']
            this.equipos_actuales = [];
            var primera = true;
            var p, w = "";
            this.lista_tiempos = {};
            for (let i = 0; i < array.length; i++) {
                if (Object.keys(array[i]).length >= 1) {
                    this.equipos_actuales.push(i + 1);
                    var aux = [];
                    for (const key in array[i]) {
                        aux.push(array[i][key]);
                        if (primera) {
                            p = i,
                                w = array[i][key]
                            primera = false;
                        }
                    }
                    this.lista_tiempos['' + (i + 1)] = aux;
                }
            }
            this.equipo_actual = p + 1;
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            console.log(this.tiempos_actuales)
            this.t_equipo = w
        },
        async Buscarfecha() {
            var a = await axios.post('api/citas_actuales/' + this.fecha_cita_actual).then();
            this.datos_usuario = a['data'];

        },
        rulesFecha_nac() {
            var fn = moment(this.fecha_nacimiento)
            var fmin = moment(this.minFechaNac)
            var fmax = moment(this.maxFechaNac)
            if (!(fmax > fn && fn > fmin)) {
                return "Fecha no valida";
            }
            return true;
        },
        reinicioar_paciente() {
            this.paciente = {
                ci: '',
                departamento: 'No se tiene registro',
                nombre: null,
                ci: null,
                departamento: null,
                correo: '',
                celular: '',
                fecha_nacimiento: null,
                direccion: null,
                sexo: 'No se tiene registro',
                expedido: null,
            }

        },
        //**
        siguiente_ventana() {
            this.dialog = false;
            this.v_mensaje = false;
            this.$refs.datospersonales.open()
        },
        //**
        async prueba2(e) {
            this.paciente = e;
            this.prueba3()
        },
        async prueba3() {
            this.paciente_cat = -1;
            this.desserts = [];
            if (this.paciente.ci == '') {
                return;
            }
            var datos = [];
            var a = await axios.post('api/datos_usuario/' + this.paciente.ci).then();
            var datos = a['data'];
            console.log(datos['opcion']);
            var opcion = datos['opcion'];
            this.$refs.datospersonales.op1 = opcion;

            this.$refs.datospersonales.las_citas = datos['citas'];
            this.$refs.datospersonales.cita_nueva.fecha = this.$store.state.fecha_actual;
            this.$refs.datospersonales.change_fecha2()
            if (opcion == 1) {
                this.$refs.datospersonales.paciente = {};
                this.$refs.datospersonales.paciente.ci = structuredClone(this.paciente.ci);
                this.v_mensaje = true;
                this.msg_usuario = {
                    titulo: 'Usuario Nuevo',
                    text: 'El usuario es Nuevo, no se encontraron registro. Se debe realizar un registro',
                    color: 'red',
                    opcion: 1,
                }
            }
            if (opcion == 2) {
                this.$refs.datospersonales.paciente = datos['datos'][0];
                this.$refs.datospersonales.paciente_edit = structuredClone(this.$refs.datospersonales.paciente);
                this.msg_usuario = {
                    titulo: 'Usuario Antiguo',
                    text: 'El usuario ya se lo tiene registrado, quiere ver su datos',
                    color: 'blue',
                    opcion: 2,
                }
                this.v_mensaje = true;
            }
        },
        prueba_fecha() {
            console.log(this.$store.state.fecha_actual);
        },
        async addelemento(array) {
            console.log(array);

            this.$store.state.listevent = await array;
            this.$store.dispatch('listEventsAction')
            /*this.$store.dispatch('clearEventAction')

            for (var element of array) {

                const first = new Date(element.fecha + 'T' + element.hora_inicio + '-04:00')
                console.log(element);
                //alert(first)
                const second = new Date(element.fecha + 'T' + element.hora_final + '-04:00')
                var datos = {
                    name: element.nombres,
                    start: first,
                    end: second,
                    color: 'black',
                    timed: 1,
                    category: this.categories[element.equipo - 1],
                    cita: element
                }
                
                datos = Object.assign(datos, element);
                this.$store.state.event = datos
                this.$store.dispatch('addEventAction')
                this.datos = {}
                //this.$store.events.push(datos)
            }*¨/
            */
        },
        initialize() {
            this.addelemento(this.datos_cita)
            this.doctorlista()
        },
        async doctorlista() {
            var a = await axios.get('api/doctor').then();
            this.doctores = a['data'];
        },
        async traerdatos() {
            console.log(this.$store.state.fecha_actual);
            var a = await axios.get('api/citas_fecha/' + this.$store.state.fecha_actual).then();
            this.addelemento(a['data']);
            this.$store.state.fecha = this.fechas
            this.$store.dispatch('addFechaAction')

        },
        getEventColor(event) {
            return event.color
        },
        setFecha() {
            const today = new Date();
            const date = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            const timestamp = date + ' ' + time;
            return date;
        },
        setToday() {

            this.msg['fecha_validacion'] = '';
            this.focus = ''
            var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
            this.anterior_dia = today;
            this.$store.state.fecha_actual = today;

            this.traerdatos();
            //alert("anterios dia"+this.anterior_dia+"hoy"+this.$store.state.fecha_actual);

        },
        diavalido(day) {
            if (day.getUTCDay() == 0 || day.getUTCDay() == 6) {
                return true;
            }
            var feriados = {}
            feriados['2022-05-02'] = true;
            feriados['2022-04-15'] = true;
            return feriados[day.toISOString().substr(0, 10)];
        },
        todayone(valor) {
            var dateStr = this.fecha_hoy_actual + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            f.setDate(f.getDate() + valor);
            return f.toISOString().substr(0, 10);
        },
        contarvalidos(valor) {
            var dateStr = this.$store.state.fecha_actual + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            var contador = 1;
            f.setDate(f.getDate() + valor);
            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;
            }
            this.$store.state.fecha_actual = f.toISOString().substr(0, 10);
            this.anterior_dia = this.$store.state.fecha_actual;
            return contador;
        },
        prev() {
            var valor = -1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.prev(contador);
            this.traerdatos();
        },
        next() {
            var valor = 1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.next(contador);
            this.traerdatos();
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        async formatDate() {
            //alert(val);
            if (this.anterior_dia == '') {
                var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
                this.anterior_dia = today;
            }

            var elDate = document.getElementById('txtDate');
            var dateStr = this.$store.state.fecha_actual + 'T00:00:00-04:00';
            console.log(dateStr);
            var now = new Date(dateStr)
            var dayp = now.getUTCDay();
            this.msg['fecha_validacion'] = '';
            if (dayp == 0) {
                this.msg['fecha_validacion'] = 'Domingos no disponibles, por favor seleccione otro día.';
                this.$store.state.fecha_actual = this.anterior_dia;
                return;
            }
            if (dayp == 6) {
                this.msg['fecha_validacion'] = 'Sabados no disponibles, por favor seleccione otro día.';
                this.$store.state.fecha_actual = this.anterior_dia;
                return;
            }
            var feriados = {}
            feriados['2022-05-02'] = true;
            feriados['2022-04-15'] = true;
            if (feriados[this.$store.state.fecha_actual]) {
                this.msg['fecha_validacion'] = this.$store.state.fecha_actual + ' es feriado.';
                this.$store.state.fecha_actual = this.anterior_dia;
                return;
            }
            const date1 = new Date(this.anterior_dia);
            const date2 = new Date(this.$store.state.fecha_actual);
            const diffTime = date2 - date1;
            const nday = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            //alert(val + " " + this.anterior_dia + " " + nday);
            this.anterior_dia = this.$store.state.fecha_actual
            this.$refs.calendar.move(nday);
            //this.$emit('input', dateObj);
            this.traerdatos();
        },
        textoDate(val) {
            var dia = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septimbre', 'Octobre', 'Novembre', 'Diciembre'];
            var dateStr = val + 'T00:00:00-04:00';
            var now = new Date(dateStr)
            return dia[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            //this.focus = val;
            //alert(val);
            //alert(" =>"+ver1+" "+ver2);
            //this.$emit('input', dateObj);
        },
        /******/
        agendadesde() {
            console.log(day1)
            if (this.$store.state.fecha_actual > day1) {
                console.log(this.$store.state.fecha_actual + " " + day1)
                return true
            }
            return false
        }

    },
    computed: {

    },
}
</script>
