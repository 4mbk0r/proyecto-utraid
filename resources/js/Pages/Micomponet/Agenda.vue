<template>
    <v-app>
        <div>
            <v-container>

                <v-row>
                    <v-col>
                        <v-sheet height="64">
                            <v-toolbar>
                                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                                    Hoy2
                                </v-btn>
                                <!--onkeydown="return false"-->
                                <!--<v-text-field v-model="fecha_uso"  :min="fechacitaMin" :rules="[ fechaValida ]" type="date" label="Fecha De Cita" >
                        </v-text-field>-->
                                <input id="txtDate" type="date" refs="seldate" v-model="fecha_hoy" @change="formatDate" />
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
                                    <!--{{ $refs.calendar.title }}-->
                                    {{ textoDate(fecha_hoy) }}
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <button type="button"
                                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Agregar</button>

                            </v-toolbar>

                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-sheet height="500">
                            <!--@change=""-->
                            <v-calendar ref="calendar" v-model="focus" color="primary" type="category" category-show-all
                                :categories="categories" :events="events" :event-color="getEventColor"
                                :event-class="customEventClass" @click:event="showEvent" :first-interval=7
                                :interval-minutes=60 :interval-count=12>
                            </v-calendar>
                        </v-sheet>

                    </v-col>
                </v-row>
            </v-container>


        </div>
    </v-app>
</template>

<script>
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)
import buscar from '@/Pages/Micomponet/Buscar'
export default {
    components: {
        buscar,
    },
    props: {
        datos_cita: Array,

    },
    data: () => ({
        av_buscar: false,
        msg: [],
        fecha_hoy: day1,
        anterior_dia: '',
        focus: '',
        events: [],
        categories: [],

    }),
    mounted() {
        this.$refs.calendar.checkChange()
    },
    created() {
        this.initialize()
    },
    methods: {
        customEventClass({ event }) {
            return 'custom-event';
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
        initialize() {
            /*this.addelemento(this.datos_cita)
            this.doctorlista()*/
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

            //this.msg['fecha_validacion'] = '';
            this.focus = ''
            var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
            this.anterior_dia = today;
            this.fecha_hoy = today;

            //this.traerdatos();
            //alert("anterios dia"+this.anterior_dia+"hoy"+this.fecha_hoy);

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

            var dateStr = this.fecha_hoy + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            var contador = 1;
            f.setDate(f.getDate() + valor);
            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;
            }
            this.fecha_hoy = f.toISOString().substr(0, 10);
            this.anterior_dia = this.fecha_hoy;
            return contador;
        },
        prev() {
            var valor = -1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.prev(contador);
            //this.traerdatos();
        },
        next() {
            var valor = 1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.next(contador);
            //this.traerdatos();
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        async formatDate() {




        },
        textoDate(val) {
            var dia = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septimbre', 'Octobre', 'Novembre', 'Diciembre'];
            var dateStr = val + 'T00:00:00-04:00';
            var now = new Date(dateStr)
            return dia[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            //this.focus = val;
            //alert(val);
            //alert(" =>"+ver1+" "+ver2);
            //this.$emit('input', dateObj);
        },
    },
    computed: {

    },
}
</script>
