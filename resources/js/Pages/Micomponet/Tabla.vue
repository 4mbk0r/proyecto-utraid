<template>
    <v-app>
        <v-card>
            <v-row>

                <v-col>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <input v-model="fecha_calendario" @keyup.enter="pedir_datos" type="date">
                        </template>

                    </v-menu>
                </v-col>
                <v-col>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn outlined color="primary" @click="procesarDatos" v-bind="attrs" v-on="on">
                                <v-icon end icon>mdi-cog</v-icon>

                            </v-btn>
                        </template>
                        <span>Configurar horario</span>
                    </v-tooltip>
                </v-col>
                <v-col>

                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                                <span>{{ optionsCitas[tipo_seleccion].text }}</span>
                                <v-icon right>
                                    mdi-menu-down
                                </v-icon>
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item v-for="(item, index) in optionsCitas" :key="index"
                                @click="seleccionarOpcion(item)">
                                <v-list-item-title>{{ item.text }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-col>
                <v-col>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-bind="attrs" v-on="on" v-model="search" append-icon="mdi-magnify" label="Buscar"
                                solo-inverted hide-details clearable class="search-input"></v-text-field>
                        </template>

                    </v-menu>

                </v-col>
                <v-col>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn outlined color="grey darken-2" v-bind="attrs" @click="dialogBusquedaTotal = true">
                                <span>Buscar</span>
                                <v-icon right>
                                    mdi-account-search-outline
                                </v-icon>
                            </v-btn>
                        </template>

                    </v-menu>


                </v-col>
                <v-divider vertical></v-divider>
                <v-col>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-autocomplete v-model="cita_nueva.lugar" :items="lugar" @change="mostrar_datos"
                                no-data-text="No se encontraron resultados" item-text='direccion' item-value="codigo">
                            </v-autocomplete>
                        </template>

                    </v-menu>

                </v-col>


            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col>
                    <v-data-table ref="dataTableRef" :headers="headers" sort-by="hora_inicio" :sort-desc="true"
                        group-by="id_horario" :custom-sort="customSort" :items="equipos" :search="search"
                        :items-per-page="itemsPerPage" :footer-props="{
                            showFirstLastPage: true,
                            firstIcon: 'mdi-arrow-collapse-left',
                            lastIcon: 'mdi-arrow-collapse-right',
                            prevIcon: 'mdi-minus',
                            nextIcon: 'mdi-plus',
                            'items-per-page-text': 'Lista de citas',
                            'items-per-page-all-text': 'Todos'
                        }">
                        <template v-slot:group.header="{ group, isExpanded, toggle, isOpen }">
                            <!-- Encabezado del grupo -->
                            <th :colspan="headers.length">
                                <v-row>
                                    <v-col>
                                        <v-btn icon @click="toggle">
                                            <v-icon>{{ isOpen ? 'mdi-chevron-down' : 'mdi-chevron-right' }}</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col>
                                        <span> {{ formatearHora(agruparCodigo[group].hora_inicio) }} {{
                                            formatearHora(agruparCodigo[group].hora_final) }}</span>
                                    </v-col>
                                    <!-- Botón de agregar para cada grupo -->
                                    <v-divider></v-divider>
                                    <v-col>
                                        <v-btn class=" justify-end" outlined color="primary" @click="agregarItem(group)">
                                            <v-icon>mdi-plus</v-icon>
                                            Nueva Horario
                                        </v-btn>
                                    </v-col>
                                </v-row>

                            </th>
                        </template>
                        <template v-slot:no-results>
                            <span>No se encontraron los datos.</span>
                        </template>
                        <template v-slot:item.="{ item }">
                            {{ formatearHora(item.hora_inicio) }}
                        </template>
                        <template v-slot:item.hora_final="{ item }">
                            {{ formatearHora(item.hora_inicio) }} - {{ formatearHora(item.hora_final) }}
                        </template>
                        <template v-slot:item.nombres="{ item }">
                            <template v-if="!empty(item.ci)">
                                <span> {{ item.nombres }}</span>
                                <span v-if="!empty(item.ap_paterno)"> {{ item.ap_paterno }}</span>
                                <span v-if="!empty(item.ap_materno)"> {{ item.ap_materno }}</span>
                            </template>
                            <template v-else>
                                <span>Aun no se asingnado</span>
                            </template>
                        </template>
                        <template v-slot:item.ci="{ item }">

                            <template v-if="!empty(item.ci)">
                                <span>{{ item.ci }} {{ departamentos[item.expedido] }}</span>

                                <template v-if="comparaFechas()">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="ma-2" color="success" @click="open_agenda(item)" v-bind="attrs"
                                                v-on="on">
                                                <v-icon end icon>mdi-account</v-icon>

                                            </v-btn>
                                        </template>
                                        <span>Editar usuario</span>
                                    </v-tooltip>

                                    <!-- Botón con tooltip para imprimir tabla -->
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="ma-2" color="success" @click="imprimir_tabla(item)" v-bind="attrs"
                                                v-on="on">
                                                <v-icon end icon>mdi-printer</v-icon>

                                            </v-btn>
                                        </template>
                                        <span>Boleta</span>
                                    </v-tooltip>

                                    <!-- Botón con tooltip para mostrar diálogo -->
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="ma-2" color="success" @click="mostrarDialogo2(item)"
                                                v-bind="attrs" v-on="on">
                                                <v-icon end icon>mdi-delete-empty</v-icon>

                                            </v-btn>
                                        </template>
                                        <span>Eliminar Cita</span>
                                    </v-tooltip>
                                    <!--<v-autocomplete v-model="item.id_designado" :items="doctores"
                                        :item-text="item => (item.nombres + ' ' + item.ap_paterno + ' ' + item.ap_materno)"
                                        :item-value="item => item.id" :rules="selecionRules" cache-items class="mx-4" flat
                                        hide-no-data hide-details label="Escriba el nombre del medico"
                                        @change="funcionPrueba(item)"
                                        :menu-props="{ closeOnClick: false, closeOnContentClick: false }">

                                    </v-autocomplete>
                                    -->



                                </template>

                            </template>
                            <template v-else>
                                <template v-if="comparaFechas()">
                                    <v-btn class="ma-2" color="primary" @click="open_agenda(item)" small>
                                        Dar cita
                                        <v-icon end icon> mdi-pencil</v-icon>
                                    </v-btn>

                                </template>

                            </template>


                        </template>
                        <template v-slot:item.option="{ item }">
                            {{ item.nombres }}
                        </template>

                        <template v-slot:item.atencion="{ item }">
                            <v-edit-dialog v-if="!empty(item.ci)" :return-value.sync="item.id_designado" save-text="Guardar"
                                cancel-text="Cancelar" large persistent v-model:return-value="item"
                                @save="save_atender(item)" @open="openEditDoctor(item)">

                                <template v-if="!empty(item.id_designado)">

                                    <v-autocomplete closable-chips v-model="item.id_designado" :items="doctores"
                                        :item-text="item => (item.nombres + ' ' + item.ap_paterno + ' ' + item.ap_materno)"
                                        :item-value="item => item.id" :rules="selecionRules" cache-items class="mx-4" flat
                                        hide-no-data hide-details label="Escriba el nombre del medico">

                                    </v-autocomplete>
                                </template>
                                <template v-else>
                                    <div>
                                        No se presento
                                    </div>

                                </template>

                                <template v-if="!empty(item.ci)" v-slot:input>
                                    <v-autocomplete auto-select-first clearable ref="autocompleteRef"
                                        v-model="item.id_designado" :items="doctores"
                                        :item-text="item => (item.nombres + ' ' + item.ap_paterno + ' ' + item.ap_materno)"
                                        :item-value="item => item.id" :rules="selecionRules" class="mx-4" flat hide-details
                                        label="Escriba el nombre del medico"
                                        :menu-props="{ closeOnClick: false, closeOnContentClick: false }">

                                    </v-autocomplete>

                                </template>
                            </v-edit-dialog>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
        </v-card>
        <!-- Cuadro de diálogo superpuesto -->
        <v-dialog eager v-model="dialogConfig" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar flat dark color="primary">
                    <v-btn icon dark @click="cerrardialogConfi()">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Configuracion de Horarios</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <!---
                        <v-btn dark text @click="dialogConfig = false">
                            Guardar
                        </v-btn>
                        -->
                    </v-toolbar-items>

                </v-toolbar>
                <v-card-text>
                    <v-list three-line subheader>
                        <v-subheader>Configuracion</v-subheader>
                    </v-list>
                    <v-row>
                        <v-col cols="6">
                            Lugar de la cita:
                            <v-select disabled v-model="cita_nueva.lugar" :items="lugar" @change="mostrar_datos"
                                label="Lugar de la cita" no-data-text="No se encontraron resultados" item-text='direccion'
                                item-value="codigo">
                            </v-select>
                        </v-col>
                        <v-col cols="6">
                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="fecha_calendario" label="Picker without buttons"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="fecha_calendario" @input="menu2 = false"
                                    locale="bo-BO"></v-date-picker>
                            </v-menu>
                        </v-col>

                    </v-row>
                    <agenda v-if="dialogConfig" :fecha_x="fecha_calendario" :cita_nueva="cita_nueva.lugar"></agenda>
                       
                </v-card-text>
            </v-card>
            
        </v-dialog>


        <v-dialog v-model="dialogVisible" fullscreen hide-overlay persistent color="blue"
            transition="dialog-bottom-transition">
            <!-- Contenido del cuadro de diálogo -->
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon @click="cerrar_imprimir()">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Boletas de Cita</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <!--<v-btn dark text @click="dialog = false">
                            Save
                        </v-btn>-->
                    </v-toolbar-items>
                </v-toolbar>
                <!-- Toolbar -->



                <!-- Contenido del diálogo -->
                <v-card-title>
                    <!-- Puedes agregar un título aquí si es necesario -->
                </v-card-title>
                <v-card-text>
                    <imprimir v-if="dialogVisible" ref="print">
                    </imprimir>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialog_equipo" fullscreen hide-overlay persistent :scrim="false"
            transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="blue">
                    <v-btn icon dark @click="close_atencion()">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Configuracion Atencion</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <!--<v-btn variant="text" @click="dialog_equipo = false">
                            Guardar
                        </v-btn>-->
                    </v-toolbar-items>
                </v-toolbar>
                <v-card>
                    <mequipo v-if="dialog_equipo" :fecha="fecha_calendario" :datos="selectedEvent.consultorio">
                    </mequipo>
                </v-card>



            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogBusquedaTotal" persistent hide-overlay color="blue" transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="black">
                    <v-btn icon dark @click="closeBusqueda">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Buscar total</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="dialogBusquedaTotal = false">
                            Guardar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-title></v-card-title>
                <v-card-text>
                    <buscartotal v-if="dialogBusquedaTotal"></buscartotal>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog_imprimir" persistent max-width="500px" hide-overlay color="blue"
            transition="dialog-bottom-transition">
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
        </v-dialog>



        <datos v-if="dialog_persona" @pedir='actualizador' ref="dato">
        </datos>
        <atencion v-if="estado == 'atencion'" ref="atender" @pedir='actualizador' :equipo="lista_equipo"></atencion>
        <!-- Diálogo de confirmación -->
        <v-dialog v-model="dialogDeletecita" max-width="400">
            <v-card>
                <v-card-title>Confirmar Eliminación</v-card-title>
                <v-card-text>
                    ¿Estás seguro de que deseas eliminar estos datos?
                </v-card-text>
                <v-card-actions>
                    <!-- Botón para confirmar la eliminación -->
                    <v-btn color="error" @click="eliminarDatos">Eliminar</v-btn>
                    <!-- Botón para cerrar el diálogo -->
                    <v-btn @click="cerrarDialogo">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogNuevoHorario" max-width="500px" color="blue" transition="dialog-bottom-transition">
            <!-- Contenido del cuadro de diálogo -->
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon @click="cerrar_nuevo_horario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Crear Nuevo Horario</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <!--<v-btn dark text @click="dialog = false">
                            Save
                        </v-btn>-->
                    </v-toolbar-items>
                </v-toolbar>
                <!-- Toolbar -->



                <!-- Contenido del diálogo -->
                <v-card-title>
                    Se creara un nuevo ficha con el siguiente horario.
                </v-card-title>
                <v-card-text class="d-flex justify-center">

                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Nuevo horario </v-list-item-title>
                            <v-list-item-subtitle class=" text--primary">
                                {{ formatearHora(seleccionHorario.hora_inicio) }} - {{
                                    formatearHora(seleccionHorario.hora_final) }}
                            </v-list-item-subtitle>
                            <v-list-item-title>Para Fecha </v-list-item-title>
                            <v-list-item-subtitle class=" text--primary">
                                {{ fecha_calendario }}
                            </v-list-item-subtitle>
                            <v-list-item-title>Lugar </v-list-item-title>
                            <v-list-item-subtitle class=" text--primary">
                                <v-select dense disabled v-model="cita_nueva.lugar" :items="lugar" label="Lugar de la cita"
                                    no-data-text="No se encontraron resultados" item-text='direccion' item-value="codigo">
                                </v-select>
                            </v-list-item-subtitle>

                        </v-list-item-content>
                    </v-list-item>

                </v-card-text>
                <v-card-title class="d-flex justify-end">
                    ¿Le parece bien este horario?
                </v-card-title>

                <v-card-actions class="d-flex justify-end">
                    <v-btn dark color="deep-purple lighten-2" @click="crear_horario(seleccionHorario)">
                        Aceptar
                    </v-btn>
                    <v-btn color="deep-purple lighten-2" text @click="cerrar_nuevo_horario">
                        Cancelar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


    </v-app>
