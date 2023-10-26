<template>
    <app-layout>

        <v-row>
            <v-col cols="12">
                <h1>Atenciones para {{ fechaSeleccionada }}</h1>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">

                <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" transition="scale-transition"
                    offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="fechaSeleccionada" label="fecha de atencion " prepend-icon="mdi-calendar"
                            readonly v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="fechaSeleccionada" @input="menu2 = false" @change="redirigir"
                        :allowed-dates="allowedDates" locale="es"></v-date-picker>
                </v-menu>

            </v-col>
        </v-row>

        <!-- Información -->
        <v-row>
            <v-col cols="12">
                <h1>Lista de pacientes</h1>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-data-table :headers="headers" :item-key="'ci'" :items="atenciones" :sort-by="sortBy"
                    :sort-desc="sortDesc" class="elevation-1">
                    <template v-slot:item.hora_inicio="{ item }">
                        <td>{{ formatearHora(item.hora_inicio) }}</td>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <td>
                            <v-row>
                                <v-col cols="6">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on"  @click="mostrarHistorial(item)" class="blue darken-2 white--text">
                                                <v-icon left>mdi-file-document</v-icon> 
                                            </v-btn>
                                        </template>
                                        <span>Historia Clínica</span>
                                    </v-tooltip>
                                </v-col>
                                <v-col cols="6">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on"  @click="mostrarHistorialActual(item)" class="green darken-2 white--text">
                                                <v-icon left>mdi-history</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historiales Anteriores</span>
                                    </v-tooltip>
                                </v-col>
                            </v-row>
                        </td>
                    </template>
                    <template v-slot:no-data>
                        <div class="text-center">No se tienen registradas atenciones.</div>
                    </template>
                </v-data-table>


            </v-col>
        </v-row>

        <v-dialog v-model="mostrarDialogoHistorialActual" fullscreen :scrim="false" transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="mostrarDialogoHistorialActual = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Historial del Paciente</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="mostrarDialogoHistorialActual = false" color="primary">
                            Guardar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

            </v-card>
        </v-dialog>
        <v-dialog v-model="mostrarDialogoHistorial" fullscreen :scrim="false" transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="mostrarDialogoHistorial = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Creacion de Historial Clinico</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="NavigationPreloadManager = false" color="primary">
                            Guardar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-form @submit.prevent="enviarFormulario">
                    <v-card>
                        <v-card-title>Datos de Deficiencia y Discapacidad</v-card-title>

                        <!-- Campo: Causa de la Deficiencia -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <v-select v-model="causaDeficiencia" :items="causasDeficiencia"
                                    label="Causa de la Deficiencia" item-text="nombre" item-value="codigo"></v-select>
                            </v-col>
                        </v-row>

                        <!-- Campo: Edad de Inicio de la Deficiencia -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <p>
                                    Edad de Inicio de la Deficiencia
                                </p>
                            </v-col>
                        </v-row>

                        <!-- Campo: Día, Mes y Año -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="4">
                                <!-- Campo de Día -->
                                <v-text-field v-model="dia" label="Día" outlined dense maxlength="2"
                                    @input="(val) => (dia = validarNumero(val))"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <!-- Campo de Mes -->
                                <v-text-field v-model="mes" label="Mes" outlined dense maxlength="2"
                                    @input="(val) => (mes = validarNumero(val))"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <!-- Campo de Año -->
                                <v-text-field v-model="ano" label="Año" outlined dense maxlength="4"
                                    @input="(val) => (ano = validarNumero(val))"></v-text-field>
                            </v-col>
                        </v-row>

                        <!-- Campo: Necesita Ayuda de Otra Persona -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <v-radio-group v-model="necesitaAyuda" label="Necesita Ayuda de Otra Persona">
                                    <v-radio label="Total" value="total"></v-radio>
                                    <v-radio label="Parcial" value="parcial"></v-radio>
                                    <v-radio label="Ninguna" value="ninguna"></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>

                        <!-- Campo: Grados de Discapacidad -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <v-radio-group v-model="gradosDiscapacidad" label="Grados de Discapacidad">
                                    <v-radio label="NULO" value="NULO"></v-radio>
                                    <v-radio label="LEVE" value="LEVE"></v-radio>
                                    <v-radio label="MODERADO" value="MODERADO"></v-radio>
                                    <v-radio label="GRAVE" value="GRAVE"></v-radio>
                                    <v-radio label="MUY GRAVE" value="MUY GRAVE"></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>

                        <!-- Campo: Porcentaje Global de Discapacidad -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <v-text-field v-model="porcentajeDiscapacidad"
                                    label="Porcentaje Global de Discapacidad"></v-text-field>
                            </v-col>
                        </v-row>

                        <!-- Campo: Tipo de Discapacidad -->
                        <v-row class="mx-2"> <!-- Agrega la clase mx-2 para márgenes horizontalmente -->
                            <v-col cols="12">
                                <v-select v-model="tipoDiscapacidad" :items="tiposDiscapacidad"
                                    label="Tipo de Discapacidad"></v-select>
                            </v-col>
                        </v-row>

                        <!-- Botón de enviar -->
                        <v-card-actions>
                            <v-btn type="submit" color="primary">Enviar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>

            </v-card>
        </v-dialog>


    </app-layout>