</template>

<style scoped>
.custom-table .v-data-table__row {
    border-bottom: 5px solid #e0e0e0;
    /* Cambia el color y estilo de la línea de separación */
}

.bordered-row {
    border-bottom: 12px solid #ccc;
    /* Agrega un borde inferior de 1 píxel con color gris claro */
}

.divider-container {
    margin: 8px 0;
    /* Margen vertical alrededor del divisor */
    padding: 8px;
    /* Relleno interno del contenedor */
}

.my-divider {
    height: 4px;
    /* Establece el grosor que desees */
}

.my-card {
    min-width: 350px;
    /* Tamaño mínimo para escritorio u otros dispositivos grandes */

    @media (max-width: 600px) {
        min-width: 250px;
        /* Tamaño menor para dispositivos móviles (ancho máximo de 600px) */
    }
}

.custom-calendar {
    font-size: 20px !important;

    /* Tamaño de fuente personalizado */
    /* Otros estilos personalizados según tus preferencias */
}
</style>

<script>
import moment from 'moment'
import buscar from '@/Pages/Micomponet/Buscar'
import datos from '@/Pages/Micomponet/Datospersonales'
import atencion from '@/Pages/Micomponet/Atencion'
import imprimir from '@/Pages/Micomponet/imprimir'
import agenda from '@/Pages/Micomponet/Agenda3'

import mequipo from '@/Pages/Configuracion/seleccionequipo'
import confihorario from '@/Pages/Micomponet/SalaEspera'
import buscartotal from '@/Pages/Micomponet/Buscar'


import viaje from '@/Pages/Configuracion/viaje'
import { triggerRef } from 'vue'

//const sortBy = ref([{ key: 'hora_inicio', order: 'asc' }])
export default {
    components: {
        buscar,
        datos,
        atencion,
        mequipo,
        viaje,
        imprimir,
        confihorario,


        buscartotal,
        agenda
        //config
    },
    watch: {

        equipos(nuevo, anterior) {
            console.log('/*/*/*/*');
            console.log(nuevo)
            console.log('-------0');
            console.log(anterior)
            const schedules = nuevo; // Sustituye "this.schedules" por tu fuente de datos
            const groupedSchedules = {};
            let datos = [];
            this.agruparCodigo = schedules.forEach(schedule => {
                if (!groupedSchedules[schedule.id_horario]) {
                    groupedSchedules[schedule.id_horario] = [];

                    groupedSchedules[schedule.id_horario] =
                    {
                        id_horario: schedule.id_horario,
                        id_conf_sala: schedule.id_conf_sala,
                        hora_inicio: schedule.hora_inicio,
                        hora_final: schedule.hora_final,

                    };
                }
            });
            console.log('-----------');
            this.agruparCodigo = groupedSchedules;
            //console.log(groupedSchedules[11]);
            console.log(this.agruparCodigo);
        }
    },
    data: () => ({
        departamentos: {
            'BENI': "BE",
            'CHUQUISACA': "CH",
            'COCHABAMBA': "CB",
            'LA PAZ': "LP",
            'ORURO': "OR",
            'PANTOJA': "PA",
            'POTOSI': "PT",
            'SANTA CRUZ': "SC",
            'TARIJA': "TJ",
        },
        dialogNuevoHorario: false,
        seleccionHorario: {},
        agruparCodigo: [],
        menu2: false,
        dialogConfig: false,
        codigo_lugar: '01',
        tiposSeleccion: ['Todos', 'Con cita', 'Sin Cita'],
        dialogDeletecita: false, // Controla la visibilidad del diálogo
        dialogVisible: false,
        dialogBusquedaTotal: false,
        value: '',
        ready: false,
        itemsPerPage: -1,
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
        doctores: [],
        doctor: [],
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
        tipo_seleccion: 0,
        optionsCitas: [
            { text: 'Todas las citas', value: 0 },
            { text: 'Citas asignadas', value: 1 },
            { text: 'Citas disponibles', value: 2 },
        ],
        estadolabel: '0',
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
        datos_eliminar: {},
        search: '',
        lugar: [],
        cita_nueva: {
            lugar: '01'
        },
        configuraciones: [],

    }),
    created() {

        this.pedir_datos()
    },
    updated() {

    },
    mounted() {


        //console.log();
    },
    computed: {

        groupBy() {
            return this.equipos.reduce((groups, item) => {
                console.log(item)
                const key = `${item.id_horario}`
                groups[key] = groups[key] || {
                    header: `${item.id_horario}`,
                    items: []
                }
                groups[key].items.push(item)
                console.log(groups);
                return groups
            }, {})
        },
        filteredItems() {
            // Filtrar los items basados en la cadena de búsqueda
            return this.equipos.filter(item =>
                Object.values(item).some(value =>
                    String(value).toLowerCase().includes(this.search.toLowerCase())
                )
            );
        },
        headers() {
            return [
                // { text: 'ID', value: 'id_horario' },
                {
                    text: 'Hora de inicio',
                    value: 'id_horario',
                    //groupable: false,
                },
                /*{
                    text: 'Hora de inicio',
                    value: 'hora_inicio',
                    //key: 'hora_inicio',

                },
                { text: 'Hora final', value: 'hora_final' },*/
                {
                    text: "Nombres Completo",
                    value: "nombres",
                },

                {
                    text: 'Cedula de identidad',
                    value: 'ci',
                    filter: this.ciFilter

                },
                {
                    text: 'Atendido',
                    value: 'atencion',
                }

                // Agrega más headers según tus necesidades
            ]
        },
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
        cerrardialogConfi() {
            this.pedir_datos();
            this.dialogConfig = false
        },
        cerrar_nuevo_horario() {
            this.dialogNuevoHorario = false
            this.pedir_datos()
        },
        async crear_horario(datos) {

            datos.fecha = this.fecha_calendario
            datos.lugar = this.cita_nueva.lugar
            axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/crear_horario`,
                data: datos,
            })
                .then((response) => {
                    console.log('=============');
                    console.log(response);
                    this.cerrar_nuevo_horario()
                    //alert('resp')
                    /*console.log(response);
                    this.desserts = response.data;
                    console.log(response.data);*/
                })
                .catch((error) => {
                    if (error.response) {
                        console.error("Error de respuesta:", error.response.data);
                        // Puedes manejar el error de la manera que necesites aquí.
                    } else if (error.request) {
                        console.error("Error de solicitud:", error.request);
                    } else {
                        console.error("Error:", error.message);
                    }
                });

        },
        customSort: function (items, index, isDesc) {
            console.log(items, index, isDesc);
            //return items

            items.sort((a, b) => {
                if (index[0] !== 'id_horario') {
                    return 0;
                }

                // Obtener las marcas de tiempo de Unix de las horas
                const horaA = moment(a.hora_inicio, "HH:mm").format("X");
                const horaB = moment(b.hora_inicio, "HH:mm").format("X");

                // Calcular la diferencia de tiempo
                const tiempo = horaA - horaB;

                // Si las horas son iguales, ordenar por id_persona
                if (tiempo === 0) {
                    if (a.id_persona === null) {
                        return -1;
                    } else if (b.id_persona === null) {
                        return 1;
                    }
                    return a.id_persona - b.id_persona;
                }

                // Ordenar por hora_inicio
                return tiempo;

            });
            return items;
        },
        agregarItem(group) {
            this.seleccionHorario = this.agruparCodigo[group]
            this.dialogNuevoHorario = true
            console.log(group)
        },
        procesarDatos() {
            // Agrupa los datos por sala

            const salas = this.equipos.reduce((acc, item) => {
                const salaExistente = acc.find((sala) => sala.id_sala === item.id_sala);
                if (salaExistente) {
                    salaExistente.horarios.push({
                        id: item.id_horario,
                        hora_inicio: item.hora_inicio,
                        hora_final: item.hora_final,
                    });
                } else {
                    acc.push({
                        id_sala: item.id_sala,
                        descripcion: item.descripcion,
                        horarios: [
                            {
                                id: item.id_horario,
                                hora_inicio: item.hora_inicio,
                                hora_final: item.hora_final,
                            },
                        ],
                    });
                }
                return acc;
            }, []);

            // Asigna el resultado a la variable salas
            this.configuraciones = salas;
            console.log('/*****************/');
            console.log(this.configuraciones);
            this.dialogConfig = true
        },
        openEditDoctor(x) {
            this.$nextTick(() => {

                const autocomplete = this.$refs.autocompleteRef;
                autocomplete.$el.click();
                autocomplete.$el.click();
                console.log('coloololololo');
            });

        },

        funcionPrueba(x) {
            alert(x)
        },

        async closeBusqueda() {
            this.mostrar_datos()
            this.dialogBusquedaTotal = false
        },
        async mostrar_datos() {
            if (this.cita_nueva.lugar) {
                this.cita_nueva.fecha = this.fecha_calendario
                axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/lista_tabla`,
                    data: this.cita_nueva,
                })
                    .then((response) => {
                        console.log(response);
                        this.equipos = structuredClone(response.data['salas_diponibles']);
                        this.lugar = response.data['lugar']
                        console.log('/**/*/*/*/*');

                        this.doctores = response.data['doctor']
                        console.log(this.doctores);
                        //alert('resp')
                        /*console.log(response);
                        this.desserts = response.data;
                        console.log(response.data);*/
                    })
                    .catch((error) => {
                        if (error.response) {
                            console.error("Error de respuesta:", error.response.data);
                            // Puedes manejar el error de la manera que necesites aquí.
                        } else if (error.request) {
                            console.error("Error de solicitud:", error.request);
                        } else {
                            console.error("Error:", error.message);
                        }
                    });



            }


        },

        mostrarLugares() {
            this.cita_nueva.fecha = this.fecha_calendario

        },
        seleccionarOpcion(value) {
            this.tipo_seleccion = value.value
        },
        handleOptionChange() {
            console.log('casasasasa');
            // Enfocar el v-data-table después de cambiar la opción
            this.$nextTick(() => {
                this.$refs.dataTableRef.$el.focus();
            });
        },
        ciFilter(value) {
            ///console.log(this.caloriesFilterValue);
            console.log('llllllfsdladfjlk');
            // If this filter has no value we just skip the entire filter.
            if (this.tipo_seleccion === 0) return true
            if (!this.empty(value) && this.tipo_seleccion === 1) {
                return true;
            }
            if (this.empty(value) && this.tipo_seleccion === 2) {
                return true;
            }

            return false

        },
        cambiarEstado() {
            // Lógica para cambiar el estado
            console.log('/////////' + this.tipo_seleccion);
            this.tipo_seleccion = (this.tipo_seleccion + 1) % 3;
            this.estadolabel = '' + this.tipo_seleccion
        },
        customFilter(value, search, item) {
            console.log('????????????????');
            console.log(item)

            return (
                this.empty(item.id)
                //item.edad.toString().includes(search)
                // Agrega más condiciones según tus columnas
            );
        },
        formatearHora(horaInicio) {

            const formato = moment(horaInicio, 'HH:mm:ss').format('HH:mm');
            const esTarde = moment(horaInicio, 'HH:mm:ss').isAfter(moment('12:00:00', 'HH:mm:ss'), 'second');

            return `${formato} ${esTarde ? 'tarde' : 'mañana'}`;
        },
        mostrarDialogo2(x) {
            this.datos_eliminar = structuredClone(x)
            this.dialogDeletecita = true; // Abre el diálogo de confirmación
        },

        mostrarDialogo() {
            this.dialogDeletecita = true; // Abre el diálogo de confirmación
        },
        cerrarDialogo() {
            this.dialogDeletecita = false; // Cierra el diálogo de confirmación
        },
        eliminarDatos() {
            // Aquí puedes agregar la lógica para eliminar los datos
            // Después de eliminar, cierra el diálogo
            this.eliminar_ficha()
        },
        empty(variable) {
            if (
                variable === null ||
                variable === undefined ||
                (typeof variable === 'number' && isNaN(variable)) ||
                (typeof variable === 'string' && variable.trim() === '') ||
                (Array.isArray(variable) && variable.length === 0)
            ) {
                return true;
            } else {
                return false;
            }
        },
        cerrar_imprimir() {
            this.$store.dispatch('guardar_imprimir', null);
            localStorage.removeItem("usuario");
            this.dialogVisible = false
        },
        imprimir_datos() {
            this.selectedOpen = false
            console.log('/*/*/*/*/*/*/')
            //console.log(this.selectedEvent.fichas)
            this.$store.dispatch('guardar_imprimir', this.selectedEvent.fichas);
            localStorage.setItem("usuario", JSON.stringify(this.selectedEvent.fichas));
            setTimeout(() => {

            }, 2);
            this.dialogVisible = true

        },
        imprimir_tabla(x) {
            this.selectedOpen = false
            console.log('/*/*/*/*/*/*/')
            //console.log(this.selectedEvent.fichas)
            this.$store.dispatch('guardar_imprimir', x);
            localStorage.setItem("usuario", JSON.stringify(x));
            setTimeout(() => {

            }, 2);
            this.dialogVisible = true

        },

        comparaFechas() {
            const fechaActual = new Date(this.$store.getters.getfecha_server + 'T00:00:00');
            const fechaCa = new Date(this.fecha_calendario + 'T00:00:00');
            /*console.log(this.$store.getters.getfecha_server);
            console.log(fechaActual,'<',fechaCa);*/
            //console.log(fechaActual < fechaCa);
            return fechaActual <= fechaCa;
        },
        async eliminar_atender(e) {
            let f = e.id_ficha
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
        },
        async pedir_datos(date) {
            console.log(this.fecha_calendario);

            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/lista_tabla/` + this.fecha_calendario,
            }).then(
                (response) => {
                    console.log(response);
                    let salas = response.data['salas'];
                    let salas_disponibles = response.data['salas_diponibles'];
                    this.equipos = structuredClone(response.data['salas_diponibles']);
                    this.lugar = response.data['lugar']
                    this.doctores = response.data['doctor']
                    /*console.log('_equipo----');
                    console.log(this.equ999ipos);
                    console.log(salas_disponibles);
                    */
                    //console.log('__'+salas)
                    /*
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
                        
                        console.log('-000000---');
                        console.log(salas);
                        this.categories.push(salas[key]['descripcion'])
                        this.events.push({
                            name: this.empty(salas[key]['id_municipio']) ? salas[key]['nombre_equipo'] : salas[key]['nombre_equipo'] + " " + salas[key]['municipio'],
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
                    //}
                    /*
                                        this.equipos = structuredClone(salas_disponibles)
                                        console.log(this.fecha_calendario)
                                        for (const key in salas_disponibles) {
                                            let fichas = salas_disponibles[key];
                                            for (const x in fichas) {
                                                let ficha = fichas[x];
                                                console.log(ficha.nombres);
                                                console.log(ficha.id);
                                                console.log(ficha.id_equipo);
                                                console.log(ficha.id_sala);
                    
                                                let categoria = ''
                                                for (const key in this.salas) {
                                                    /*console.log('------');
                                                    console.log(this.salas[key]);
                                                    console.log(this.salas[key].id_equipo, ' ', ficha.id_equipo);
                                                    
                                                    if (this.salas[key].id_equipo == ficha.id_equipo && this.salas[key].id_sala == ficha.id_sala) {
                                                        //console.log('si');
                                                        //categoria=this.categories[key]
                    
                                                        this.events.push({
                                                            name: (!ficha.ci) ? 'Sin asignar' : ficha.nombres,
                                                            //paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                                            start: new Date(this.fecha_calendario + 'T' + ficha.hora_inicio + '-04:00'),
                                                            end: new Date(this.fecha_calendario + 'T' + ficha.hora_final + '-04:00'),
                                                            color: (!ficha.ci) ? '#595959' : (!ficha.id_designado) ? 'blue' : 'green',
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
                                                
                                            }
                                        }*/
                    /*this.ready = true
                    this.scrollToTime()
                    this.updateTime()*/
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
        async save_atender(item) {
            /*verificar que no existe equipo designado */
            //this.$refs.seleccion_equipo.validate()
            console.log(';;;;;;;;;;;;;;;;;');

            console.log(item);
            if (this.empty(item.id_designado)) {
                this.eliminar_atender(item)
                return;
            }
            const selectedObject = this.doctores.find(x => x.id === item.id_designado);

            console.log(item.id_designado + '  ' + selectedObject.id);
            if (selectedObject) {
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/atender`,
                    data: {
                        ficha: item,
                        equipo: item.id_designado
                    }
                }).then(
                    (response) => {
                        console.log("atender");
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
            else {
                alert('eliminado')
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
                url: `/${process.env.MIX_CARPETA}/dar_ficha/` + this.datos_eliminar.id_ficha,
                data:
                    this.datos_eliminar,
            }).then(
                (response) => {
                    console.log('**********')
                    console.log(response)
                    this.pedir_datos()
                    this.selectedOpen = false
                    this.cerrarDialogo()
                }
            ).catch(err => {
                console.log(err)
                console.log(err.response)

            });
        },
        async atencion_equipos() {
            console.log('tess');
            console.log(`/${process.env.MIX_CARPETA}/api/equipos_elegidos`);
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/equipos_elegidos`,
                data:
                    this.selectedEvent.fichas,
                //equipo: this.selectequipo.equipo

            }).then(
                (response) => {
                    var r = response.data.seleccion
                    console.log('equipos/**//*/*/*/*');
                    console.log(response.data);
                    console.log('------------00000-');

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
            return (!ficha.id) ? 'white' : (!ficha.id_designado) ? 'blue' : 'green'
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
            console.log('se selecciono el evento');
            console.log(event);
            console.log('este se selecciono');
            if (event.equipo == true) {
                this.selectedEvent = structuredClone(event)
                this.dialog_equipo = true;
                return;
            }

            if (event.name == 'Atencion') {
                //this.dialog = true
                //this.selectedEvent = event

                console.log('atencion quien atendera al equipo');
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
                console.log('-----*-*--*-*-*-*-');
                console.log('quien agendara los datos');
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
            console.log('....venta de opciones');
            console.log(X);

            if (typeof X.fichas.ci == 'undefined') {
                return null
            }

            return X.fichas.ci
        },
        open_agenda(x) {


            this.selectedOpen = false
            this.dialog_persona = true
            setTimeout(() => {
                this.$refs.dato.op1 = 1;
                this.$refs.dato.fecha_cita = this.fecha_calendario
                this.$refs.dato.cita_nueva = null
                this.$refs.dato.cita_nueva = x//this.selectedEvent.fichas
                this.$refs.dato.cita_nueva.fecha = this.fecha_calendario
                //this.$refs.dato.cita_nueva.inst = this.lugar

                this.$refs.dato.open()
                if (!this.empty(x.ci)) {
                    this.$refs.dato.mostrarDatos(x)
                    this.$refs.dato.op1 = 2;
                }
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
            console.log('22222222222292');
            console.log(this.cal.updateTimes());
            setInterval(() => this.cal.updateTimes(), 60 * 2000)
        },
        close_atencion() {
            this.dialog_equipo = false
            this.pedir_datos();
        }
    }
}

</script>