</template>

<script>

import io from "socket.io-client";

window.io = io;
import Pusher from 'pusher-js'


import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import Registros from '@/Pages/Auth/Register'
import Lista from '@/Pages/Personal/Listapersonal'
import moment from 'moment'
//import { VListSubheader } from '@/vuetify/lib/components/VList';
//import { router } from '@inertiajs/vue2'
import { Inertia } from '@inertiajs/inertia';
//import Echo from "laravel-echo";
import Echo from 'laravel-echo';
//import Pusher from 'pusher-js';
import Cookies from 'js-cookie';

//const socket = io('http://localhost:6001');
// Have this in case you stop running your
if (typeof io !== 'undefined') {
    console.log('ioioio');
    window.Echo = new Echo({
        namespace: 'App.Events',
        broadcaster: 'socket.io',
        connector: 'socket.io',
        //client: socket,
        reconnectionAttempts: 5,
        csrfToken: Cookies.get('XSRF-TOKEN'),
        host: 'http://' + window.location.hostname + ':6001',
        //authEndpoint: 'http://localhost/main/broadcasting/auth',

        //transports: ['websocket'],
        auth: {
            headers: {
                Authorization: "Bearer " + Cookies.get('XSRF-TOKEN'),
            },
        },
    });
}
//const socket = io('http://localhost:6001'); // Reemplaza con la URL y puerto de tu servidor Socket.io
export default {

    props: {
        atenciones1: {
            type: Array,
            required: true,
        },
        hoy: {
            type: String,
            required: true,
        },
        fecha_actual: {
            type: String,
            required: true,
        },
    },
    data: () => ({
        atenciones: [],
        dia: '',
        mes: '',
        ano: '',
        causaDeficiencia: null,
        causasDeficiencia: [
            { codigo: '01', nombre: 'Enfermedad Adquirida' },
            { codigo: '02', nombre: 'Accidente de Tránsito' },
            { codigo: '03', nombre: 'Accidente de Trabajo' },
            { codigo: '04', nombre: 'Accidente Doméstico' },
            { codigo: '05', nombre: 'Accidente Deportivo' },
            { codigo: '06', nombre: 'Violencia' },
            { codigo: '07', nombre: 'Desastre Natural' },
            { codigo: '08', nombre: 'Congénito/Genético' },
            { codigo: '09', nombre: 'Problemas Prenatales' },
            { codigo: '10', nombre: 'Problemas de Parto' },
            { codigo: '11', nombre: 'Trastornos Nutricionales' },
            { codigo: '12', nombre: 'Otros Especiales' },
        ],
        edadInicio: '',
        necesitaAyuda: '',
        gradosDiscapacidad: '',
        porcentajeDiscapacidad: '',
        tipoDiscapacidad: '',
        tiposDiscapacidad: ['Física', 'Motora', 'Auditiva', 'Intelectual', 'Mental o Psíquica', 'Múltiple'],


        notifications: false,
        sound: true,
        widgets: false,

        gradoDiscapacidad: null,
        porcentajeDiscapacidad: null,
        menu2: false,
        tabs: 0,
        fechaSeleccionada: null,
        headers: [
            {
                text: 'Cedula de Identidad',
                align: 'start',
                sortable: false,
                value: 'ci',
                //'hide-default-header': true,
            },
            // Agrega más encabezados de columna aquí

            {
                text: 'Hora de atencion',
                sortable: true,
                value: 'hora_inicio',
                //filter:  this.formatearHora(value)

            },
            {
                text: 'Apellido Paterno',
                sortable: false,
                value: 'ap_paterno',
            },
            {
                text: 'Apellido Materno',
                sortable: false,
                value: 'ap_materno',
            },
            {
                text: 'Nombres',
                sortable: false,
                value: 'nombres',
            },

            {
                text: 'Acciones',
                sortable: false, // No es necesario que sea sortable
                value: 'actions', // Puedes usar cualquier nombre aquí
            },
        ],
        mostrarDialogoHistorial: false,
        mostrarDialogoHistorialActual: false,
        historialData: '', // Puedes almacenar datos del historial aquí
        historialActualData: '',
        sortBy: ['fecha', 'hora_inicio'], // Aquí especifica las columnas por las que deseas ordenar
        sortDesc: [false, false], // false indica orden ascendente, true indica orden descendente

        //hoy: moment().format('DD dddd MMMM YYYY'),
    }),
    components: {
        AppLayout,
        Registros,
        Lista,
        //VListSubheader,

    },
    mounted() {
        window.Echo.private('atencion.' + this.$page.props.user.id + "." + this.fechaSeleccionada)
            .listen('.AtenderEvent', (event) => {
                console.log('Mensaje ssssssssrecibido:', event);
                this.atenciones = event.datos
                console.log(this.atenciones);
                // Haz algo con los datos recibidos
            });

        // Suscribirse al canal
        /*window.Echo.private('message-channel.' + this.$page.props.user.id)
            .listen('.AtenderEvent', (event) => {
                console.log('Mensaje ssssssssrecibido:', event);
                this.atenciones = event.datos
                console.log(this.atenciones);
                // Haz algo con los datos recibidos
            });*/

    },
    created() {
        this.fechaSeleccionada = this.fecha_actual; // Puedes usar una función para obtener la fecha actual
        console.log();
        //import Echo from 'laravel-echo';
        console.log(window.location.hostname);
        console.log(window.laravel_echo_port);
        console.log(Cookies.get('XSRF-TOKEN'))
        window.io = require('socket.io-client')

        // Verifica si Echo está conectado
        if (window.Echo.connector.socket.connected) {
            console.log('Echo está conectado.');
        } else {
            console.log('Echo está desconectado.');
        }
        //import io from "socket.io-client";

        //const socket = io("http://localhost:6001");



        window.Echo.channel('mesajecanal')
            .listen('.MensajeEvento', (event) => {
                console.log('Evento recibido:', event);
            })
        this.atenciones = this.atenciones1
        //console.log(this.$page.props.user)
        /*window.Echo.channel('massage-channel')
        .listen('.MessageEvent', (event) => {
            console.log('Evento recibido:', event.massage);
        });
        window.Echo.channel('massage-channel')
        .listen('MessageEvent', (event) => {
            console.log('Evento recibido:', event.massage);
        });*/

    },
    methods: {

        validarNumero(campo) {
            console.log('///////////');
            console.log(campo);
            console.log(campo.replace(/[^0-9]/g, ''));
            return campo.replace(/[^0-9]/g, '')
        },
        allowedDates(date) {
            //var date =  this.fechaSeleccionada
            date = new Date(date);

            //console.log(date)
            return date.getDay() !== 6 && date.getDay() !== 5; // 0 representa domingo, 6 representa sábado
        },
        mostrarHistorial(item) {
            // Lógica para obtener el historial completo para el elemento seleccionado
            // Puedes actualizar this.historialData con los datos del historial
            this.historialData = 'Datos del historial completo para ' + item.ci;
            this.mostrarDialogoHistorial = true;
        },
        mostrarHistorialActual(item) {
            // Lógica para obtener el historial actual para el elemento seleccionado
            // Puedes actualizar this.historialActualData con los datos del historial actual
            this.historialActualData = 'Datos del historial actual para ' + item.ci;
            this.mostrarDialogoHistorialActual = true;
        },
        cerrarDialogoHistorial() {
            this.mostrarDialogoHistorial = false;
        },
        cerrarDialogoHistorialActual() {
            this.mostrarDialogoHistorialActual = false;
        },
        redirigir() {
            // Verifica si se seleccionó una fecha antes de redirigir
            if (this.fechaSeleccionada) {
                // Utiliza la fecha seleccionada para construir la URL
                const fechaFormateada = moment(this.fechaSeleccionada).format('YYYY-MM-DD');
                //const fechaFormateada = this.formatoFecha(this.fechaSeleccionada);
                const url = `/${process.env.MIX_CARPETA}/atender/${fechaFormateada}`;

                // Redirige a la URL
                this.$inertia.visit(route('atencion', { fecha: fechaFormateada }));


            }
        },
        formatearHora(hora) {
            // Parsea la hora en formato "hh:mm"
            var horaParseada = moment(hora, 'HH:mm:ss');

            // Formatea la hora en "hh:mm a"
            var horaFormateada = horaParseada.format('hh:mm a');

            return horaFormateada;
        },
        enviarFormulario() {
            // Aquí puedes manejar el envío del formulario
            // Puedes acceder a los valores seleccionados con this.gradoDiscapacidad y this.porcentajeDiscapacidad
            console.log('Grado de Discapacidad:', this.gradoDiscapacidad);
            console.log('Porcentaje Global de Discapacidad:', this.porcentajeDiscapacidad);
        },
    },

}
</script>
